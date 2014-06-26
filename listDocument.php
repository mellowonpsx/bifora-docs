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
}//get category from POST

$pageNumber = 1;
if(isset($_POST["pageNumber"]))
{
    $pageNumber = $db->escape(filter_var( $_POST["pageNumber"], FILTER_SANITIZE_NUMBER_INT));
}

$documentPerPage = $config->getParam("documentPerPage");
$startLimit = ($pageNumber-1)*$documentPerPage;
$endLimit = $pageNumber*$documentPerPage;

//$result_array = Document::getDocumentList($startLimit, $endLimit, $category_array, true, NULL, NULL);

$user = getSessionUser();
if($user != NULL)
{
    if($user->getType() == BD_USER_TYPE_ADMIN)
    {
        //admin shows all
        //echo json_encode(Document::getDocumentList(0, 1000, Category::getCategoryList(), true, NULL, NULL));
        //echo json_encode(Document::getDocumentList(0, 1000, $category_array, true, NULL, NULL));
        $result_array = Document::getDocumentList($startLimit, $endLimit, $category_array, true, NULL, NULL);
    }
    else //logged show public and his his own file 
    {
        //echo json_encode(Document::getDocumentList(0, 1000, Category::getCategoryList(), false, $user->getUserId(), NULL));
        //echo json_encode(Document::getDocumentList(0, 1000, $category_array, false, $user->getUserId(), NULL));
        $result_array = Document::getDocumentList($startLimit, $endLimit, $category_array, false, $user->getUserId(), NULL);
        //$result_array["numberOfDocument"] = Document::getDocumentList(0, 1000, $category_array, true, NULL, NULL);
    }
}
else //user = null => not logged (?)
{
    //echo json_encode(Document::getDocumentList(0, 1000, Category::getCategoryList(), false, NULL, NULL));
    //echo json_encode(Document::getDocumentList(0, 1000, $category_array, false, NULL, NULL));
    $result_array = Document::getDocumentList($startLimit, $endLimit, $category_array, false, NULL, NULL);
    //$result_array["numberOfDocument"] = Document::getDocumentList(0, 1000, $category_array, true, NULL, NULL);
}
echo json_encode($result_array);