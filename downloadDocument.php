<?php
/**
 * downloadDocument
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';
//check variabiles
if(!isset($_POST["idDocument"]))
{
    //die(json_error(Errors::$ERROR_90." _POST[\"idDocument\"]"));
    //this error will appear in downloaded file, instead file content.
    //it's better if this message are human readable
    die(Errors::$ERROR_90." _POST[\"idDocument\"]");
}

if(!Document::existDocument($db->escape(filter_var($_POST["idDocument"], FILTER_SANITIZE_STRING))))
{
    //die(json_error(Errors::$ERROR_12));
    die(Errors::$ERROR_12);
}

$document = new Document($db->escape(filter_var($_POST["idDocument"], FILTER_SANITIZE_STRING)));

//if is public no control (exept login?)
if($document->getIsPrivate())
{
    //otherwise verify if not-owner adn not admin
    // verify user
    $user = getSessionUser();
    if(empty($user))
    {
        //die(json_error(Errors::$ERROR_00));
        die(Errors::$ERROR_00);
    }
    if($user->getType() != BD_USER_TYPE_ADMIN && $user->getUserId() != $document->getOwnerId())
    {
        //die(json_error(Errors::$ERROR_21));
        die(Errors::$ERROR_21);
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
    die(json_error(Errors::$ERROR_20));
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

//in caso il download parta, non devo fare alcun echo, sennò rischio di appenderlo al file appena inviato