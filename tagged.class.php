<?php
/**
 * Tagged
 *
 * @author mellowonpsx
 */

require_once "utils.php";

// the phylosophy behind this project expect to have already control and excaped variables
class Tagged
{
    public static function existBind($idTag, $idDocument)
    {                
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Tagged WHERE idTag = '$idTag' AND idDocument = '$idDocument'";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return true; //exist
        }
        return false; //not exist
    }
    
    public static function insertTagged($idTag, $idDocument)
    {
        global $db;
        if(Tagged::existBind($idTag, $idDocument) || !Tag::existTagById($idTag) || !Document::existDocument($idDocument))
        {
            return 1; // (already exist, or not exist category, or not exist document) -> not inserted
        }
        $query = "INSERT INTO Tagged(id, idTag, idDocument) VALUES (NULL, '$idTag', '$idDocument')";
        if($db->query($query))
        {
            return 0; //inserted
        }
        return 1; //not inserted
    }
       
    public static function eraseBind($idTag, $idDocument)
    {
        global $db;
        if(!Tagged::existBind($idTag, $idDocument))
        {
            return 1; //bind not exist => no erase
        }
        //delete
        $query = "DELETE FROM Tagged  WHERE idTag = '$idTag' AND idDocument = '$idDocument'";
        $db->query($query);
        $deletedRows = $db->affectedRows();
        //check if was last document associated with tag
        if(Tagged::getBindNumberByTag($idTag) <1)
        {
            //was last document associated with tag => erase tag
            Tag::eraseTag($idTag);
        }
        if(!$deletedRows)
        {
            return 1; //not erased
        }
        return 0; //erased
    }
    
    public static function getBindNumber($idDocument)
    {
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Tagged WHERE idDocument = '$idDocument'";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        return $elementNumber;
    }
    
    public static function getBindNumberByTag($idTag)
    {
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Tagged WHERE idTag = '$idTag'";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        return $elementNumber;
    }
}
