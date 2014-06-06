<?php

/**
 * insertDocument
 * @author mellowonpsx
 */

require_once "utils.php";
// verify user
$user = getSessionUser();
if(empty($user))
{
    json_exit(Errors::$ERROR_00);
    return;
}

//check variabiles
if(!isset($_POST["title"]))
{
    json_exit(Errors::$ERROR_90." _POST[\"title\"]");
    return;
}
if(!isset($_POST["description"]))
{
    json_exit(Errors::$ERROR_90." _POST[\"description\"]");
    return;
}
if(!isset($_POST["type"]))
{
    json_exit(Errors::$ERROR_90." _POST[\"type\"]");
    return;
}
if(!isset($_POST["filename"]))
{
    json_exit(Errors::$ERROR_90." _POST[\"filename\"]");
    return;
}
if(!isset($_POST["extension"]))
{
    json_exit(Errors::$ERROR_90." _POST[\"extension\"]");
    return;
}

$isPrivate = false;
if(isset($_POST["isPrivate"]))
{
    $isPrivate = true;
}

$title = $db->escape(filter_var($_POST["title"], FILTER_SANITIZE_STRING));
$description = $db->escape(filter_var($_POST["description"], FILTER_SANITIZE_STRING));
$type = $db->escape(filter_var($_POST["type"], FILTER_SANITIZE_STRING));
$filename = $db->escape(filter_var($_POST["filename"], FILTER_SANITIZE_STRING));
$extension = $db->escape(filter_var($_POST["extension"], FILTER_SANITIZE_STRING));

if(Document::existDocumentByFilename($filename))
{
    json_exit(Errors::$ERROR_11);
    return;
}

$document = new Document();
$document->setMultipleValues($title, $filename, $extension, $description, $type, $isPrivate, $user->getUserId());
if($document->updateDBValue())
{
    json_exit(Errors::$ERROR_10);
    return;
}
//all ok
$result_array = array();
$result_array["status"] = "true";
echo json_encode($result_array);
return;