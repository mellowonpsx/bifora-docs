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
        $this->db->set_charset("utf8"); // serve per json perché senno con la presenza di caratteri accentati esplode!
    }
    
    public function __destruct()
    {
        $this->close(); //die is in close() function
    }
    
    public function close()
    {
	$this->db->close() or die($this->db->error);
    }

    public function query($query)
    {
        return $this->db->query($query); //moved die away, i want to handle query errors
    }
        
    public function multi_query($query)
    {
        return $this->db->multi_query($query);
    }
    
    public function store_result()
    {
        return $this->db->store_result();
    }
    
    public function next_result()
    {
        return $this->db->next_result();
    }
    
    public function affectedRows()
    {
        return $this->db->affected_rows;
    }
    
    public function lastId()
    {
        return mysqli_insert_id($this->db);
    }
    
    public function escape($string)
    {
        return $this->db->real_escape_string($string);
    }
}
