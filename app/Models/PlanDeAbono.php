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
	protected $numeroPlan;

    /**
     * @var Campania
     */
	protected $campania;

    /**
     * @var Socio
     */
	protected $socio;

    protected $timestampAlta;
    protected $valorNumero; // precio
    protected $mesDesde; // 1 al 12
    protected $mesHasta; // idem
    protected $numeros;
    protected $esDadoBaja;

    /**
     * Al instanciar aseguramos siempre al objeto con sus propiedades
     * esenciales. El resto se podran completar por setters.
     */
    public function __construct($id = null, $numeroPlan = null, Campania $campania,
						Socio $socio, $valorNumero, $mesDesde, $mesHasta)
    {
		$this->id = $id;
		$this->numeroPlan = $numeroPlan;
		$this->campania = $campania;
        $this->socio = $socio;
        $this->valorNumero = $valorNumero;
        $this->mesDesde = $mesDesde;
        $this->mesHasta = $mesHasta;

		// Defaults
        $this->numeros = [];
		$this->timestampAlta = time();
		$this->esDadoBaja = FALSE;
	}

    public function getId()
	{
        return $this->id;
    }

	public function getNumeroPlan()
	{
        return $this->numeroPlan;
    }

    public function getCampania()
	{
        return $this->campania;
    }

    public function getSocio()
	{
        return $this->socio;
    }

    public function getTimestampAlta()
	{
        return $this->timestampAlta;
    }

    public function getValorNumero()
	{
        return $this->valorNumero;
    }

    public function getMesDesde()
	{
        return $this->mesDesde;
    }

    public function getMesHasta()
	{
        return $this->mesHasta;
    }

    public function getNumeros()
	{
        return $this->numeros;
    }

    public function getEsDadoBaja()
	{
        return $this->esDadoBaja;
    }

    //public function setId($id)
	//{
    //    $this->id = $id;
    //}

	public function setNumeroPlan($numeroPlan)
	{
        $this->numeroPlan = $numeroPlan;
    }

    public function setCampania($campania)
	{
        $this->campania = $campania;
    }

    public function setSocio($socio)
	{
        $this->socio = $socio;
    }

    public function setTimestampAlta($timestampAlta)
	{
        $this->timestampAlta = $timestampAlta;
    }

    public function setValorNumero($valorNumero)
	{
        $this->valorNumero = $valorNumero;
    }

    public function setMesDesde($mesDesde)
	{
        $this->mesDesde = $mesDesde;
    }

    public function setMesHasta($mesHasta)
	{
        $this->mesHasta = $mesHasta;
    }

    public function setNumeros(array $numeros)
	{
        $this->numeros = $numeros;
    }

    public function setEsDadoBaja($esDadoBaja)
	{
        $this->esDadoBaja = $esDadoBaja;
    }

	/**
	 * Calcula el total que se cobrará por éste plan de abono.
	 *
	 * @return float
	 */
	public function totalLiquida()
	{
		$cantMesesAbona = ($this->mesHasta - $this->mesDesde) + 1;
		$total = $this->valorNumero * $cantMesesAbona;
		return number_format($total, 2, '.', '');
	}

}
