<?php
/**
 * insertCategory
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';

if(!isset($_POST["categoryName"]))
{
    die(json_error(Errors::$ERROR_90." _POST[\"categoryName\"]"));
}

global $db;
$user = getSessionUser();

if(empty($user))
{
    die(json_error(Errors::$ERROR_00));
    
}

if($user->getType() != BD_USER_TYPE_ADMIN) //only admin can modify category!!
{
    die(json_error(Errors::$ERROR_01));
}

if(Category::insertCategory($db->escape(filter_var($_POST['categoryName'], FILTER_SANITIZE_STRING))))
{
    die(json_error(Errors::$ERROR_13));
}

die(json_ok());