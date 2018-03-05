<?php
namespace App\Models;

/**
 * Description of SocioFamiliar
 *
 * @author Juan M. Fernandez
 */

// TODO: Metodo de fecha nac a human readable.

class SocioFamiliar {
	protected $id;
    
    /**
     * Id del socio jefe de familia.
     * @var int
     */
    protected $idSocio;
    
    protected $codTipoParentesco;
    protected $nombreCompleto;
    protected $codTipoDocUnico;
    protected $numeroDocUnico;
    protected $fechaNacimiento;

    /**
     * Al instanciar aseguramos siempre al objeto con sus propiedades
     * esenciales. El resto se podran completar por "setValorACompletar()". 
     */
    public function __construct($id = null, $idSocio, $codTipoParentesco, $nombreCompleto, 
                            $codTipoDocUnico, $numeroDocUnico, $fechaNacimiento) {
        $this->id = $id;
        $this->idSocio = $idSocio;
        $this->codTipoParentesco = $codTipoParentesco;
        $this->nombreCompleto = $nombreCompleto;
        $this->codTipoDocUnico = $codTipoDocUnico;
        $this->numeroDocUnico = $numeroDocUnico;
        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function getId() {
        return $this->id;
    }

    public function getIdSocio() {
        return $this->idSocio;
    }

    public function getCodTipoParentesco() {
        return $this->codTipoParentesco;
    }

    public function getNombreCompleto() {
        return $this->nombreCompleto;
    }

    public function getCodTipoDocUnico() {
        return $this->codTipoDocUnico;
    }

    public function getNumeroDocUnico() {
        return $this->numeroDocUnico;
    }

    public function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }

    //public function setId($id) {
    //    $this->id = $id;
    //}

    public function setIdSocio($idSocio) {
        $this->idSocio = $idSocio;
    }

    public function setCodTipoParentesco($codTipoParentesco) {
        $this->codTipoParentesco = $codTipoParentesco;
    }

    public function setNombreCompleto($nombreCompleto) {
        $this->nombreCompleto = $nombreCompleto;
    }

    public function setCodTipoDocUnico($codTipoDocUnico) {
        $this->codTipoDocUnico = $codTipoDocUnico;
    }

    public function setNumeroDocUnico($numeroDocUnico) {
        $this->numeroDocUnico = $numeroDocUnico;
    }

    public function setFechaNacimiento($fechaNacimiento) {
        $this->fechaNacimiento = $fechaNacimiento;
    }

}
