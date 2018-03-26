<?php
namespace App\Http\Controllers;

use App\Models\Campania;
use App\DAO\MySQL\CampaniasDao;
use Illuminate\Http\Request;

class CampaniasController extends Controller
{
    protected $campaniasDao;

    /**
     * Implementamos la funcion mágica construct,
     * llamada cuando se crea nuestro objeto controller.
     * Aqui establecemos las dependencias que va a usar la clase.
     */
    public function __construct(CampaniasDao $an_campaniasDao)
    {
        $this->campaniasDao = $an_campaniasDao;
    }

    public function listed()
    {
        $res = $this->campaniasDao->search();
		$viewData = [
            'result' => $res
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('campania.list', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Campañas", "view" => $view]);
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
        $view = view('campania.edit_create', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nueva Campaña", "view" => $view]);
    }

    /**
     * Almacena un recurso enviado por medio de formulario web.
     */
    public function store(Request $request)
    {
        // TODO: Actualizar validaciones a ser correctas.
        $this->validate($request, [
            'nombre' => 'required',
            'valorNumero' => 'required',
            
        ]);

        $nombre = $request->input('nombre');
        $valorNumero = $request->input('valorNumero');
        

        $campania = new campania(null, $nombre, $valorNumero, 
                             TRUE);

        // codejobs.biz/www/lib/files/images/3240aa0fe3ca150.png
        $resOk = FALSE;
        try {
            $resOk = $this->campaniasDao->save($campania);
        } catch (Exception $e) {
            //echo $e->getMessage();
            // TODO: Implementar log de volcado errores (y forma de mostrarlos al user)
        }

        $viewData = [
            "success" => $resOk,
            "message" => $resOk? "El registro se cargó con éxito." : "No se pudo cargar el registro.",
            "btn_href" => route('campanias/create')
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('messages.success_view', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nueva Campaña", "view" => $view]);
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');
        $campania = $this->campaniasDao->findById($id);

        $viewData = [
            'camp' => $campania,
            'editing' => TRUE
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('campania.edit_create', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nueva Campaña", "view" => $view]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|int',
            'nombre' => 'required',
            'valorNumero' => 'required',
            
        ]);

        // Traer al registro.
        $vend = $this->campaniasDao->findById($request->input('id'));

        // Setear lo que creemos necesario.
        $camp->setNombre($request->input('nombre'));
        $camp->setvalorNumero($request->input('valorNumero'));
        

        $resOk = FALSE;
        try {
            $resOk = $this->campaniasDao->save($vend);
        } catch (Exception $e) {
            //echo $e->getMessage();exit;
            // TODO: Implementar log de volcado errores
        }

        $viewData = [
            "success" => $resOk,
            "message" => $resOk? "El registro se modificó con éxito." : "No se pudo editar el registro.",
            "btn_href" => route('campanias/listed')
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('messages.success_view', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Editar campaña", "view" => $view]);
    }

}
