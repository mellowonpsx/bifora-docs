<?php
/**
 * login
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';

/*if(isset($_POST["username"])&&isset($_POST["password"]))
{
    global $db;
    $username = $db->escape(filter_var($_POST["username"], FILTER_SANITIZE_STRING));
    $password = $db->escape(filter_var($_POST["password"], FILTER_SANITIZE_STRING));
    $user= new User($username,$password);
    setSessionUser($user); //set a session
    echo $user->toJson();
}
else
{
    $user= new User("","");
    removeSession();
    echo $user->toJson();
}*/

if(!isset($_POST["username"]))
{
    die(json_error(Errors::$ERROR_90." _POST[\"username\"]"));
}

if(!isset($_POST["password"]))
{
    die(json_error(Errors::$ERROR_90." _POST[\"password\"]"));
}

global $db;
$username = $db->escape(filter_var($_POST["username"], FILTER_SANITIZE_STRING));
$password = $db->escape(filter_var($_POST["password"], FILTER_SANITIZE_STRING));
$user= new User($username,$password);
setSessionUser($user); //set a session

echo json_ok($user->toArray());
exit();
