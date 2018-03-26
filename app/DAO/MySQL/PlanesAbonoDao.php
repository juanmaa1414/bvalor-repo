<?php
namespace App\DAO\MySQL;

use App\Models\PlanDeAbono;
use Illuminate\Support\Facades\DB;

/**
 * Description of Dao
 * TODO: Asignar un interface para garantizar esten ok futuras implementaciones
 * del mismo dao "concreto" pero con otro motor de bd.
 *
 * @author Juan M. Fernandez
 */
class PlanesAbonoDao
{
	protected $campaniasDao;
	protected $sociosDao;

	public function __construct(CampaniasDao $campaniasDao, SociosDao $sociosDao)
	{
		$this->campaniasDao = $campaniasDao;
		$this->sociosDao = $sociosDao;
	}

	/**
	 * Devuelve todos los registros, excepto los eliminados.
	 * TODO: Enlazar parametros en lugar de concatenar.
	 */
	public function search($params = [])
	{
		$whereSQL = "";

		if (isset($params["id"])) {
            $whereSQL .= " AND pl_id = {$params["id"]} ";
        }

        if (isset($params["id_socio"])) {
            $whereSQL .= " AND pl_id_socio = '{$params["id_socio"]}'";
        }

		$sql = "SELECT
					pl_id,
					pl_numero_plan,
                    pl_id_campania,
					pl_id_socio,
                    pl_timestamp_alta,
                    pl_valor_numero,
                    pl_mes_desde,
                    pl_mes_hasta,
                    pl_es_dado_baja
				FROM planes_abono
				WHERE pl_borrado = 0 {$whereSQL}
				ORDER BY pl_id DESC";

        $sqlNumeros = "SELECT pln_numero FROM planes_abono_numeros WHERE pln_id_plan_abono = :idPlan";

		$result = DB::select($sql);

		// Mapeo: se crean los modelos con sus propiedades conforme lo obtenido.
		$arrayof = [];
		if (count($result) >= 1) {
			foreach ($result as $row) {
				$campania = $this->campaniasDao->findById($row->pl_id_campania);
				$socio = $this->sociosDao->findById($row->pl_id_socio);
				$plan = new PlanDeAbono($row->pl_id, $row->pl_numero_plan, $campania, $socio,
                                    $row->pl_valor_numero, $row->pl_mes_desde, $row->pl_mes_hasta);

                // Traer los numeros del plan y asignarlos.
                $resNum = DB::select($sqlNumeros, [":idPlan" => $row->pl_id]);
                $numeros = [];
                foreach ($resNum as $rnum) {
                    $numeros[] = $rnum->pln_numero;
                }

                $plan->setNumeros($numeros);
                $arrayof[] = $plan;
			}
		}

		return $arrayof;
	}

	public function findById($id)
	{
		$params = ["id" => $id];
		$results = $this->search($params);

		$item = false;
		if (count($results) == 1) {
			$item = array_shift($results);
		}

		return $item;
	}

    /**
     * Registra en la bbdd el objeto proporcionado.
     * Si tiene un Id, lo actualiza, sino, lo carga como nuevo y le asigna uno.
	 *
     * @param PlanDeAbono $plan
     * @return boolean
     */
	public function save(PlanDeAbono $plan)
	{
		if ($plan->getId() === null) {
            return $this->insert($plan);
        }
        return $this->update($plan);
	}

	// TODO: implementar un transaction
	private function insert(PlanDeAbono $plan)
	{
		$params = [
			":numeroPlan" => $this->getNextNumber(),
			":idCampania" => $plan->getCampania()->getId(),
            ":idSocio" => $plan->getSocio()->getId(),
            ":timestampAlta" => $plan->getTimestampAlta(),
            ":valorNum" => $plan->getValorNumero(),
            ":mesDesde" => $plan->getMesDesde(),
            ":mesHasta" => $plan->getMesHasta(),
            ":esDadoBaja" => $plan->getEsDadoBaja()
		];

		$sql = "INSERT INTO planes_abono (
					pl_id,
					pl_numero_plan,
					pl_id_campania,
                    pl_id_socio,
                    pl_timestamp_alta,
                    pl_valor_numero,
                    pl_mes_desde,
                    pl_mes_hasta,
                    pl_es_dado_baja,
                    pl_borrado
				)
				VALUES (
					NULL,
					:numeroPlan,
					:idCampania,
                    :idSocio,
                    :timestampAlta,
                    :valorNum,
                    :mesDesde,
                    :mesHasta,
                    :esDadoBaja,
                    0
				)";

        $result = DB::insert($sql, $params);

        $idPlan = DB::getPdo()->lastInsertId();

        // Si hay numeros que almacenar
        $numeros = $plan->getNumeros();
        if (count($numeros) >= 1) {
            $nparams = [];
            foreach ($numeros as $num) {
                $nparams[] = ['pln_id_plan_abono' => $idPlan, 'pln_numero' => $num];
            }

            DB::table('planes_abono_numeros')->insert($nparams);
        }

		return $result;
	}

	/**
	 * Encuentra numeros para sorteo disponibles
	 */
    public function findAvailNumbers($maxLimit, $quantToGenerate, $idCampania)
    {
        $pdo = DB::getPdo();

		// Entendemos que tambien se pueden tomar numeros que
		// pertenecian a planes dados de baja. La baja es definitiva,
		// solo permite consultas.
        $stm = $pdo->prepare("SELECT COUNT(pln_numero) AS used
                            FROM planes_abono_numeros
                            INNER JOIN planes_abono ON pl_id = pln_id_plan_abono
                            WHERE pl_id_campania = {$idCampania} AND pl_es_dado_baja = 0 AND pln_numero = :num");
        $numDisponibles = [];

        // Recorrer de uno (1) hasta el limite permitido para encontrar disponibles.
        for ($i=1; $i<=$maxLimit; $i++) {
            $stm->bindParam(':num', $i, \PDO::PARAM_INT);
            $stm->execute();
            $res = $stm->fetch(\PDO::FETCH_ASSOC);

            // Si el numero aun no se uso para la campaña consultada.
            if ($res['used'] == '0') {
                $numDisponibles[] = $i;
            }

            // Si conseguimos lo requerido antes de terminar
            if (count($numDisponibles) == $quantToGenerate) {
                return $numDisponibles;
            }
        }

        return $numDisponibles;
    }

	/**
	 * Comprueba que un dado numero para sorteo se encuentre disponible
	 */
    public function numIsAvailable($num, $idCampania)
    {
		// Entendemos que tambien se pueden tomar numeros que
		// pertenecian a planes dados de baja. La baja es definitiva,
		// solo permite consultas.
        $sql = "SELECT COUNT(pln_numero) AS used
                FROM planes_abono_numeros
                INNER JOIN planes_abono ON pl_id = pln_id_plan_abono
                WHERE pl_id_campania = {$idCampania} AND pl_es_dado_baja = 0 AND pln_numero = {$num}";

        $res = DB::select($sql);
		$res = $res[0];

        if ($res->used == '0') {
            return TRUE;
        }
        return FALSE;
    }

	/**
	 * Encuentra el proximo numero de socio a asignar.
	 */
	private function getNextNumber()
	{
		$sql = "SELECT IFNULL(MAX(pl_numero_plan), 0) AS lastnum FROM planes_abono";
        $res = DB::select($sql)[0];

		return $res->lastnum + 1;
	}

}
