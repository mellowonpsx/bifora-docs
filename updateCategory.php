<?php
/**
 * updateCategory
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';

if(!isset($_POST["categoryName"]))
{
    die(json_error(Errors::$ERROR_90." _POST[\"categoryName\"]"));
}

if(!isset($_POST["categoryId"]))
{
    die(json_error(Errors::$ERROR_90." _POST[\"categoryId\"]"));
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

$categoryName = $db->escape(filter_var($_POST["categoryName"], FILTER_SANITIZE_STRING));
$categoryId = $db->escape(filter_var($_POST["categoryId"], FILTER_SANITIZE_STRING));

if(Category::updateCategory($categoryId, $categoryName))
{
    die(json_error(Errors::$ERROR_14));
}

die(json_ok());