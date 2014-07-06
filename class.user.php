<?php
/**
 * User
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';

//status
define("BD_USER_NOT_LOGGED", "BD_USER_NOT_LOGGED");
define("BD_USER_LOGGED", "BD_USER_LOGGED");
define("BD_USER_UNLOGGED", "BD_USER_UNLOGGED");
define("BD_USER_DATA_NOT_SET", "DATA_NOT_SET");
define("BD_USER_NOT_EXIST", "USER_NOT_EXIST");


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
        if($this->status === BD_USER_LOGGED)
        {
            return true;
        }
        return false;
    }
    
    public function isAdmin()
    {
        if($this->status === BD_USER_LOGGED && $this->getType() === BD_USER_TYPE_ADMIN)
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
    
    public function toArray()
    {
        $data_array = array();
        $data_array["status"] = $this->status;
        if($this->isLogged())
        {
            $data_array["id"] = $this->id;
            $data_array["user"] = $this->username;
            $data_array["name"] = $this->name;
            $data_array["surname"] = $this->surname;
            $data_array["mail"] = $this->mail;
            $data_array["type"] = $this->type;
        }
        return $data_array;
    }
    
    public function toJson()
    {
        return json_encode($this->toArray());
    }
    
    public static function logoutStatus()
    {
        return BD_USER_UNLOGGED;
    }
    
    public static function getUsernameById($userId)
    {
        global $db;
        $query = "SELECT id, username FROM User WHERE id = '$userId'";
        $result = $db->query($query);
        // max one row cause username is unique
        $row = mysqli_fetch_assoc($result);
        if(!$row)
        {
            return BD_USER_NOT_EXIST;
        }
        return $row["username"];
    }    
}