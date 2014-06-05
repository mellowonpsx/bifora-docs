<?php

/**
 * listDocument
 * @author mellowonpsx
 */

require_once "utils.php";
$category_array = array();
if(isset($_POST["category"]))
{
    global $db;
    $json_array = json_decode($_POST["category"]);
    foreach ($json_array as $category)
    {
        $category_array[$category->id] = objectToArray($category);
    }
    //echo json_encode($category_array);
    //return;
    //$user= new User($username,$password);
    //setSessionUser($user); //set a session
    //echo $user->toJson();
}
//get categody from POST

$user = getSessionUser();
if($user != NULL)
{
    if($user->getType() == BD_USER_TYPE_ADMIN)
    {
        //admin shows all
        //echo json_encode(Document::getDocumentList(0, 1000, Category::getCategoryList(), true, NULL, NULL));
        echo json_encode(Document::getDocumentList(0, 1000, $category_array, true, NULL, NULL));
    }
    else //logged show public and his his own file 
    {
        //echo json_encode(Document::getDocumentList(0, 1000, Category::getCategoryList(), false, $user->getUserId(), NULL));
        echo json_encode(Document::getDocumentList(0, 1000, $category_array, false, $user->getUserId(), NULL));
    }
}
else
{
    //echo json_encode(Document::getDocumentList(0, 1000, Category::getCategoryList(), false, NULL, NULL));
    echo json_encode(Document::getDocumentList(0, 1000, $category_array, false, NULL, NULL));
}