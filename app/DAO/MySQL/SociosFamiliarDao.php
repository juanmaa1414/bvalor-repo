<?php
namespace App\DAO\MySQL;

use App\Models\SocioFamiliar;
use Illuminate\Support\Facades\DB;

/**
 * Description of Dao
 *
 * @author Juan M. Fernandez
 */
class SociosFamiliarDao
{

	/**
	 * Devuelve todos los registros, excepto los eliminados.
	 * TODO: Enlazar parametros en lugar de concatenar.
	 */
	public function search($params = [])
	{
		$whereSQL = "";

		if (isset($params["id"])) {
            $whereSQL .= " AND socf_id = {$params["id"]} ";
        }

        if (isset($params["id_socio_jefe_familia"])) {
            $whereSQL .= " AND socf_id_socio_jefe_familia = {$params["id_socio_jefe_familia"]} ";
        }

		$sql = "SELECT
					socf_id,
                    socf_id_socio_jefe_familia,
                    socf_cod_tipo_parentesco,
					socf_nombre_completo,
                    socf_cod_tipo_doc_unico,
                    socf_numero_doc_unico,
                    socf_fecha_nacimiento
				FROM socios_familiares
				WHERE socf_borrado = 0 {$whereSQL}";

		$result = DB::select($sql);

		// Mapeo: se crean los modelos con sus propiedades conforme lo obtenido.
		$arrayof = [];
		if (count($result) >= 1) {
			foreach ($result as $row) {
				$sociof = new SocioFamiliar($row->socf_id, $row->socf_id_socio_jefe_familia, $row->socf_cod_tipo_parentesco,
                                $row->socf_nombre_completo, $row->socf_cod_tipo_doc_unico, $row->socf_numero_doc_unico,
                                $row->socf_fecha_nacimiento);

                $arrayof[] = $sociof;
			}
		}

		return $arrayof;
	}

    /**
     * Busca y devuelve un socio por su ID.
     * @param int $id
     * @return Socio
     */
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
	public function save(SocioFamiliar $sociof)
	{
		if ($sociof->getId() === null) {
            return $this->insert($sociof);
        }
        return $this->update($sociof);
	}

	private function insert(SocioFamiliar $sociof)
	{
        $params = [
            ":idSocioJefeFamilia" => $sociof->getIdSocioJefeFamilia(),
            ":codTipoParentesco" => $sociof->getCodTipoParentesco(),
            ":nombreCompleto" => $sociof->getNombreCompleto(),
            ":codTipoDocUnico" => $sociof->getCodTipoDocUnico(),
            ":numeroDocUnico" => $sociof->getNumeroDocUnico(),
            ":fechaNacimiento" => $sociof->getFechaNacimiento()
		];

		$sql = "INSERT INTO socios_familiares (
					socf_id,
                    socf_id_socio_jefe_familia,
                    socf_cod_tipo_parentesco,
					socf_nombre_completo,
                    socf_cod_tipo_doc_unico,
                    socf_numero_doc_unico,
                    socf_fecha_nacimiento,
                    socf_borrado
				)
				VALUES (
					NULL,
                    :idSocioJefeFamilia,
                    :codTipoParentesco,
					:nombreCompleto,
                    :codTipoDocUnico,
                    :numeroDocUnico,
                    :fechaNacimiento,
                    0
				)";

		$result = DB::insert($sql, $params);
		return $result;
	}

	private function update(SocioFamiliar $sociof)
	{
		$params = [
			":id" => $sociof->getId(),
            ":idSocioJefeFamilia" => $sociof->getIdSocioJefeFamilia(),
            ":codTipoParentesco" => $sociof->getCodTipoParentesco(),
            ":nombreCompleto" => $sociof->getNombreCompleto(),
            ":codTipoDocUnico" => $sociof->getCodTipoDocUnico(),
            ":numeroDocUnico" => $sociof->getNumeroDocUnico(),
            ":fechaNacimiento" => $sociof->getFechaNacimiento()
		];

		$sql = "UPDATE socios_familiares
				SET
                    socf_id_socio_jefe_familia = :idSocioJefeFamilia,
                    socf_cod_tipo_parentesco = :codTipoParentesco,
					socf_nombre_completo = :nombreCompleto,
                    socf_cod_tipo_doc_unico = :codTipoDocUnico,
                    socf_numero_doc_unico = :numeroDocUnico,
                    socf_fecha_nacimiento = :fechaNacimiento
				WHERE socf_id = :id";

		$affected = DB::update($sql, $params);

        return ($affected == 1);
	}
}
