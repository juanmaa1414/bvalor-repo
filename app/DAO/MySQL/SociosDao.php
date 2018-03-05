<?php
namespace App\DAO\MySQL;

use App\Models\Socio;
use Illuminate\Support\Facades\DB;
use App\DAO\ISociosDao;

/**
 * Description of Dao
 *
 * @author Juan M. Fernandez
 */
class SociosDao implements ISociosDao
{
	
	/**
	 * Devuelve todos los registros, excepto los eliminados.
	 * TODO: Enlazar parametros en lugar de concatenar.
	 */
	public function search($params = [])
	{
		$whereSQL = "";
		
		if (isset($params["id"])) {
            $whereSQL .= " AND soc_id = {$params["id"]} ";
        }
        
        if (isset($params["nombre"])) {
            $whereSQL .= " AND soc_nombre_completo LIKE '%{$params["nombre"]}%' ";
        }
        
        // Para busquedas de autocomplete.
        if (isset($params["nombre_dni"])) {
            // Shortcut...
            $whereSQL .= "CONCAT(soc_nombre_completo, ' ', soc_numero_doc_unico) LIKE '%{$params["nombre_dni"]}%' ";
        }
		
		$sql = "SELECT
					soc_id,
					soc_nombre_completo,
                    soc_cod_tipo_doc_unico,
                    soc_numero_doc_unico,
                    soc_domicilio,
                    soc_telefono,
                    soc_id_vendedor,
                    soc_cod_tipo_persona,
                    soc_id_socio_referente,
                    soc_es_activo
				FROM socios
				WHERE soc_borrado = 0 {$whereSQL}";
				
		$result = DB::select($sql);
		
		// Mapeo: se crean los modelos con sus propiedades conforme lo obtenido.
		$arrayof = [];
		if (count($result) >= 1) {
			foreach ($result as $row) {
				$socio = new Socio($row->soc_id, $row->soc_nombre_completo, $row->soc_cod_tipo_doc_unico,
                                    $row->soc_numero_doc_unico, $row->soc_domicilio, $row->soc_telefono,
                                    $row->soc_cod_tipo_persona, $row->soc_es_activo);
                
                $socio->setIdVendedor($row->soc_id_vendedor);
                $socio->setIdSocioReferente($row->soc_id_socio_referente);
                
                $arrayof[] = $socio;
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
     * @param Socio $socio
     * @return boolean
     */
	public function save(Socio $socio)
	{
		if ($socio->getId() === null) {
            return $this->insert($socio);
        }
        return $this->update($socio);
	}
	
	private function insert(Socio $socio)
	{   
        $params = [
            ":nombre_completo" => $socio->getNombreCompleto(),
            ":cod_tipo_doc_unico" => $socio->getCodTipoDocUnico(),
            ":numero_doc_unico" => $socio->getNumeroDocUnico(),
            ":domicilio" => $socio->getDomicilio(),
            ":telefono" => $socio->getTelefono(),
            ":id_vendedor" => $socio->getIdVendedor(),
            ":cod_tipo_persona" => $socio->getCodTipoPersona(),
            ":id_socio_referente" => $socio->getIdSocioReferente(),
            ":es_activo" => $socio->getEsActivo()
		];
		
		$sql = "INSERT INTO socios (
					soc_id,
					soc_nombre_completo,
                    soc_cod_tipo_doc_unico,
                    soc_numero_doc_unico,
                    soc_domicilio,
                    soc_telefono,
                    soc_id_vendedor,
                    soc_cod_tipo_persona,
                    soc_id_socio_referente,
                    soc_es_activo,
                    soc_borrado
				)
				VALUES (
					NULL,
					:nombre_completo,
                    :cod_tipo_doc_unico,
                    :numero_doc_unico,
                    :domicilio,
                    :telefono,
                    :id_vendedor,
                    :cod_tipo_persona,
                    :id_socio_referente,
                    :es_activo,
                    0
				)";
		
		$result = DB::insert($sql, $params);
		return $result;
	}
	
	private function update(Socio $socio)
	{
		$params = [
			":id" => $socio->getId(),
            ":nombre_completo" => $socio->getNombreCompleto(),
            ":cod_tipo_doc_unico" => $socio->getCodTipoDocUnico(),
            ":numero_doc_unico" => $socio->getNumeroDocUnico(),
            ":domicilio" => $socio->getDomicilio(),
            ":telefono" => $socio->getTelefono(),
            ":id_vendedor" => $socio->getIdVendedor(),
            ":cod_tipo_persona" => $socio->getCodTipoPersona(),
            ":id_socio_referente" => $socio->getIdSocioReferente(),
            ":es_activo" => $socio->getEsActivo()
		];
		
		$sql = "UPDATE socios
				SET
                    soc_nombre_completo = :nombre_completo,
                    soc_cod_tipo_doc_unico = :cod_tipo_doc_unico,
                    soc_numero_doc_unico = :numero_doc_unico,
                    soc_domicilio = :domicilio,
                    soc_telefono = :telefono,
                    soc_id_vendedor = :id_vendedor,
                    soc_cod_tipo_persona = :cod_tipo_persona,
                    soc_id_socio_referente = :id_socio_referente,
                    soc_es_activo = :es_activo
				WHERE soc_id = :id";
		
		$affected = DB::update($sql, $params);
        
        return ($affected == 1);
	}
}
