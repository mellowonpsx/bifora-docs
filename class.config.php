<?php
/**
 * Config
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';

class Config
{
    static public $db_host = "127.0.0.1"; //Problems with localhost name resolve
    static public $db_user = "root";
    static public $db_password = "";
    static public $db_name = "bifora_docs";
    
    private $paramArray;
    
    public function __construct()
    {
        $this->paramArray = array();
        $db = new DB();
        $query = "SELECT * FROM Configuration";
        $result = $db->query($query);
        $result_array = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $result_array[$row["parameterName"]] = $row["value"];
        }
        $this->paramArray = $result_array;
    }
    
    public function getParam($paramName)
    {
        if(isset($this->paramArray[$paramName])) return $this->paramArray[$paramName];
        return NULL;
    }
}
