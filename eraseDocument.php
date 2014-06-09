<?php

/**
 * eraseDocument
 * @author mellowonpsx
 */

require_once "utils.php";
//check variabiles
if(!isset($_GET["idDocument"]))
{
    json_error(Errors::$ERROR_90." _GET[\"idDocument\"]");
    return;
}

if(!Document::existDocument($db->escape(filter_var($_GET["idDocument"], FILTER_SANITIZE_STRING))))
{
    json_error(Errors::$ERROR_12);
    return;
}

$document = new Document($db->escape(filter_var($_GET["idDocument"], FILTER_SANITIZE_STRING)));

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
if(isset($_GET["eraseTempKey"]))
{
    $generateTempKey = false;
    $eraseTempKey = $db->escape(filter_var($_GET["eraseTempKey"], FILTER_SANITIZE_STRING));
    return;
}

define("BD_TEMPKEY_EXPIRATION_TIME_SECONDS", 60); 

if($generateTempKey)
{
    do
    {
        $tempKey = TempKey::generateTempKey($user->getUserId(), BD_TEMPKEY_EXPIRATION_TIME_SECONDS);
    }while(!$tempKey);
    echo json_encode($tempKey);
    return;
}

if(!TempKey::useTempKey($eraseTempKey, $user->getUserId())) //check if exist and use
{
    json_error(Errors::$ERROR_50);
    return;
}

// erasing document

//global $config;
//$directoryUpload = $config->getParam("uploadDirectory");
//$directoryDownload = $config->getParam("downloadDirectory");
$directoryUpload = "./ul/";
$directoryDownload = "./dl/";
//prepare filename
$filename = $directoryUpload.$document->getFilename();
//check file already exist

if(!file_exists($filename))
{
    json_error(Errors::$ERROR_20);
    return;
}

unlink($filename);
$document->deleteDocument();