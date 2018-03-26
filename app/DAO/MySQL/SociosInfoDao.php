<?php
namespace App\DAO\MySQL;

use App\Models\Socio;
use App\Models\Vendedor;
use App\Models\SocioInfo;
use Illuminate\Support\Facades\DB;

/**
 * ### DEPRECADO / no uso ###
 * 
 * Description of Dao
 * TODO: Asignar un interface para garantizar esten ok futuras implementaciones
 * del mismo dao "concreto" pero con otro motor de bd.
 *
 * @author Juan M. Fernandez
 */
class SociosInfoDao
{
    /**
     *
     * @var SociosDao 
     */
    protected $sociosDao;
    
    /**
     *
     * @var VendedoresDao 
     */
    protected $vendedoresDao;

    public function __construct(SociosDao $sociosDao, VendedoresDao $vendedoresDao) {
        $this->sociosDao = $sociosDao;
        $this->vendedoresDao = $vendedoresDao;
    }
    
    public function search($params = [])
	{
        $sociosInfoArr = [];
		$res = $this->sociosDao->search($params);
        
        foreach ($res as $soc) {
            $socioInfo = new SocioInfo($soc);

            // Busca lo que compone a la clase socioinfo.
            $vend = $this->vendedoresDao->findById($soc->getIdVendedor());
            $socRefer = $this->sociosDao->findById($soc->getIdSocioReferente());

            // Si tiene/existen, asignar.
            if ($vend) {
                $socioInfo->setVendedor($vend);
            }
            if ($socRefer) {
                $socioInfo->setSocioReferente($socRefer);
            }

            $sociosInfoArr[] = $socioInfo;
        }
        
        return $sociosInfoArr;
	}
	
	public function findBySocioId($id)
	{
		$params = ["id" => $id];
		$results = $this->search($params);
		
		$item = false;
		if (count($results) == 1) {
			$item = array_shift($results);
		}
		
		return $item;
	}
    
}
