<?php
/**
 * eraseDocument
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';
//check variabiles
if(!isset($_POST["idDocument"]))
{
    json_error(Errors::$ERROR_90." _POST[\"idDocument\"]");
    return;
}

if(!Document::existDocument($db->escape(filter_var($_POST["idDocument"], FILTER_SANITIZE_STRING))))
{
    json_error(Errors::$ERROR_12);
    return;
}

$document = new Document($db->escape(filter_var($_POST["idDocument"], FILTER_SANITIZE_STRING)));

// verify user
$user = getSessionUser();
if(empty($user))
{
    json_error(Errors::$ERROR_00);
    return;
}
if($user->getType() != BD_USER_TYPE_ADMIN && $user->getUserId() != $document->getOwnerId())
{
    json_error(Errors::$ERROR_21);
    return;
}

$generateTempKey = true;
if(isset($_POST["eraseTempKey"]))
{
    $generateTempKey = false;
    $eraseTempKey = $db->escape(filter_var($_POST["eraseTempKey"], FILTER_SANITIZE_STRING));
    return;
}

define("BD_TEMPKEY_EXPIRATION_TIME_SECONDS", 60); 

if($generateTempKey)
{
    do
    {
        $eraseTempKey = TempKey::generateTempKey($user->getUserId(), BD_TEMPKEY_EXPIRATION_TIME_SECONDS);
    }while(!$eraseTempKey);
    echo json_encode($eraseTempKey);
    return; //importante sennò va avinti l'esecuzione
}

if(!TempKey::useTempKey($eraseTempKey, $user->getUserId())) //check if exist and use
{
    json_error(Errors::$ERROR_50);
    return;
}

// erasing document

global $config;
$directoryUpload = $config->getParam("uploadDirectory");
$directoryDownload = $config->getParam("downloadDirectory");
//$directoryUpload = "./ul/";
//$directoryDownload = "./dl/";
//prepare filename
$filename = $directoryUpload.$document->getFilename();
//check file already exist

if(!file_exists($filename))
{
    // il file non esiste, cancello comunque il documento ma lo notifico
    $document->deleteDocument();
    json_error(Errors::$ERROR_22);
    return;
}

unlink($filename);
$document->deleteDocument();