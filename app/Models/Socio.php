<?php
namespace App\Models;

/**
 * Description of Socio
 *
 * @author Juan M. Fernandez
 */
class Socio {
	protected $id;
    protected $numeroSocio;
    protected $nombreCompleto;
    protected $codTipoDocUnico;
    protected $numeroDocUnico;
    protected $domicilio;
    protected $telefono;
    protected $idVendedor;
    protected $codTipoPersona;
    protected $idSocioReferente;
    protected $esActivo;

    /**
     * Al instanciar aseguramos siempre al objeto con sus propiedades
     * esenciales. El resto se podran completar por "setValorACompletar()".
     */
    public function __construct($id = null, $numeroSocio = null, $nombreCompleto,
						$codTipoDocUnico, $numeroDocUnico, $domicilio, $telefono,
                        $codTipoPersona, $esActivo)
    {
		$this->id = $id;
		$this->numeroSocio = $numeroSocio;
		$this->nombreCompleto = $nombreCompleto;
        $this->codTipoDocUnico = $codTipoDocUnico;
        $this->numeroDocUnico = $numeroDocUnico;
        $this->domicilio = $domicilio;
        $this->telefono = $telefono;
        $this->idVendedor = 0;
        $this->codTipoPersona = $codTipoPersona;
        $this->idSocioReferente = 0;
        $this->esActivo = $esActivo;
	}

    public function getId()
	{
        return $this->id;
    }

	public function getNumeroSocio()
	{
        return $this->numeroSocio;
    }

    public function getNombreCompleto()
	{
        return $this->nombreCompleto;
    }

    public function getCodTipoDocUnico()
	{
        return $this->codTipoDocUnico;
    }

    public function getNumeroDocUnico()
	{
        return $this->numeroDocUnico;
    }

    public function getDomicilio()
	{
        return $this->domicilio;
    }

    public function getTelefono()
	{
        return $this->telefono;
    }

    public function getIdVendedor()
	{
        return $this->idVendedor;
    }

    public function getCodTipoPersona()
	{
        return $this->codTipoPersona;
    }

    public function getIdSocioReferente()
	{
        return $this->idSocioReferente;
    }

    public function getEsActivo()
	{
        return $this->esActivo;
    }

    //public function setId($id)
	//{
    //    $this->id = $id;
    //}

	public function setNumeroSocio($numeroSocio)
	{
        $this->numeroSocio = $numeroSocio;
    }

    public function setNombreCompleto($nombreCompleto)
	{
        $this->nombreCompleto = $nombreCompleto;
    }

    public function setCodTipoDocUnico($codTipoDocUnico)
	{
        $this->codTipoDocUnico = $codTipoDocUnico;
    }

    public function setNumeroDocUnico($numeroDocUnico)
	{
        $this->numeroDocUnico = $numeroDocUnico;
    }

    public function setDomicilio($domicilio)
	{
        $this->domicilio = $domicilio;
    }

    public function setTelefono($telefono)
	{
        $this->telefono = $telefono;
    }

    public function setIdVendedor($idVendedor)
	{
        $this->idVendedor = $idVendedor;
    }

    public function setCodTipoPersona($codTipoPersona)
	{
        $this->codTipoPersona = $codTipoPersona;
    }

    public function setIdSocioReferente($idSocioReferente)
	{
        $this->idSocioReferente = $idSocioReferente;
    }

    public function setEsActivo($esActivo)
	{
        $this->esActivo = $esActivo;
    }

}
