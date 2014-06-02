<?php

/**
 * listCategory
 * @author mellowonpsx
 */

require_once "utils.php";

//da modificare!!
$user = getSessionUser();
if($user != NULL)
{
    if($user->getType() == BD_USER_TYPE_ADMIN)
    {
        //admin shows all
        echo json_encode(Document::getDocumentList(0, 1000, Category::getCategoryList(), true, NULL, NULL));
        return;
    }
    //logged show public and his his own file 
    echo json_encode(Document::getDocumentList(0, 1000, Category::getCategoryList(), false, $user->getUserId(), NULL));
    return;
}
//not logged show all but public
echo json_encode(Document::getDocumentList(0, 1000, Category::getCategoryList(), false, NULL, NULL));
return;