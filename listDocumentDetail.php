<?php
/**
 * listDocumentDetail
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';
// verify user
$user = getSessionUser();

if(empty($user))
{
    json_error(Errors::$ERROR_00);
    return;
}
if(isset($_POST["id"]))
{
    $document = new Document($_POST["id"]);
    echo $document->toJson();
}
?>