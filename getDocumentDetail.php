<?php
/**
 * getDocumentDetail
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';
//check variabiles
if(!isset($_POST["idDocument"]))
{
    die(json_error(Errors::$ERROR_90." _POST[\"idDocument\"]"));
}

if(!Document::existDocument($db->escape(filter_var($_POST["idDocument"], FILTER_SANITIZE_STRING))))
{
    die(json_error(Errors::$ERROR_12));
}

$document = new Document($db->escape(filter_var($_POST["idDocument"], FILTER_SANITIZE_STRING)));

//if is public no control (exept login?) => no!
if($document->getIsPrivate())
{
    //otherwise verify if not-owner adn not admin
    // verify user
    $user = getSessionUser();
    if(empty($user))
    {
        die(json_error(Errors::$ERROR_00));
    }
    if($user->getType() != BD_USER_TYPE_ADMIN && $user->getUserId() != $document->getOwnerId())
    {
        die(json_error(Errors::$ERROR_21));
    }
    echo json_ok($document->toArray($user->getUserId()));
    exit();
}

//die(json_ok($document->toJson()));
echo json_ok($document->toArray());
exit();