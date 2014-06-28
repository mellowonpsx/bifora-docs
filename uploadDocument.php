<?php
/**
 * uploadDocument
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
global $config;
$directoryUpload = $config->getParam("uploadDirectory");
$directoryDownload = $config->getParam("downloadDirectory");
$directory = $directoryUpload;
//faccio upload di un file alla volta, serve davvero il foreach?
foreach($_FILES as $nomefile => $descrittore)
{
    $tmp_name = $descrittore["tmp_name"];
    $name = $descrittore["name"];
    $extension = pathinfo($name, PATHINFO_EXTENSION);
    $filename = md5($name);
    //remove extension to prevent server execution
    //encrypt file name to prevent illegal search (?)
    //maybe modify htdocs
    //attach extension on file before download (and rename as title.extension)
    //in temp folder, and delete temp folder after some times
    //on modify delete file
    while(file_exists($directory.$filename))
    {
        $filename = md5($filename);
    }
    
    if(!move_uploaded_file($tmp_name, $directory.$filename))
    {
        die(json_error(Errors::$ERROR_23));
    }
    
    $document = new Document();
    $document->setMultipleValues(BD_DOCUMENT_DEFAULT_NAME, $filename, $extension, "", BD_DOCUMENT_DEFAULT_TYPE, true, $user->getUserId());
    //default category: the one with lowest id => to not have a file without category
    Categorized::insertCategorized(Category::getFirstCategoryId(), $document->getId());
    $result_array["documentId"] = $document->getId();
    echo json_ok($result_array);
    exit();
}