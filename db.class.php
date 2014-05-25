<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of db
 *
 * @author mellowonpsx
 */
require_once "utils.php";

class DB
{
    private $db;
    
    public function __construct()
    {
        $this->db = new mysqli(Config::$db_host, Config::$db_user, Config::$db_password, Config::$db_name) or die($this->db->error);
    }
    
    public function __destruct()
    {
        $this->close();
    }
    
    public function close()
    {
	$this->db->close() or die($this->db->error);
    }

    public function query($query)
    {
	$result = mysqli_query($this->db, $query) or die($this->db->error);
	return $result;
    }
}

?>
