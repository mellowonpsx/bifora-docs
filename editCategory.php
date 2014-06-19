<?php

require_once 'utils.php';

if (isset($_POST["id"])&&isset($_POST["value"])) 
{
    global $db;
    $user = getSessionUser();
    if ($user->getType() == BD_USER_TYPE_ADMIN) 
    {
        Category::updateCategory($_POST["id"],$_POST['value']);
        echo "updated";
    }
    else
    {
        echo "no admin";
    }
}
else
{
    echo "error"+$_POST['id'];
}
