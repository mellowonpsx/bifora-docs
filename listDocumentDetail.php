<?php

/**
 * uploadDocument
 * @author mellowonpsx
 */

require_once "utils.php";
// verify user
$user = getSessionUser();

if(empty($user))
{
    json_error(Errors::$ERROR_00);
    return;
}
if(isset($_POST["id"]))
{
    $doc=new Document($_POST["id"]);
    echo $doc->getJson();
}
?>