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
//echo $user->isLogged();