<?php
namespace App\DAO;

use App\Models\Socio;

/**
 *
 * @author Juan M. Fernandez
 */
interface ISociosDao
{
    public function search();
    public function findById($id);
    public function save(Socio $socio);
}
