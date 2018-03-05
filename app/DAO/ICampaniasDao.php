<?php
namespace App\DAO;

/**
 *
 * @author Juan M. Fernandez
 */
interface ICampaniasDao
{
    public function search();
    public function findById();
    public function save();
}
