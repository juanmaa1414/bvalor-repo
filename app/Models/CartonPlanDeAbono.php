<?php
namespace App\Models;

use App\Models\PlanDeAbono;
use App\Models\Socio;
use App\Models\Campania;

/**
 * Ficha impresa de info de cobro, llamada 'carton',
 * normalmente lo usaremos para mostrar datos.
 * 
 * ## Clase de tipo asociación ##
 *
 * @author Juan M. Fernandez
 */
class CartonPlanDeAbono
{
    
    /**
     * @var PlanDeAbono
     */
    protected $planDeAbono;
    
    /**
     * @var Socio
     */
    protected $socio;
    
    /**
     * @var Campania
     */
    protected $campania;
    
    public function __construct(PlanDeAbono $planDeAbono, Socio $socio, Campania $campania) {
        $this->setPlanDeAbono($planDeAbono);
        $this->setSocio($socio);
        $this->setCampania($campania);
    }
    
    public function getPlanDeAbono() {
        return $this->planDeAbono;
    }

    public function getSocio() {
        return $this->socio;
    }

    public function getCampania() {
        return $this->campania;
    }

    public function setPlanDeAbono(PlanDeAbono $planDeAbono) {
        $this->planDeAbono = $planDeAbono;
    }

    public function setSocio(Socio $socio) {
        $this->socio = $socio;
    }

    public function setCampania(Campania $campania) {
        $this->campania = $campania;
    }

}
