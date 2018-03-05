<?php
namespace App\DAO\MySQL;

use App\Models\Vendedor;
use Illuminate\Support\Facades\DB;

/**
 * Description of Dao
 * TODO: Asignar un interface para garantizar esten ok futuras implementaciones
 * del mismo dao "concreto" pero con otro motor de bd.
 *
 * @author Juan M. Fernandez
 */
class VendedoresDao
{
	
	/**
	 * Devuelve todos los registros, excepto los eliminados.
	 * TODO: Enlazar parametros en lugar de concatenar.
	 */
	public function search($params = [])
	{
		$whereSQL = "";
		
		if (isset($params["id"])) {
            $whereSQL .= " AND vend_id = {$params["id"]} ";
        }
        
        if (isset($params["nombre"])) {
            $whereSQL .= " AND vend_nombre_completo LIKE '%{$params["nombre"]}%'";
        }
        
        // Para busquedas de autocomplete.
        if (isset($params["nombre_dni"])) {
            // Shortcut...
            $search_group[] = "CONCAT(vend_nombre_completo, ' ', vend_numero_doc_unico) LIKE '%{$params["nombre_dni"]}%' ";
        }
		
		$sql = "SELECT
					vend_id,
					vend_nombre_completo,
                    vend_domicilio,
                    vend_telefono,
                    vend_cod_tipo_doc_unico,
                    vend_numero_doc_unico
				FROM vendedores
				WHERE vend_borrado = 0 {$whereSQL}";
				
		$result = DB::select($sql);
		
		// Mapeo: se crean los modelos con sus propiedades conforme lo obtenido.
		$arrayof = [];
		if (count($result) >= 1) {
			foreach ($result as $row) {
				$arrayof[] = new Vendedor($row->vend_id, $row->vend_nombre_completo, $row->vend_cod_tipo_doc_unico,
                                $row->vend_numero_doc_unico, $row->vend_domicilio, $row->vend_telefono);
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
     * @param Autor $vendedor
     * @return boolean
     */
	public function save(Vendedor $vendedor)
	{
		if ($vendedor->getId() === null) {
            return $this->insert($vendedor);
        }
        return $this->update($vendedor);
	}
	
	private function insert(Vendedor $vendedor)
	{
		$params = [
			":nombre" => $vendedor->getNombreCompleto(),
            ":domic" => $vendedor->getDomicilio(),
            ":tel" => $vendedor->getTelefono(),
            ":codTipoDoc" => $vendedor->getCodTipoDocUnico(),
            ":numeroDoc" => $vendedor->getNumeroDocUnico()
		];
		
		$sql = "INSERT INTO vendedores (
					vend_id,
					vend_nombre_completo,
                    vend_domicilio,
                    vend_telefono,
                    vend_cod_tipo_doc_unico,
                    vend_numero_doc,
                    vend_borrado
				)
				VALUES (
					NULL,
					:nombre,
                    :domic,
                    :tel,
                    :codTipoDoc,
                    :numeroDoc,
                    0
				)";
		
		$result = DB::insert($sql, $params);
		return $result;
	}
	
	private function update(Vendedor $vendedor)
	{
		$params = [
			":id" => $vendedor->getId(),
			":nombre" => $vendedor->getNombreCompleto(),
            ":domic" => $vendedor->getDomicilio(),
            ":tel" => $vendedor->getTelefono(),
            ":codTipoDoc" => $vendedor->getCodTipoDocUnico(),
            ":numeroDoc" => $vendedor->getNumeroDocUnico()
		];
		
		$sql = "UPDATE vendedores
				SET
                    vend_nombre_completo = :nombre,
                    vend_domicilio = :domic,
                    vend_telefono = :tel,
                    vend_cod_tipo_doc_unico = :codTipoDoc,
                    vend_numero_doc_unico = :numeroDoc
				WHERE vend_id = :id";
		
		$affected = DB::update($sql, $params);
		
		return ($affected == 1);
	}
}
