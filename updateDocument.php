<?php
/**
 * updateDocument
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';
// verify user
$user = getSessionUser();
if(empty($user))
{
    die(json_error(Errors::$ERROR_00));
}

//check variabiles
if(!isset($_POST["documentId"]))
{
    die(json_error(Errors::$ERROR_90." _POST[\"documentId\"]"));
}

if(!isset($_POST["title"]))
{
    die(json_error(Errors::$ERROR_90." _POST[\"title\"]"));
}
if(!isset($_POST["description"]))
{
    die(json_error(Errors::$ERROR_90." _POST[\"description\"]"));
}
if(!isset($_POST["type"]))
{
    die(json_error(Errors::$ERROR_90." _POST[\"type\"]"));
}

if(!isset($_POST["categoryList"]))
{
    die(json_error(Errors::$ERROR_90." _POST[\"categoryList\"]"));
}

//l'assenza di tagList non è un errore, i tag non sono obbligatori!
//if(!isset($_POST["tagList"]))
//{
    //json_error(Errors::$ERROR_90." _POST[\"tagList\"]");
    //return;
//}

$tagList = array();
if(isset($_POST["tagList"]))
{
    foreach ($_POST["tagList"] as $tag)
    {
        array_push($tagList,  array("name" => trim(strtolower($db->escape(filter_var($tag, FILTER_SANITIZE_STRING))))));
    }
}

$categoryList = array();
foreach ($_POST["categoryList"] as $category)
{
    array_push($categoryList,  array("id" => $db->escape(filter_var($category, FILTER_SANITIZE_STRING))));
}

$isPrivate = false;
if(isset($_POST["isPrivate"]))
{
    $isPrivate = true;
}

if(!Document::existDocument($db->escape(filter_var($_POST["documentId"], FILTER_SANITIZE_STRING))))
{
    die(json_error(Errors::$ERROR_12));
}


if(!Document::existDocument($db->escape(filter_var($_POST["documentId"], FILTER_SANITIZE_STRING))))
{
    die(json_error(Errors::$ERROR_12));
}

$title = $db->escape(filter_var($_POST["title"], FILTER_SANITIZE_STRING));
$description = $db->escape(filter_var($_POST["description"], FILTER_SANITIZE_STRING));
$type = $db->escape(filter_var($_POST["type"], FILTER_SANITIZE_STRING));

if(!Document::isDocumentType($type))
{
    $type = BD_DOCUMENT_TYPE_UNKNOW;
}

$document = new Document($db->escape(filter_var($_POST["documentId"], FILTER_SANITIZE_STRING)));
    
//user check: (is always an update) i must be owner or admin
if($user->getType() != BD_USER_TYPE_ADMIN && $user->getUserId() != $document->getOwnerId())
{
    die(json_error(Errors::$ERROR_21));
}

//erase all bind
if(Tagged::eraseAllDocumentBind($document->getId()))
{
    die(json_error(Errors::$ERROR_30));
}
   
if(empty($categoryList))
{
    die(json_error(Errors::$ERROR_41));
}

if(Categorized::eraseAllDocumentBind($document->getId()))
{
    die(json_error(Errors::$ERROR_40));
}

//setMultipleValues($title, $filename, $extension, $description, $type, $isPrivate, $ownerId)
$document->setMultipleValues($title, null, null, $description, $type, $isPrivate, null);

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
    //puo capitare che l'admin cancelli una categoria che io ho scelto come unica per il mio file.
    //per non lasciare il file non categorizzato, prima di notificare l'errore, lo attacco alla categoria di default.
    //default category: the one with lowest id => to not have a file without category
    Categorized::insertCategorized(Category::getFirstCategoryId(), $document->getId());
    //notifico l'errore
    die(json_error(Errors::$ERROR_41));
}

// force update database value
if($document->updateDBValue())
{
    die(json_error(Errors::$ERROR_10));
}

//all ok
echo json_ok();
exit();