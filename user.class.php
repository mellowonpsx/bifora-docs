<?php
/**
 * User
 *
 * @author mellowonpsx
 */

require_once "utils.php";

//status
define("BD_USER_NOT_LOGGED", "BD_USER_NOT_LOGGED");
define("BD_USER_LOGGED", "BD_USER_LOGGED");
define("BD_USER_UNLOGGED", "BD_USER_UNLOGGED");
define("BD_USER_DATA_NOT_SET", "DATA_NOT_SET");

//user type
define("BD_USER_TYPE_ADMIN", "ADMIN");
define("BD_USER_TYPE_USER", "USER");

// remember: the phylosophy behind this project expect to have already control and excaped variables
class User
{
    private $status;
    private $id;
    private $username;
    private $name;
    private $surname;
    private $mail;
    private $type;
    
    public function __construct($username = NULL, $password = NULL)
    {
        if(!isset($username) || !isset($password))
        {
            $this->status = BD_USER_NOT_LOGGED;
            return;
        }
        global $db;
        $query = "SELECT * FROM User WHERE username = '$username' AND password = MD5(CONCAT('$password',salt))";
        $result = $db->query($query);
        // max one row cause username is unique
        $row = mysqli_fetch_assoc($result);
        if(!$row)
        {
            $this->status = BD_USER_NOT_LOGGED;
            return;
        }
        $this->id = $row["id"];
        $this->username = $row["username"];
        $this->name = $row["name"];
        $this->surname = $row["surname"];
        $this->mail = $row["mail"];
        $this->type = $row["type"];
        $this->status = BD_USER_LOGGED;
        return;
    }
    
    public function isLogged()
    {
        if ($this->status === BD_USER_LOGGED)
        {
            return true;
        }
        return false;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getUserId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getSurname()
    {
        return $this->surname;
    }
    
    public function getMail()
    {
        return $this->mail;
    }
    
    public function getType()
    {
        return $this->type;
    }
    public function toJson()
    {
        $json_array = array();
        $json_array["status"] = $this->status;
        if($this->isLogged())
        {
            $json_array["id"] = $this->id;
            $json_array["user"] = $this->username;
            $json_array["name"] = $this->name;
            $json_array["surname"] = $this->surname;
            $json_array["mail"] = $this->mail;
            $json_array["type"] = $this->type;
        }
        $json_string  = json_encode($json_array);
        return $json_string;
    }
    
    public function logoutStatus()
    {
        return BD_USER_UNLOGGED;
    }
}