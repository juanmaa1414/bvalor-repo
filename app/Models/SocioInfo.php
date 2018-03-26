<?php
namespace App\Models;

use App\Models\Socio;
use App\Models\Vendedor;

/**
 * ### DEPRECADO / NO USO ###
 * 
 * Socio junto con su vendedor y su socio referente si hubieran.
 * 
 * ## Clase de tipo asociación ##
 *
 * @author Juan M. Fernandez
 */
class SocioInfo {
    
    /**
     * @var Socio
     */
    protected $socio;
    
    /**
     * @var Vendedor
     */
    protected $vendedor;
    
    /**
     * @var Socio
     */
    protected $socioReferente;
    
    public function __construct(Socio $socio) {
        $this->setSocio($socio);
        $this->vendedor = null;
        $this->socioReferente = null;
    }

    public function getSocio() {
        return $this->socio;
    }

    public function getVendedor() {
        return $this->vendedor;
    }

    public function getSocioReferente() {
        return $this->socioReferente;
    }

    public function setSocio(Socio $socio) {
        $this->socio = $socio;
    }

    public function setVendedor(Vendedor $vendedor) {
        $this->vendedor = $vendedor;
    }

    public function setSocioReferente(Socio $socioReferente) {
        $this->socioReferente = $socioReferente;
    }

}
