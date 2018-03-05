<?php
namespace App\Http\Controllers;

use App\Models\Vendedor;
use App\DAO\MySQL\VendedoresDao;
use Illuminate\Http\Request;

class VendedoresController extends Controller
{
    protected $vendedoresDao;

    /**
     * Implementamos la funcion mágica construct,
     * llamada cuando se crea nuestro objeto controller.
     * Aqui establecemos las dependencias que va a usar la clase.
     */
    public function __construct(VendedoresDao $an_vendedoresDao)
    {
        $this->vendedoresDao = $an_vendedoresDao;
    }

    public function listed()
    {
        $res = $this->vendedoresDao->search();
		$viewData = [
            'result' => $res
        ];
        
        // La vista "parcial" se guarda en variable.
        $view = view('vendedor.list', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nuevo vendedor", "view" => $view]);
    }

    /**
     * Presenta el formulario web para cargar un recurso nuevo.
     */
    public function create()
    {
        $viewData = [
            'editing' => FALSE // indica que no es modificacion
        ];
        
        // La vista "parcial" se guarda en variable.
        $view = view('vendedor.edit_create', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nuevo vendedor", "view" => $view]);
    }

    /**
     * Almacena un recurso enviado por medio de formulario web.
     */
    public function store(Request $request)
    {
        // TODO: Actualizar validaciones a ser correctas.
        $this->validate($request, [
            'nombreCompleto' => 'required',
            'domicilio' => 'required',
            'telefono' => 'required',
            'codTipoDocUnico' => 'required|int',
            'numeroDocUnico' => 'required|int'
        ]);
        
        $nombreCompleto = $request->input('nombreCompleto');
        $domicilio = $request->input('domicilio');
        $telefono = $request->input('telefono');
        $codTipoDocUnico = $request->input('codTipoDocUnico');
        $numeroDocUnico = $request->input('numeroDocUnico');
        
        $vendedor = new Vendedor(null, $nombreCompleto, $codTipoDocUnico, $numeroDocUnico,
                            $domicilio, $telefono, TRUE);
        
        // codejobs.biz/www/lib/files/images/3240aa0fe3ca150.png
        $resOk = FALSE;
        try {
            $resOk = $this->vendedoresDao->save($vendedor);
        } catch (Exception $e) {
            //echo $e->getMessage();
            // TODO: Implementar log de volcado errores (y forma de mostrarlos al user)
        }
        
        $viewData = [
            "success" => $resOk,
            "message" => $resOk? "El registro se cargó con éxito." : "No se pudo cargar el registro.",
            "btn_href" => route('vendedores/create')
        ];
        
        // La vista "parcial" se guarda en variable.
        $view = view('messages.success_view', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nuevo vendedor", "view" => $view]);
    }
    
    public function edit(Request $request)
    {
        $id = $request->input('id');
        $vendedor = $this->vendedoresDao->findById($id);
        
        $viewData = [
            'vend' => $vendedor,
            'editing' => TRUE
        ];
        
        // La vista "parcial" se guarda en variable.
        $view = view('vendedor.edit_create', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nuevo vendedor", "view" => $view]);
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|int',
            'nombreCompleto' => 'required',
            'domicilio' => 'required',
            'telefono' => 'required',
            'codTipoDocUnico' => 'required|int',
            'numeroDocUnico' => 'required|int'
        ]);
        
        // Traer al registro.
        $vend = $this->vendedoresDao->findById($request->input('id'));
        
        // Setear lo que creemos necesario.
        $vend->setNombreCompleto($request->input('nombreCompleto'));
        $vend->setNombreCompleto($request->input('nombreCompleto'));
        $vend->setCodTipoDocUnico($request->input('codTipoDocUnico'));
        $vend->setNumeroDocUnico($request->input('numeroDocUnico'));
        $vend->setDomicilio($request->input('domicilio'));
        $vend->setTelefono($request->input('telefono'));
        
        $resOk = FALSE;
        try {
            $resOk = $this->vendedoresDao->save($vend);
        } catch (Exception $e) {
            //echo $e->getMessage();exit;
            // TODO: Implementar log de volcado errores
        }
        
        $viewData = [
            "success" => $resOk,
            "message" => $resOk? "El registro se modificó con éxito." : "No se pudo editar el registro.",
            "btn_href" => route('vendedores/listed')
        ];
        
        // La vista "parcial" se guarda en variable.
        $view = view('messages.success_view', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Editar vendedor", "view" => $view]);
    }
    
    public function searchByNameAjax(Request $request)
    {
        $search['nombre_dni'] = $request->input('terms');
        
        $res = $this->vendedoresDao->search($search);
        
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
