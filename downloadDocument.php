<?php
/**
 * downloadDocument
 *
 * @author mellowonpsx
 * @author aci
 */
//il download disattiva l'error reporting
error_reporting(0);
require_once 'utils.php';
//check variabiles
if(!isset($_GET["idDocument"]))
{
    //die(json_error(Errors::$ERROR_90." _POST[\"idDocument\"]"));
    //this error will appear in downloaded file, instead file content.
    //it's better if this message are human readable
    document_error(Errors::$ERROR_90." _POST[\"idDocument\"]");
}

if(!Document::existDocument($db->escape(filter_var($_GET["idDocument"], FILTER_SANITIZE_STRING))))
{
    //die(json_error(Errors::$ERROR_12));
    document_error(Errors::$ERROR_12);
}

$document = new Document($db->escape(filter_var($_GET["idDocument"]   , FILTER_SANITIZE_STRING)));

//if is public no control (exept login?)
if($document->getIsPrivate())
{
    //otherwise verify if not-owner adn not admin
    // verify user
    $user = getSessionUser();
    if(empty($user))
    {
        //die(json_error(Errors::$ERROR_00));
        document_error(Errors::$ERROR_00);
    }
    if(!$user->isAdmin() && $user->getUserId() != $document->getOwnerId())
    {
        //die(json_error(Errors::$ERROR_21));
        document_error(Errors::$ERROR_21);
    }
}

global $config;
$directoryUpload = $config->getParam("uploadDirectory");
$directoryDownload = $config->getParam("downloadDirectory");
//prepare filename
$downloadFilenameDirectory = $directoryDownload.$document->getFilename()."/";
$downloadFilename = $downloadFilenameDirectory.$document->getTitle().".".$document->getExtension();
$uploadFilename = $directoryUpload.$document->getFilename();
//check file already exist
//header serve per scaricare al posto di visualizzare

if(!file_exists($downloadFilename))
{
    // if not exist create folder
    mkdir($downloadFilenameDirectory);
    //copy file to folder
    copy($uploadFilename, $downloadFilename);
    //prepare a trim for file deletion
}
//last check and send to user
if (!file_exists($downloadFilename))
{
    document_error(Errors::$ERROR_20);
}
else
{
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary"); 
    header("Content-disposition: attachment; filename=\"".basename($downloadFilename)."\""); 
    readfile($downloadFilename);
}
//If continued downloads are a small percentage of your downloads, you can delete the zip file immediately;
//as long as your server is still sending the file to the client, it'll remain on disk.
unlink($downloadFilename);
rmdir($downloadFilenameDirectory);