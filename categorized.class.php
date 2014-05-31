<?php
/**
 * Categorized
 *
 * @author mellowonpsx
 */

require_once "utils.php";

// the phylosophy behind this project expect to have already control and excaped variables
class Categorized
{
    public static function existBind($idCategory, $idDocument)
    {                
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Categorized WHERE idCategory = '$idCategory' AND idDocument = '$idDocument'";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return true; //exist
        }
        return false; //not exist
    }
    
    public static function insertCategoryzed($idCategory, $idDocument)
    {
        global $db;
        if(Categorized::existBind($idCategory, $idDocument) || !Category::existCategoryById($idCategory) || !Document::existDocument($idDocument))
        {
            return 1; // (already exist, or not exist category, or not exist document) -> not inserted
        }
        $query = "INSERT INTO Categorized(id, idCategory, idDocument) VALUES (NULL, '$idCategory', '$idDocument')";
        if($db->query($query, TRUE))
        {
            return 0; //inserted
        }
        return 1; //not inserted
    }
    
    public static function eraseBind($idCategory, $idDocument, $eraseLast = false)
    {
        global $db;
        if(!Categorized::existBind($idCategory, $idDocument))
        {
            return 1; //bind not exist => no erase
        }
        if(Categorized::getBindNumber($idDocument)<2 && !$eraseLast)
        {
            return 1; //can't erase last bind for a document (a document must be categorized)
        }
        //lock
        $query = "LOCK TABLES Categorized WRITE, Categorized as CategorizedReadLock READ";
        $db->query($query);
        //check again with table locked
        if(Categorized::getBindNumber($idDocument)<2 && !$eraseLast)
        {
            //unlock if fail 
            $query = "UNLOCK TABLES";
            $db->query($query);
            return 1; //can't erase last bind for a document (a document must be categorized)
        }
        $query = "DELETE FROM Categorized  WHERE idCategory = '$idCategory' AND idDocument = '$idDocument'";
        $result = $db->query($query, TRUE);
        //unlock
        $query = "UNLOCK TABLES";
        $db->query($query);
        //result contain delete query result
        if(!$result)
        {
            return 1; //not erased
        }
        return 0; //erased
    }
    
    public static function getBindNumber($idDocument)
    {
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Categorized WHERE idDocument = '$idDocument'";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        return $elementNumber;
    }
    
    public static function getBindNumberByCategory($idCategory)
    {
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Categorized WHERE idCategory = '$idCategory'";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        return $elementNumber;
    }
}
