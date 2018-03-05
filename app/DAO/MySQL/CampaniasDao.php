<?php
namespace App\DAO\MySQL;

use App\Models\Campania;
use Illuminate\Support\Facades\DB;

/**
 * Description of Dao
 *
 * @author Juan M. Fernandez
 */
class CampaniasDao
{
	
	/**
	 * Devuelve todos los registros, excepto los eliminados.
	 * TODO: Enlazar parametros en lugar de concatenar.
	 */
	public function search($params = [])
	{
		$whereSQL = "";
		
		if (isset($params["id"])) {
            $whereSQL .= " AND cam_id = {$params["id"]} ";
        }
        
        if (isset($params["nombre"])) {
            $whereSQL .= " AND nombre LIKE '%{$params["nombre"]}%'";
        }
		
		$sql = "SELECT
					cam_id,
					cam_nombre,
                    cam_valor_numero
				FROM campanias
				WHERE camp_borrado = 0 {$whereSQL}";
				
		$result = DB::select($sql);
		
		// Mapeo: se crean los modelos con sus propiedades conforme lo obtenido.
		$arrayof = [];
		if (count($result) >= 1) {
			foreach ($result as $row) {
				$arrayof[] = new Campania($row->cam_id, $row->cam_nombre, $row->cam_valor_numero);
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
     * @param Campania $campania
     * @return boolean
     */
	public function save(Vendedor $campania)
	{
		if ($campania->getId() === null) {
            return $this->insert($campania);
        }
        return $this->update($campania);
	}
	
	private function insert(Campania $campania)
	{
		$params = [
			":nombre" => $campania->getNombre(),
            ":valorNum" => $campania->getValorNumero()
		];
		
		$sql = "INSERT INTO campanias (
					cam_id,
					cam_nombre,
                    cam_valor_numero,
                    cam_borrado
				)
				VALUES (
					NULL,
					:nombre,
                    :valorNum,
                    0
				)";
		
		$result = DB::insert($sql, $params);
		return $result;
	}
	
	private function update(Campania $campania)
	{
		$params = [
			":id" => $campania->getId(),
			":nombre" => $campania->getNombre(),
            ":valorNum" => $campania->getValorNumero()
		];
		
		$sql = "UPDATE campanias
				SET
                    cam_nombre = :nombre,
                    cam_valor_numero = :valorNum
				WHERE cam_id = :id";
		
		$affected = DB::update($sql, $params);
		
		return ($affected == 1);
	}
}
