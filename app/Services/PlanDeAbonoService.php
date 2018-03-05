<?php
namespace App\Services;

use App\DAO\MySQL\PlanesDeAbonoDao;
use App\Models\PlanDeAbono;

/**
 * Description of PlanDeAbonoService
 */
class PlanDeAbonoService
{
    /**
     *
     * @var PlanesDeAbonoDao
     */
    protected $planesDeAbonoDao;
    
    public function __construct(PlanesDeAbonoDao $planesDeAbonoDao) {
        $this->planesDeAbonoDao = $planesDeAbonoDao;
    }
    
    /**
     * Genera un array de numeros libres de ser utilizados para
     * una determinada campaña.
     */
    public function generarNumeros($cantNumerosAGenerar, $numDeTerminacion, $idCampania)
    {
        // Teniendo en cuenta a 9999 como limite consensuado para
        // usar numeros
        $maxBusca = 9999;
        
        $numDisponibles = $this->planesDeAbonoDao->findAvailNumbers($i, $maxBusca, $cantNumerosAGenerar, $idCampania);
        
        return $numDisponibles;
    }
    
    /**
     * Devuelve true si todos los numeros del array proporcionado están
     * disponibles para la campaña pasada por id.
     */
    public function comprobarNumeros($numeros, $idCampania)
    {
        foreach ($numeros as $num) {
            $disponible = $this->planesDeAbonoDao->numIsAvailable($num, $idCampania);
            if ( ! $disponible) {
                return FALSE;
            }
        }
        
        return TRUE;
    }

    /**
     * Crea y almacena un nuevo Plan de abono acorde con la campaña actual/activa.
     */
    public function guardarNuevo($idSocio, array $numeros, $cantNumGenerar, 
                                $numDeTerminacion, $valorDeNumero, $cuotaDesde, $cuotaHasta)
    {
        $idCampania = 20; // TODO: traer.
        $planDeAbono = new PlanDeAbono(null, $idCampania, $idSocio, $valorDeNumero,
                                $cuotaDesde, $cuotaHasta);
        
        if (count($numeros) == 0) {
            $numeros = $this->generarNumeros($cantNumGenerar, $numDeTerminacion, $idCampania);
            
        } elseif (count($numeros) >= 1) {
            $numerosOk = $this->comprobarNumeros($numeros, $idCampania);
            if ( ! $numerosOk) {
                throw new Exception('No disponible uno o mas numeros de entre los proporcionados.');
            }
        }
        
        $planDeAbono->setNumeros($numeros);
        
        // No devolvemos nada, porque si falla, sabremos por una Exception.
        $this->planesDeAbonoDao->save($planDeAbono);
    }

}
