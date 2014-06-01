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
    public function json(){

        $r="{";
        $r.='"id": "'.$this->id;
        $r.='","status":"'.$this->status;
        $r.='","user": "'.$this->username;
        $r.='","name": "'.$this->name;
        $r.='","surname": "'.$this->surname;
        $r.='","mail": "'.$this->mail;
        $r.='","type": "'.$this->type;
        $r.='"}';
        return $r;
    }
}
parse_str($_SERVER['QUERY_STRING'],$query);
$obj= new User($query['user'],$query['pass']);
echo $obj->json();
