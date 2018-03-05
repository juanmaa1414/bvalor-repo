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
class PlanesDeAbonoDao
{
    
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
                    pl_id_campania,
					pl_id_socio,
                    pl_timestamp_alta,
                    pl_valor_numero,
                    pl_mes_desde,
                    pl_mes_hasta,
                    pl_es_dado_baja
				FROM planes_abono
				WHERE pl_borrado = 0 {$whereSQL}";
                
        $sqlNumeros = "SELECT pln_numero FROM plan_numeros_sorteo WHERE pln_id_plan_pago = :idPlan";
				
		$result = DB::select($sql);
		
		// Mapeo: se crean los modelos con sus propiedades conforme lo obtenido.
		$arrayof = [];
		if (count($result) >= 1) {
			foreach ($result as $row) {
				$plan = new PlanDeAbono($row->pl_id, $row->pl_id_campania, $row->pl_id_socio,
                                    $row->pl_valor_numero, $row->pl_mes_desde, $row->pl_mes_hasta);
                
                // Traer los numeros de carton.
                $resNum = DB::select($sqlNumeros, [":idCar" => $row->pl_id]);
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
    
    public function findAvailNumbers($num, $maxLimit, $quantToGenerate, $idCampania)
    {
        $pdo = DB::getPdo();
        
        $stm = $pdo->prepare("SELECT COUNT(carn_numero) AS used
                            FROM cartones_numeros
                            INNER JOIN cartones ON car_id = carn_id_carton
                            WHERE car_id_campania = {$idCampania} AND carn_numero = :num");
        $numDisponibles = [];
        
        // Recorrer de uno (1) hasta el limite permitido para encontrar disponibles.
        for ($i=1;$i<=$maxLimit;$i++) {
            $stm->bindParam(':num', $i, \PDO::PARAM_INT);
            $stm->execute();
            $res = $stm->fetch(\PDO::FETCH_ASSOC);
            
            // Si el numero se encuentra disponible para la campaña consultada.
            if ($res['used'] == '0') {
                $numDisponibles[] = $i;
            }

            // Si conseguimos lo requerido antes de terminar
            if (count($disponible) == $quantToGenerate) {
                return $numDisponibles;
            }
        }
        
        return $numDisponibles;
    }
    
    public function numIsAvailable($num, $idCampania)
    {
        $pdo = DB::getPdo();
        
        $sql = "SELECT COUNT(carn_numero) AS used
                FROM cartones_numeros
                INNER JOIN cartones ON car_id = carn_id_carton
                WHERE car_id_campania = {$idCampania} AND carn_numero = {$num}";
                
        $res = DB::select($sql)->first();
        
        if ($res->used == '0') {
            return TRUE;
        }
        return FALSE;
    }

    // TODO: implementar un transaction
	private function insert(PlanDeAbono $plan)
	{
		$params = [
			":idCampania" => $plan->getCampania()->getId(),
            ":idSocio" => $plan->getSocio()->getId(),
            ":timestampAlta" => $plan->getTimestampAlta(),
            ":valorNum" => $plan->getValorNumero(),
            ":mesDesde" => $plan->getMesDesde(),
            ":mesHasta" => $plan->getMesHasta(),
            ":esDadoBaja" => $plan->getCodTipoDocUnico()
		];
		
		$sql = "INSERT INTO planes_abono (
					pl_id,
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
                $nparams[] = ['carn_id_carton' => $idCarton, 'carn_numero' => $num];
            }
            
            DB::table('cartones_numeros')->insert($nparams);
        }
		
		return $result;
	}
	
	private function update(PlanDeAbono $plan)
	{
		$params = [
			":id" => $plan->getId(),
			":nombre" => $plan->getNombreCompleto(),
            ":domic" => $plan->getDomicilio(),
            ":tel" => $plan->getTelefono(),
            ":codTipoDoc" => $plan->getCodTipoDocUnico(),
            ":numeroDoc" => $plan->getNumeroDocUnico()
		];
		
		$sql = "UPDATE planes_abono
				SET
                    vend_nombre_completo = :nombre,
                    vend_domicilio = :domic,
                    vend_telefono = :tel,
                    vend_cod_tipo_doc_unico = :codTipoDoc,
                    vend_numero_doc = :numeroDoc
				WHERE pl_id = :id";
		
		$affected = DB::update($sql, $params);
		
		return ($affected == 1);
	}
}
