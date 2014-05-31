<?php
/**
 * test
 *
 * @author mellowonpsx
 */

require_once "utils.php";
//work
//var_dump(Category::getCategoryList());
//var_dump(Category::getCategoryList("p"));
//var_dump(Category::getCategoryList("prov"));
//work 
//echo Category::eraseCategory(11);
//work 
//echo Category::insertCategory("provagliodiseo");
//work
//echo Category::existCategoryByName("prova");
//echo Category::existCategoryByName("prov");
//echo Category::existCategoryById(1);
//echo Category::existCategoryByName(33);
//work
//echo Category::updateCategory(11, "cane");
//echo Category::updateCategory(13, "cane");
//work
//echo Tag::insertTag("terzo");
//work
//var_dump(Tag::getTagList());
//var_dump(Tag::getTagList("ri"));
//var_dump(Tag::getTagList("pim"));
// work 
//$config declared in utils
//echo $config->getParamByName("numeroRighe"); //null show nothing
//echo $config->getParamByName("numRighe");
//work
//$user = new User("seiun","cane");
//echo $user->isLogged();
// work
//$sessionId = session_id(); 
//if(empty($sessionId)) session_start() or die("Could not start session"); 
//if(isset($_SESSION["user"]))
//{
//    $user = $_SESSION["user"];
//    echo "sessione recuperata";
//}
//else
//{
//    $user = new User("admin","admin");
//    $_SESSION["user"] = $user;
//    echo "nuovo utente creato";
//}
//session_write_close();
//work
//$user = getSessionUser();
//if(!$user->isLogged())
//{
//    echo "eseguo login";
//    $user = new \User("admin","admin");
//    setSessionUser($user);
//}
//echo "benvenuto ".$user->getUsername();
//work
//$document = new Document(3);
//echo $document->getDate("Y-m-d H:i:s");
//$document->setMultipleValues("la casa nel fosco", "casa.txt", "txt", "breve racconto di 150 pagine", BD_DOCUMENT_TYPE_DOCUMENT, false, NULL);
// different method to define document type
//$document->setMultipleValues("la casa nel fosco", "casa.txt", "txt", "breve racconto di 150 pagine", Document::listDocumentType()[2], false, NULL)
//work
//echo Categorized::insertCategoryzed(1, 1);
//echo Categorized::insertCategoryzed(2, 1);
//echo Categorized::getBindNumber(1);
//echo Categorized::eraseBind(2, 1);
//echo Categorized::getBindNumber(1);
//echo Categorized::eraseBind(1, 1);
//echo Categorized::getBindNumber(1);
//echo Categorized::eraseBind(1, 1, true);
//echo Categorized::getBindNumber(1);
//work all
Tag::insertTag("prova26");
echo Tagged::insertTagged(23, 1); //0 0
echo Tagged::insertTagged(23, 2); //0 1
//echo Tagged::insertTagged(25, 1); //0 1 non esiste più 25
echo Tagged::insertTagged(26, 1); // 0
echo Tagged::getBindNumber(1); //2 1
echo Tagged::getBindNumber(2); //1 1
echo Tagged::getBindNumberByTag(23); //2 2
//echo Tagged::getBindNumberByTag(25); //1 0
echo Tagged::getBindNumberByTag(26); //1
echo Tagged::eraseBind(23, 1); //0 0
//echo Tagged::eraseBind(25, 1); //0 1 //non esiste 25
echo Tagged::eraseBind(26, 1); //0
echo Tagged::getBindNumber(1); //0 0
echo Tagged::getBindNumber(2); //1 1
echo Tagged::getBindNumberByTag(23); //1 1
echo Tagged::getBindNumberByTag(25); //0 0
echo Tagged::getBindNumberByTag(26); //0 

