<?php
namespace App\Models;

use App\Models\Campania;

/**
 * Plan de pago, cargado en el sistema para tener info
 * de como se liquidarán las cuotas de un socio en una determinada campaña.
 *
 * @author Juan M. Fernandez
 */
class PlanDeAbono {
	protected $id;
	protected $idCampania;
	protected $idSocio;
    protected $timestampAlta;
    protected $valorNumero;
    protected $mesDesde; // 1 al 12
    protected $mesHasta; // idem
    protected $numeros;
    protected $esDadoBaja;

    /**
     * Al instanciar aseguramos siempre al objeto con sus propiedades
     * esenciales. El resto se podran completar por "setValorACompletar()". 
     */
    public function __construct($id = null, $idCampania, $idSocio, $valorNumero,
                                $mesDesde, $mesHasta)
    {
		$this->id = $id;
		$this->idCampania = $idCampania;
        $this->idSocio = $idSocio;
        $this->valorNumero = $valorNumero;
        $this->mesDesde = $mesDesde;
        $this->mesHasta = $mesHasta;
        $this->numeros = [];
	}
    
    public function getId() {
        return $this->id;
    }

    public function getIdCampania() {
        return $this->idCampania;
    }

    public function getIdSocio() {
        return $this->idSocio;
    }

    public function getTimestampAlta() {
        return $this->timestampAlta;
    }

    public function getValorNumero() {
        return $this->valorNumero;
    }

    public function getMesDesde() {
        return $this->mesDesde;
    }

    public function getMesHasta() {
        return $this->mesHasta;
    }

    public function getNumeros() {
        return $this->numeros;
    }

    public function getEsDadoBaja() {
        return $this->esDadoBaja;
    }

    //public function setId($id) {
    //    $this->id = $id;
    //}

    public function setIdCampania($idCampania) {
        $this->idCampania = $idCampania;
    }

    public function setIdSocio($idSocio) {
        $this->idSocio = $idSocio;
    }

    public function setTimestampAlta($timestampAlta) {
        $this->timestampAlta = $timestampAlta;
    }

    public function setValorNumero($valorNumero) {
        $this->valorNumero = $valorNumero;
    }

    public function setMesDesde($mesDesde) {
        $this->mesDesde = $mesDesde;
    }

    public function setMesHasta($mesHasta) {
        $this->mesHasta = $mesHasta;
    }

    public function setNumeros(array $numeros) {
        $this->numeros = $numeros;
    }

    public function setEsDadoBaja($esDadoBaja) {
        $this->esDadoBaja = $esDadoBaja;
    }

}
