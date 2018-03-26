<?php
namespace App\Http\Controllers;

use App\DAO\MySQL\SociosDao;
use App\DAO\MySQL\SociosFamiliarDao;
use App\DAO\MySQL\VendedoresDao;
use App\Models\Socio;
use App\Models\SocioFamiliar;
use App\Services\LengthPagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // TODO: move to a general controller

class SociosController extends Controller
{
    /**
     * @var SociosDao
     */
    protected $sociosDao;

    /**
     * @var SociosFamiliarDao
     */
    protected $sociosFamiliarDao;

    /**
     * @var VendedoresDao
     */
    protected $vendedoresDao;

    /**
     * Implementamos la funcion mágica construct,
     * llamada cuando se crea nuestro objeto controller.
     * Aqui establecemos las dependencias que va a usar la clase.
     *
     * TODO: Implementar sobre interfaces!
     */
    public function __construct(SociosDao $sociosDao, SociosFamiliarDao $sociosFamiliarDao, VendedoresDao $vendedoresDao)
    {
        $this->sociosDao = $sociosDao;
        $this->sociosFamiliarDao = $sociosFamiliarDao;
        $this->vendedoresDao = $vendedoresDao;
    }

    public function listed(Request $request)
    {
		$page = $request->input('page') ?: '1'; // Paginacion
		$perPage = "5";
        $offset = ($page - 1) * $perPage;

		$terms = []; // Busqueda
		if ($request->has('nombre_dni')) {
			$terms['nombre_dni'] = $request->input('nombre_dni');
		}
		if ($request->has('numeroSocio')) {
			$terms['numeroSocio'] = $request->input('numeroSocio');
		}

		$hasSubmit = $request->input('nombre_dni') !== NULL
					|| $request->input('nombre_dni') !== NULL;

		$res = [];
		// SOLO buscar si hay envio del form, aun aunque se mande vacio.
		if ($hasSubmit) {
			$res = $this->sociosDao->search($terms, $offset, $perPage);
		}
		$totalFound = $this->sociosDao->totalRows();
		$pagelinks = LengthPagerService::makeLengthAware($res, $totalFound, $perPage, $request->all());

		$viewData = [
            'result' => $res,
			'pagelinks' => $pagelinks
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('socio.list', $viewData);

        // La incorporamos a la vista principal y queda lista.
        return view('main_layout', ["title" => "Socios", "view" => $view]);
    }

    /**
     * Presenta el formulario web para cargar un recurso nuevo.
     */
    public function create()
    {
        $viewData = [
            'editing' => false // indica que no es modificacion
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('socio.edit_create', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nuevo socio", "view" => $view]);
    }

    /**
     * Almacena un recurso enviado por medio de formulario web.
     */
    public function store(Request $request)
    {
        $formdata = [
            // TODO: mejorar las reglas.
            'nombreCompleto' => 'required|regex:/^[\pL\s\-]+$/u',
            'codTipoDocUnico' => 'required|int',
            'numeroDocUnico' => 'required|int',
            'domicilio' => 'required',
            'telefono' => 'required',
            'idVendedor' => 'int',
            'codTipoPersona' => 'required|int',
            'idSocioReferente' => 'int',
            'esActivo' => 'int'
        ];

		// TODO: Ver como tratar errores con esto. Lo ideal seria largar errores
		// asi como aca en cualquier lugar, pero solo llamando a una fn a cual
		// pasarle parametros y listo.
        $validation = Validator::make($request->all(), $formdata);

        if ($validation->fails()) {
            $view = view('messages.success_view',
                    ['success' => FALSE, 'message' => 'Los datos no son correctos. Deberá volver a intentar']);
            return view('main_layout', ["title" => "Nuevo socio", "view" => $view]);
        }

        // Volcar lo recibido en una variable.
        $form = [];
        foreach ($formdata as $field => $rules) {
            $form[$field] = $request->input($field);
        }

        $form['esActivo'] = $form['esActivo'] ? $form['esActivo'] : 0;

        $socio = new Socio(null, null, $form['nombreCompleto'], $form['codTipoDocUnico'],
						$form['numeroDocUnico'], $form['domicilio'], $form['telefono'],
						$form['codTipoPersona'], $form['esActivo']);
        $socio->setIdVendedor($form['idVendedor']);
        $socio->setIdSocioReferente($form['idSocioReferente']);

        $resOk = FALSE;
        try {
            $resOk = $this->sociosDao->save($socio);
        } catch (Exception $e) {
            //echo $e->getMessage();
            // TODO: Implementar log de volcado errores
        }

        $viewData = [
            "success" => $resOk,
            "message" => $resOk? "El registro se cargó con éxito." : "No se pudo cargar el registro.",
            "btn_href" => route('socios/create')
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('messages.success_view', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nuevo socio", "view" => $view]);
    }

    public function edit(Request $request)
    {
		$socio = $this->sociosDao->findById($request->input('id'));
        $vendedor = $this->vendedoresDao->findById($socio->getIdVendedor());
        $socioRef = $this->sociosDao->findById($socio->getIdSocioReferente());

        // Vamos ademas a listar los familiares
        $sociosFamiliares = $this->sociosFamiliarDao->search(["id_socio_jefe_familia" => $socio->getId()]);

        $viewData = [
            'socio' => $socio,
            'vend' => $vendedor,
            'socioRef' => $socioRef,
            'sociosFamiliares' =>$sociosFamiliares,
            'editing' => TRUE
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('socio.edit_create', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nuevo socio", "view" => $view]);
    }

    public function update(Request $request)
    {
        // TODO: mejorar.
        $this->validate($request, [
            'id' => 'required|int',
            'nombreCompleto' => 'required|regex:/^[\pL\s\-]+$/u',
            'codTipoDocUnico' => 'required|int',
            'numeroDocUnico' => 'required|int',
            'domicilio' => 'required',
            'telefono' => 'required',
            'idVendedor' => 'int',
            'codTipoPersona' => 'required|int',
            'idSocioReferente' => 'int',
            'esActivo' => 'int'
        ]);

        $esActivo = $request->input('esActivo');
        $esActivo = $esActivo? $esActivo : 0;

        // Traer al registro.
        $socio = $this->sociosDao->findById($request->input('id'));

        // Setear lo que creemos necesario.
        $socio->setNombreCompleto($request->input('nombreCompleto'));
        $socio->setCodTipoDocUnico($request->input('codTipoDocUnico'));
        $socio->setNumeroDocUnico($request->input('numeroDocUnico'));
        $socio->setDomicilio($request->input('domicilio'));
        $socio->setTelefono($request->input('telefono'));
        $socio->setIdVendedor($request->input('idVendedor'));
        $socio->setCodTipoPersona($request->input('codTipoPersona'));
        $socio->setIdSocioReferente($request->input('idSocioReferente'));
        $socio->setEsActivo($esActivo);

        $resOk = FALSE;
        try {
            $resOk = $this->sociosDao->save($socio);
        } catch (Exception $e) {
            //echo $e->getMessage();exit;
            // TODO: Implementar log de volcado errores
        }

        $viewData = [
            "success" => $resOk,
            "message" => $resOk? "El registro se modificó con éxito." : "No se pudo editar el registro.",
            "btn_href" => route('socios/listed')
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('messages.success_view', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Editar socio", "view" => $view]);
    }

    public function searchByNameAjax(Request $request)
    {
        $terms['nombre_dni'] = $request->input('terms');

        $res = $this->sociosDao->search($terms);

        // Presentar como un json array.
        $arr = [];
        foreach ($res as $obj) {
            $arr[] = [
                'id' => $obj->getId(),
                'name' => $obj->getNumeroDocUnico() .' '. $obj->getNombreCompleto()
            ];
        }
        header('Content-Type: application/json');
        return json_encode($arr);
    }

    // =====================================
    // Métodos de Socio familiar
    // =====================================

    /* Quitar este metodo */
    public function familiaresListed(Request $request)
    {
        $idSocioJefe = $request->input('id_socio');

        $socioJefe = $this->sociosDao->findById($idSocioJefe);
        $sociosFamiliares = $this->sociosFamiliarDao->search(["id_socio_jefe_familia" => $idSocioJefe]);
	    $viewData = [
	        'socioJefe' => $socioJefe,
        	'sociosFamiliares' => $sociosFamiliares
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('socio.socio_fam.list', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Socios Familiares", "view" => $view]);
    }

    public function familiaresCreate(Request $request)
    {
		$idSocioJefe = $request->input('id_socio');
		$socioJefe = $this->sociosDao->findById($idSocioJefe);

		$viewData = [
            'editing' => false, // indica que no es modificacion
			'socioJefe' => $socioJefe
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('socio.socio_fam.edit_create', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nuevo familiar", "view" => $view]);
    }

	public function familiaresStore(Request $request)
    {
        $formdata = [
			'idSocioJefeFamilia' => 'required|int',
            'nombreCompleto' => 'required|regex:/^[\pL\s\-]+$/u',
			'codTipoParentesco' => 'required|int',
            'codTipoDocUnico' => 'required|int',
            'numeroDocUnico' => 'required|int',
            'fechaNacimiento' => ''
        ];

        $validation = Validator::make($request->all(), $formdata);

        if ($validation->fails()) {
            $view = view('messages.success_view',
                    ['success' => FALSE, 'message' => 'Los datos no son correctos. Deberá volver a intentar']);
            return view('main_layout', ["title" => "Nuevo familiar", "view" => $view]);
        }

        // Volcar lo recibido en una variable.
        $form = [];
        foreach ($formdata as $field => $rules) {
            $form[$field] = $request->input($field);
        }

        $familiar = new SocioFamiliar(null, $form['idSocioJefeFamilia'], $form['codTipoParentesco'], $form['nombreCompleto'],
							$form['codTipoDocUnico'], $form['numeroDocUnico'], date('Y-m-d', strtotime($form['fechaNacimiento'])));

        $resOk = FALSE;
        try {
            $resOk = $this->sociosFamiliarDao->save($familiar);
        } catch (Exception $e) {
            //echo $e->getMessage();
            // TODO: Implementar log de volcado errores
        }

        $viewData = [
            "success" => $resOk,
            "message" => $resOk? "El registro se cargó con éxito." : "No se pudo cargar el registro.",
            "btn_href" => route('socios/edit', ['id' => $request->input('idSocioJefeFamilia')])
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('messages.success_view', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nuevo familiar", "view" => $view]);
    }

	public function familiaresEdit(Request $request)
    {
		$familiar = $this->sociosFamiliarDao->findById($request->input('id_fam'));
        $socioJefe = $this->sociosDao->findById($familiar->getIdSocioJefeFamilia());

        $viewData = [
            'familiar' => $familiar,
            'socioJefe' => $socioJefe,
            'editing' => TRUE
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('socio.socio_fam.edit_create', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Editar familiar", "view" => $view]);
    }

	public function familiaresUpdate(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|int',
			'idSocioJefeFamilia' => 'required|int',
			'nombreCompleto' => 'required|regex:/^[\pL\s\-]+$/u',
			'codTipoParentesco' => 'required|int',
            'codTipoDocUnico' => 'required|int',
            'numeroDocUnico' => 'required|int',
            'fechaNacimiento' => ''
        ]);

        // Traer al registro.
        $familiar = $this->sociosFamiliarDao->findById($request->input('id'));

        // Setear lo que creemos necesario.
		$familiar->setIdSocioJefeFamilia($request->input('idSocioJefeFamilia'));
		$familiar->setCodTipoParentesco($request->input('codTipoParentesco'));
        $familiar->setNombreCompleto($request->input('nombreCompleto'));
        $familiar->setCodTipoDocUnico($request->input('codTipoDocUnico'));
        $familiar->setNumeroDocUnico($request->input('numeroDocUnico'));
        $familiar->setFechaNacimiento(date('Y-m-d', strtotime($request->input('fechaNacimiento'))));

        $resOk = FALSE;
        try {
            $resOk = $this->sociosFamiliarDao->save($familiar);
        } catch (Exception $e) {
            //echo $e->getMessage();exit;
            // TODO: Implementar log de volcado errores
        }

        $viewData = [
            "success" => $resOk,
            "message" => $resOk? "El registro se modificó con éxito." : "No se pudo editar el registro.",
            "btn_href" => route('socios/edit', ['id' => $request->input('idSocioJefeFamilia')])
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('messages.success_view', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Editar socio", "view" => $view]);
    }

}
