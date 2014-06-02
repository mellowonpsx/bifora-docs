<?php

/**
 * login
 * @author mellowonpsx
 */

require_once "utils.php";

if(isset($_POST["username"])&&isset($_POST["password"]))
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
}
