<?php

require_once 'utils.php';

if (isset($_POST["id"])) 
{
    global $db;
    $user = getSessionUser();
    if ($user->getType() == BD_USER_TYPE_ADMIN) 
    {
        echo Category::eraseCategory($_POST["id"]);
        
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
