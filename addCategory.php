<?php

require_once 'utils.php';

if (isset($_POST["categoryName"])) 
{
    global $db;
    $user = getSessionUser();
    if ($user->getType() == BD_USER_TYPE_ADMIN) 
    {
        Category::insertCategory($_POST['categoryName']);
    }
}
