<?php
namespace App\Http\Controllers;

use App\Models\Vendedor;
use App\Services\PlanDeAbonoService;
use Illuminate\Http\Request;

class PlanesAbonoController extends Controller
{
    protected $planDeAbonoService;

    public function __construct(PlanDeAbonoService $an_planDeAbonoService)
    {
        $this->planDeAbonoService = $an_planDeAbonoService;
    }

	public function listed()
    {
        $res = $this->planDeAbonoService->buscar();
		$viewData = [
            'result' => $res
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('plan_abono.list', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Planes de abono", "view" => $view]);
    }

	// TODO: Traer el valor del num desde la campaña actual para
	// que quede default en esta vista.
    public function crearPlan(Request $request)
    {
		$viewData = [
            'editing' => FALSE
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('plan_abono.create', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nuevo plan socio", "view" => $view]);
    }

	// TODO: Comprobar que socio no tenga plan para el año indicado
	public function grabarPlan(Request $request)
	{
		//var_dump($request->input('numeroTerminacion') ?: null);return;
		$this->validate($request, [
			'idSocio' => 'required|int',
			'cantNros' => 'required|int',
			'numeroTerminacion' => 'int',
			'valorNumero' => 'required|numeric',
			'mesDesde' => 'required|int',
			'mesHasta' => 'required|int'
		]);

		$idSocio = $request->input('idSocio');
		$valorNumero = $request->input('valorNumero');
		$mesDesde = $request->input('mesDesde');
		$mesHasta = $request->input('mesHasta');
		$cantNros = $request->input('cantNros');
		$numeroTerminacion = $request->input('numeroTerminacion') ?: null;
		$numeros = []; // Al no suministrar ninguno, seran generados automaticamente

		$resOk = FALSE;
		try {
			$resOk = $this->planDeAbonoService->guardarNuevo($idSocio, $valorNumero,
		  						$mesDesde, $mesHasta, $numeros, $cantNros, $numeroTerminacion);
		} catch (Exception $e) {
			//echo $e->getMessage();
		}

		$viewData = [
			"success" => $resOk,
			"message" => $resOk? "El registro se cargó con éxito." : "No se pudo cargar el registro.",
			"btn_href" => route('planes_abono/crear_plan')
		];

		// La vista "parcial" se guarda en variable.
		$view = view('messages.success_view', $viewData);

		// Salida del conjunto al browser.
		return view('main_layout', ["title" => "Nuevo plan socio", "view" => $view]);
	}

	public function crearPlanManual(Request $request)
    {
		$viewData = [
            'editing' => FALSE
        ];

        // La vista "parcial" se guarda en variable.
        $view = view('plan_abono.create2', $viewData);

        // Salida del conjunto al browser.
        return view('main_layout', ["title" => "Nuevo plan manual", "view" => $view]);
    }

	public function grabarPlanManual(Request $request)
	{
		$this->validate($request, [
			'idSocio' => 'required|int',
			'valorNumero' => 'required|numeric',
			'mesDesde' => 'required|int',
			'mesHasta' => 'required|int'
		]);

		$idSocio = $request->input('idSocio');
		$valorNumero = $request->input('valorNumero');
		$mesDesde = $request->input('mesDesde');
		$mesHasta = $request->input('mesHasta');

		// Conservamos solo aquellos inputs que no esten vacios.
		$numeros = array_values(array_filter($request->input('numeros'), function($val) {
			return $val !== '';
		}));

		$resOk = FALSE;
		$details = '';
		try {
			$resOk = $this->planDeAbonoService->guardarNuevo($idSocio, $valorNumero,
		  						$mesDesde, $mesHasta, $numeros);
		} catch (\Exception $e) {
			$details = $e->getMessage();
		}

		$viewData = [
			"success" => $resOk,
			"message" => $resOk? "El registro se cargó con éxito." : "No se pudo cargar el registro.",
			"details" => $details,
			"btn_href" => route('planes_abono/crear_plan_manual')
		];

		// La vista "parcial" se guarda en variable.
		$view = view('messages.success_view', $viewData);

		// Salida del conjunto al browser.
		return view('main_layout', ["title" => "Nuevo plan socio", "view" => $view]);
	}

}
