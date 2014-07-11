<?php
/**
 * deleteDocument
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

// verify user
$user = getSessionUser();
if(empty($user))
{
    die(json_error(Errors::$ERROR_00));
}
if(!$user->isAdmin() && $user->getUserId() != $document->getOwnerId())
{
    die(json_error(Errors::$ERROR_21));
}

$generateTempKey = true;
if(isset($_POST["eraseTempKey"]))
{
    $generateTempKey = false;
    $eraseTempKey = $db->escape(filter_var($_POST["eraseTempKey"], FILTER_SANITIZE_STRING));
}

if($generateTempKey)
{
    do
    {
        global $config;
        $expirationTime = $config->getParam("tempkeyExpirationTime");
        $eraseTempKey = TempKey::generateTempKey($user->getUserId(), $expirationTime);
    }while(!$eraseTempKey);
    echo json_ok($eraseTempKey);
    exit();
}

if(!TempKey::useTempKey($eraseTempKey, $user->getUserId())) //check if exist and use
{
    die(json_error(Errors::$ERROR_50));
}

// erasing document
global $config;
$directoryUpload = $config->getParam("uploadDirectory");
$directoryDownload = $config->getParam("downloadDirectory");
//prepare filename
$filename = $directoryUpload.$document->getFilename();
//check file already exist

if(!file_exists($filename))
{
    // il file non esiste, cancello comunque il documento ma lo notifico
    $document->deleteDocument();
    die(json_error(Errors::$ERROR_22));
}

unlink($filename);
$document->deleteDocument();
echo json_ok();
exit();