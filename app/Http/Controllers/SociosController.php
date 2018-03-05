<?php
namespace App\Http\Controllers;

use App\DAO\MySQL\SociosDao;
use App\DAO\MySQL\SociosInfoDao;
use App\Models\Socio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // TODO: move to a general controller

class SociosController extends Controller
{
    protected $sociosDao;
    protected $sociosInfoDao;

    /**
     * Implementamos la funcion mágica construct,
     * llamada cuando se crea nuestro objeto controller.
     * Aqui establecemos las dependencias que va a usar la clase.
     */
    public function __construct(SociosDao $sociosDao, SociosInfoDao $sociosInfoDao)
    {
        $this->sociosDao = $sociosDao;
        $this->sociosInfoDao = $sociosInfoDao;
    }

    public function listed()
    {
        $res = $this->sociosDao->search();
		$viewData = [
            'result' => $res
        ];
        
        // La vista "parcial" se guarda en variable.
        $view = view('socio.list', $viewData);

        // Salida del conjunto al browser.
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
        
        $validation = Validator::make($request->all(), $formdata);
        
        if ($validation->fails()) {
            $view = view('messages.success_view', 
                    ['success' => FALSE, 'message' => 'Los datos no son correctos. Deberá volver a intentar']);
            return view('main_layout', ["title" => "Nuevo socio", "view" => $view]);
        }
        
        // Volcar lo recibido en una variable.
        foreach ($formdata as $field => $rules) {
            $form[$field] = $request->input($field);
        }
        
        $form['esActivo'] = $form['esActivo'] ? $form['esActivo'] : 0;
        
        $socio = new Socio(null, $form[nombreCompleto], $form[codTipoDocUnico], $form[numeroDocUnico],
                        $form[domicilio], $form[telefono], $form[codTipoPersona], $form[esActivo]);
        $socio->setIdVendedor($idVendedor);
        $socio->setIdSocioReferente($idSocioReferente);
        
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
        $id = $request->input('id');
        $socioInfo = $this->sociosInfoDao->findBySocioId($id);

        $viewData = [
            'socio' => $socioInfo->getSocio(),
            'vend' => $socioInfo->getVendedor(),
            'socioRef' => $socioInfo->getSocioReferente(),
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

}
