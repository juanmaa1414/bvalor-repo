<?php

namespace App\Models;

/**
 * Description of Socio
 *
 * @author Juan M. Fernandez
 */
class Vendedor {
	protected $id;
	protected $nombreCompleto;
    protected $codTipoDocUnico;
    protected $numeroDocUnico;
    protected $domicilio;
    protected $telefono;

    /**
     * Al instanciar aseguramos siempre al objeto con sus propiedades
     * esenciales. El resto se podran completar por "setValorACompletar()". 
     */
    public function __construct($id = null, $nombreCompleto, $codTipoDocUnico,
                                $numeroDocUnico, $domicilio, $telefono)
    {
		$this->id = $id;
		$this->nombreCompleto = $nombreCompleto;
        $this->codTipoDocUnico = $codTipoDocUnico;
        $this->numeroDocUnico = $numeroDocUnico;
        $this->domicilio = $domicilio;
        $this->telefono = $telefono;
	}
    
    public function getId() {
        return $this->id;
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

    public function getDomicilio() {
        return $this->domicilio;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    //public function setId($id) {
    //    $this->id = $id;
    //}

    public function setNombreCompleto($nombreCompleto) {
        $this->nombreCompleto = $nombreCompleto;
    }

    public function setCodTipoDocUnico($codTipoDocUnico) {
        $this->codTipoDocUnico = $codTipoDocUnico;
    }

    public function setNumeroDocUnico($numeroDocUnico) {
        $this->numeroDocUnico = $numeroDocUnico;
    }

    public function setDomicilio($domicilio) {
        $this->domicilio = $domicilio;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

}
