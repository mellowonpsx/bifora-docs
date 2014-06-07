<?php

/**
 * eraseDocument
 * @author mellowonpsx
 */

require_once "utils.php";
//check variabiles
if(!isset($_GET["idDocument"]))
{
    echo Errors::$ERROR_90." _GET[\"idDocument\"]";
    return;
}

if(!isset($_GET["eraseTempKey"]))
{
    echo Errors::$ERROR_90." _GET[\"tempKey\"]";
    return;
}

if(!Document::existDocument($db->escape(filter_var($_GET["idDocument"], FILTER_SANITIZE_STRING))))
{
    echo Errors::$ERROR_12;
    return;
}

$document = new Document($db->escape(filter_var($_GET["idDocument"], FILTER_SANITIZE_STRING)));

// verify user
$user = getSessionUser();
if(empty($user))
{
    echo Errors::$ERROR_00;
    return;
}
if($user->getType() != BD_USER_TYPE_ADMIN && $user->getUserId() != $document->getOwnerId())
{
    echo Errors::$ERROR_21;
    return;
}

$eraseTempKey = $db->escape(filter_var($_GET["eraseTempKey"], FILTER_SANITIZE_STRING));

if(!TempKey::existTempKey($eraseTempKey, $user->getUserId()))
{
    echo Errors::$ERROR_50;
    return;
}

if(!TempKey::isValidTempKey($eraseTempKey, $user->getUserId()))
{
    echo Errors::$ERROR_51;
    return;
}
/*
//global $config;
//$directoryUpload = $config->getParam("uploadDirectory");
//$directoryDownload = $config->getParam("downloadDirectory");
$directoryUpload = "./ul/";
$directoryDownload = "./dl/";
//prepare filename
$downloadFilenameDirectory = $directoryDownload.$document->getFilename()."/";
$downloadFilename = $downloadFilenameDirectory.$document->getTitle().".".$document->getExtension();
$uploadFilename = $directoryUpload.$document->getFilename();
//check file already exist
if(!file_exists($downloadFilename))
{
    // if not exist create folder
    mkdir($directoryDownload.$document->getFilename());
    //copy file to folder
    copy($uploadFilename, $downloadFilename);
    //prepare a trim for file deletion
}    
//last check and send to user
if (!file_exists($downloadFilename))
{
    echo Errors::$ERROR_20;
    return;
}
else
{
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary"); 
    header("Content-disposition: attachment; filename=\"".basename($downloadFilename)."\""); 
    readfile($downloadFilename); 
}
//If continued downloads are a small percentage of your downloads, you can delete the zip file immediately; as long as your server is still sending the file to the client, it'll remain on disk.
unlink($downloadFilename);
rmdir($downloadFilenameDirectory);
 
 /*
 */