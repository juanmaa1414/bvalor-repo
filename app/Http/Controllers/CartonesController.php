<?php
namespace App\Http\Controllers;

use App\Models\Carton;
use App\DAO\MySQL\CartonesDao;
use Illuminate\Http\Request;

class VendedoresController extends Controller
{
    protected $cartonesDao;

    /**
     * Implementamos la funcion mágica construct,
     * llamada cuando se crea nuestro objeto controller.
     * Aqui establecemos las dependencias que va a usar la clase.
     */
    public function __construct(cartonesDao $an_cartonesDao)
    {
        $this->cartonesDao = $an_cartonesDao;
    }

    public function listed()
    {
        $res = $this->cartonesDao->search();
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
        $numeros = ['22', '23', '24', '25'];
        
        $carton = new Carton(null, $r);
    }
    
    public function edit(Request $request)
    {
        $id = $request->input('id');
        $vendedor = $this->cartonesDao->findById($id);
        
        $viewData = [
            'vend' => $vendedor,
            'editing' => TRUE
        ];
        
        // La vista "parcial" se guarda en variable.
        $view = view('vendedor.edit_create', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nuevo vendedor", "view" => $view]);
    }

}
