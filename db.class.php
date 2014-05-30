<?php
/**
 * DB
 *
 * @author mellowonpsx
 */
require_once "utils.php";

// remember: the phylosophy behind this project expect to have already control and excaped variables
class DB
{
    private $db;
    
    public function __construct()
    {
        $this->db = new mysqli(Config::$db_host, Config::$db_user, Config::$db_password, Config::$db_name) or die($this->db->error);
    }
    
    public function __destruct()
    {
        $this->close(); //die is in close() function
    }
    
    public function close()
    {
	$this->db->close() or die($this->db->error);
    }

    public function query($query, $returnFailure = FALSE)
    {
        if($returnFailure)
        {
            $result = mysqli_query($this->db, $query);
            return $result;
        }
	$result = mysqli_query($this->db, $query) or die($this->db->error);
	return $result;
    }
    
    public function lastId()
    {
        return mysqli_insert_id($this->db);
    }
}
