<?php

/**
 * uploadDocument
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
//global $config;
//$directory = $config->getParam("uploadDirectory");
$directory = "./ul/";
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
    $result_array = array();
    while(file_exists($directory.$filename))
    {
        $filename = md5($filename);
    }
    if(move_uploaded_file($tmp_name, $directory.$filename))
    {
        $result_array["status"] = "true";
        // ho valuato se rinviarlo al client o mettere nella sessione
        // restituendolo al client mi "espongo" leggermente ad un utente curiosone
        // ma comunque non gli comunico la cartella di upload, quindi la sua visione è limitata.
        // se lo mettessi nella sessione, mi esporrei a problemi di concorrenza in caso di upload multipli
        // file grandi = tanto tempo = non istantaneo => possibilità di invertire ordine di terminazione.
        $result_array["filename"] = "$filename";
        $result_array["extension"] = "$extension";
    }
    else
    {
        $result_array["status"] = "false";
    }
    echo json_encode($result_array);
}