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

if(!isset($_POST["categoryList"]))
{
    json_exit(Errors::$ERROR_90." _POST[\"categoryList\"]");
    return;
}
if(!isset($_POST["tagList"]))
{
    json_exit(Errors::$ERROR_90." _POST[\"tagList\"]");
    return;
}

$categoryList = array();
foreach ($_POST["categoryList"] as $category)
{
    array_push($categoryList,  array("id" => $db->escape(filter_var($category, FILTER_SANITIZE_STRING))));
}
$tagList = array();
foreach ($_POST["tagList"] as $tag)
{
    array_push($tagList,  array("name" => trim(strtolower($db->escape(filter_var($tag, FILTER_SANITIZE_STRING))))));
}

$isPrivate = false;
if(isset($_POST["isPrivate"]))
{
    $isPrivate = true;
}

$isUpdate = false;
if(isset($_POST["documentId"]))
{
    $isUpdate = true;
}


$title = $db->escape(filter_var($_POST["title"], FILTER_SANITIZE_STRING));
$description = $db->escape(filter_var($_POST["description"], FILTER_SANITIZE_STRING));
$type = $db->escape(filter_var($_POST["type"], FILTER_SANITIZE_STRING));
if(!Document::isDocumentType($type))
{
    $type = BD_DOCUMENT_TYPE_UNKNOW;
}
$filename = $db->escape(filter_var($_POST["filename"], FILTER_SANITIZE_STRING));
$extension = $db->escape(filter_var($_POST["extension"], FILTER_SANITIZE_STRING));

if(!$isUpdate)
{
    if(Document::existDocumentByFilename($filename))
    {
        json_exit(Errors::$ERROR_11);
        return;
    }

    $document = new Document();
}
else
{
    $document = new Document($db->escape(filter_var($_POST["documentId"], FILTER_SANITIZE_STRING)));
    
    //user check: if is update i must be owner or admin
    if($user->getType() != BD_USER_TYPE_ADMIN && $user->getUserId() != $document->getOwnerId())
    {
        echo Errors::$ERROR_21;
        return;
    }
    
    
    //if is an update, i erase all bind
    if(Tagged::eraseAllDocumentBind($document->getId()))
    {
        json_exit(Errors::$ERROR_30);
        return;
    }
    
    if(empty($categoryList))
    {
        json_exit(Errors::$ERROR_41);
        return;
    }

    if(Categorized::eraseAllDocumentBind($document->getId()))
    {
        json_exit(Errors::$ERROR_40);
        return;
    }
}

$document->setMultipleValues($title, $filename, $extension, $description, $type, $isPrivate, $user->getUserId());

// updateTag
foreach ($tagList as $tag)
{
    Tagged::insertTaggedByTagName($tag["name"], $document->getId());
}

// updateCategory
$atLeastOne = false;
foreach ($categoryList as $category)
{
    if(!Categorized::insertCategorized($category["id"], $document->getId()))
    {
        $atLeastOne = true;
    }
}
if(!$atLeastOne)
{
    json_exit(Errors::$ERROR_41);
    return;
}

// force update database value
if($document->updateDBValue())
{
    json_exit(Errors::$ERROR_10);
    return;
}
//all ok
$result_array = array();
$result_array["status"] = "true";
$result_array["id"] = $document->getId();
echo json_encode($result_array);
return;

/*
 */