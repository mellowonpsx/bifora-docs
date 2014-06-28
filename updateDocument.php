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
    return;
}

//check variabiles
if(!isset($_POST["documentId"]))
{
    json_error(Errors::$ERROR_90." _POST[\"documentId\"]");
    return;
}

if(!isset($_POST["title"]))
{
    json_error(Errors::$ERROR_90." _POST[\"title\"]");
    return;
}
if(!isset($_POST["description"]))
{
    json_error(Errors::$ERROR_90." _POST[\"description\"]");
    return;
}
if(!isset($_POST["type"]))
{
    json_error(Errors::$ERROR_90." _POST[\"type\"]");
    return;
}

if(!isset($_POST["categoryList"]))
{
    json_error(Errors::$ERROR_90." _POST[\"categoryList\"]");
    return;
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
    echo Errors::$ERROR_12;
    return;
}


if(!Document::existDocument($db->escape(filter_var($_POST["documentId"], FILTER_SANITIZE_STRING))))
{
    json_error(Errors::$ERROR_12);
    return;
}

$title = $db->escape(filter_var($_POST["title"], FILTER_SANITIZE_STRING));
$description = $db->escape(filter_var($_POST["description"], FILTER_SANITIZE_STRING));
$type = $db->escape(filter_var($_POST["type"], FILTER_SANITIZE_STRING));
if(!Document::isDocumentType($type))
{
    $type = BD_DOCUMENT_TYPE_UNKNOW;
}
//$filename = $db->escape(filter_var($_POST["filename"], FILTER_SANITIZE_STRING));
//$extension = $db->escape(filter_var($_POST["extension"], FILTER_SANITIZE_STRING));

$document = new Document($db->escape(filter_var($_POST["documentId"], FILTER_SANITIZE_STRING)));
    
//user check: if is update i must be owner or admin
if($user->getType() != BD_USER_TYPE_ADMIN && $user->getUserId() != $document->getOwnerId())
{
    echo Errors::$ERROR_21;
    return;
}

//erase all bind
if(Tagged::eraseAllDocumentBind($document->getId()))
{
    json_error(Errors::$ERROR_30);
    return;
}
   
if(empty($categoryList))
{
    json_error(Errors::$ERROR_41);
    return;
}

if(Categorized::eraseAllDocumentBind($document->getId()))
{
    json_error(Errors::$ERROR_40);
    return;
}

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
    //default category: the one with lowest id
    Categorized::insertCategorized(Category::getFirstCategoryId(), $document->getId());
    //notifico l'errore
    json_error(Errors::$ERROR_41);
    return;
}

// force update database value
if($document->updateDBValue())
{
    json_error(Errors::$ERROR_10);
    return;
}
//all ok
$result_array = array();
$result_array["status"] = "true";
echo json_encode($result_array);
return;