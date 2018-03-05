<?php

namespace App\Models;

/**
 * Description of class
 *
 * @author Juan M. Fernandez
 */
class Campania {
	protected $id;
	protected $nombre;
    protected $valorNumero;

    /**
     * Al instanciar aseguramos siempre al objeto con sus propiedades
     * esenciales. El resto se podran completar por "setValorACompletar()". 
     */
    public function __construct($id = null, $nombre, $valorNumero)
    {
		$this->id = $id;
		$this->nombre = $nombre;
        $this->valorNumero = $valorNumero;
	}
    
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getValorNumero() {
        return $this->valorNumero;
    }

    //public function setId($id) {
    //    $this->id = $id;
    //}

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setValorNumero($valorNumero) {
        $this->valorNumero = $valorNumero;
    }

}
