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
    echo Errors::$ERROR_90." _POST[\"idDocument\"]";
    return;
}

if(!Document::existDocument($db->escape(filter_var($_POST["idDocument"], FILTER_SANITIZE_STRING))))
{
    echo Errors::$ERROR_12;
    return;
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
        echo Errors::$ERROR_00;
        return;
    }
    if($user->getType() != BD_USER_TYPE_ADMIN && $user->getUserId() != $document->getOwnerId())
    {
        echo Errors::$ERROR_21;
        return;
    }
}

global $config;
$directoryUpload = $config->getParam("uploadDirectory");
$directoryDownload = $config->getParam("downloadDirectory");
//$directoryUpload = "./ul/";
//$directoryDownload = "./dl/";
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