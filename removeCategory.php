<?php
/**
 * removeCategory
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';

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
if(!$user->isAdmin()) //only admin can modify category!!
{
    die(json_error(Errors::$ERROR_01));
}

$categoryId = $db->escape(filter_var($_POST["categoryId"], FILTER_SANITIZE_STRING));

if(Category::eraseCategory($categoryId))
{
    die(json_error(Errors::$ERROR_15));
}

json_ok();
exit();