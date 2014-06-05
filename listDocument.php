<?php

/**
 * listDocument
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
//get categody from POST

$user = getSessionUser();
if($user != NULL)
{
    if($user->getType() == BD_USER_TYPE_ADMIN)
    {
        //admin shows all
        echo json_encode(Document::getDocumentList(0, 1000, Category::getCategoryList(), true, NULL, NULL));
    }
    else //logged show public and his his own file 
    {
        echo json_encode(Document::getDocumentList(0, 1000, Category::getCategoryList(), false, $user->getUserId(), NULL));
    }
}
else
{
    echo json_encode(Document::getDocumentList(0, 1000, Category::getCategoryList(), false, NULL, NULL));
}