<?php
namespace App\Services;

use App\DAO\MySQL\PlanesAbonoDao;
use App\DAO\MySQL\CampaniasDao;
use App\DAO\MySQL\SociosDao;
use App\Models\PlanDeAbono;

/**
 * Description of PlanDeAbonoService
 */
class PlanDeAbonoService
{
    /**
     *
     * @var PlanesAbonoDao
     */
    protected $planesAbonoDao;

	/**
     *
     * @var CampaniasDao
     */
    protected $campaniasDao;

	/**
     *
     * @var SociosDao
     */
    protected $sociosDao;

    public function __construct(PlanesAbonoDao $planesAbonoDao, CampaniasDao $campaniasDao,
						SociosDao $sociosDao) {
        $this->planesAbonoDao = $planesAbonoDao;
		$this->campaniasDao = $campaniasDao;
		$this->sociosDao = $sociosDao;
    }

	// TODO: pasar valores de paginacion
	public function buscar()
	{
		return $this->planesAbonoDao->search();
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

		// TODO: tener en cuenta numero terminacion, if isset o algo asi
        $numDisponibles = $this->planesAbonoDao->findAvailNumbers($maxBusca, $cantNumerosAGenerar, $idCampania);

        return $numDisponibles;
    }

    /**
     * Devuelve true si todos los numeros del array proporcionado están
     * disponibles para la campaña pasada por id.
     */
    public function comprobarNumeros($numeros, $idCampania)
    {
        foreach ($numeros as $num) {
            $disponible = $this->planesAbonoDao->numIsAvailable($num, $idCampania);
            if ( ! $disponible) {
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * Crea y almacena un nuevo Plan de abono acorde con la campaña actual/activa.
	 *
	 * @param int 	$idSocio	 	Id del socio
	 * @param float	$valorNumero	Costo que tendran los numeros sorteo
	 * @param int	$mesDesde		Numero de mes desde el que se cobra
	 * @param int	$mesHasta		Numero de mes hasta el que se cobra
	 * @param array	$numeros		Los numeros de sorteo en caso suministremos
	 * @param int	$cantNumGenerar (opcional) Cantidad de numeros a generar en caso
	 * 								de no suministrarlos
	 * @param int	$numDeTerminacion (opcional) Ultimo/s num con los que se desea terminen
     */
    public function guardarNuevo($idSocio, $valorNumero, $mesDesde, $mesHasta, array $numeros = [],
							$cantNumGenerar = null, $numDeTerminacion = null)
    {
    	$idCampania = "4"; // TODO: traer.
        $campania = $this->campaniasDao->findById($idCampania);
        $socio = $this->sociosDao->findById($idSocio);

        $planDeAbono = new PlanDeAbono(null, null, $campania, $socio, $valorNumero,
                                $mesDesde, $mesHasta);

        // Resolvemos que pasa con los numeros.
		// Si no fueron suministrados
        if (count($numeros) == 0) {
			// Luego de hacer esto, no seria necesario llamar a comprobarNumeros.
            $numeros = $this->generarNumeros($cantNumGenerar, $numDeTerminacion, $idCampania);

		  // Si fueron suministrados, comprobarlos.
        } elseif (count($numeros) >= 1) {
            $numerosOk = $this->comprobarNumeros($numeros, $idCampania);
            if ( ! $numerosOk) {
                throw new \InvalidArgumentException('Uno o mas numeros de entre los proporcionados ya se encuentra en uso.');
            }
        }

        $planDeAbono->setNumeros($numeros);//printdie($planDeAbono);

        return $this->planesAbonoDao->save($planDeAbono);
    }

}
