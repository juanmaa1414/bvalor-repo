<?php

namespace App\Http\Controllers;

class InicioController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

	public function index()
	{
		// La vista "parcial" se guarda en variable.
        $view = '<div class="container"><h3>Sistema de socios y pagos</h3></div>';

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nuevo socio", "view" => $view]);
	}

}
