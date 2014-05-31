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
        if($db->query($query, TRUE))
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
        //lock
        $query = "LOCK TABLES Tagged WRITE, Tagged as TaggedReadLock READ";
        $db->query($query);
        //delete
        $query = "DELETE FROM Tagged  WHERE idTag = '$idTag' AND idDocument = '$idDocument'";
        $result = $db->query($query, TRUE);
        //check if was last document associated with tag
        if(Tagged::getBindNumberByTag($idTag) <1)
        {
            //was last document associated with tag => erase tag
            //lock prevent to "associate"tagged" new tag, after the delete and before the erase tag
            Tag::eraseTag($idTag); //check fault?
        }
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
    
    /*public static function existTagById($categoryId)
    {
        // db is declared in utils $db = new DB();
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Tag WHERE id = $categoryId";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return 1; //exist
        }
        return 0; //not exist
    }
    
    //exact name
    public static function existTagByName($categoryName)
    {
        // db is declared in utils $db = new DB();
        global $db;
        $categoryName = trim(strtolower($categoryName));
        $query = "SELECT COUNT(*) AS elementNumber FROM Tag WHERE name = '$categoryName'";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return 1; //exist
        }
        return 0; //not exist
    }
    
    public static function insertTag($categoryName)
    {
        if(Tag::existTagByName($categoryName))
        {
            return 1;
        } //already exist -> not inserted
        // db is declared in utils $db = new DB();
        global $db;
        $categoryName = trim(strtolower($categoryName));
        $query = "INSERT INTO Tag(id, name) VALUES (NULL, '$categoryName')";
        if($db->query($query, TRUE))
        {
            return 0; //inserted
        }
        return 1; //not inserted
    }
    
    public static function updateTag($categoryId, $categoryUpdatedName)
    {
        if(!Tag::existTagById($categoryId)) 
        {
            return 1;
        } //not exist -> not updated
        // db is declared in utils $db = new DB();
        global $db;
        $categoryUpdatedName = trim(strtolower($categoryUpdatedName));
        $query = "UPDATE Tag SET name = '$categoryUpdatedName' WHERE id = $categoryId";
        if($db->query($query, TRUE))
        {
            return 0; //updated
        }
        return 1; //not updated
    }

    public static function eraseTag($categoryId)
    {
        // db is declared in utils $db = new DB();
        global $db;
        if (!Tag::existTagById($categoryId))
        {
            return 1;
        } //not exist -> not erased
        $query = "SELECT COUNT(*) AS elementNumber FROM Tagged WHERE idTag = $categoryId";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return 1;
        }
        //lock
        $query = "LOCK TABLES Tag WRITE, Tagged WRITE, Tag as TagReadLock READ, Tagged TaggedReadLock READ";
        //check again with table locked
        $query = "SELECT COUNT(*) AS elementNumber FROM Tagged WHERE idTag = $categoryId";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return 1;
        }
        $query = "DELETE FROM Tag WHERE id = $categoryId";
        $result = $db->query($query, TRUE);
        //unlock
        $query = "UNLOCK TABLES";
        $db->query($query);
        //result contain delete query result
        if(!$result)
        {
            return 1;
        }
        return 0; 
    }
    
    //list all category or category that has part of string in name
    public static function getTagList($searchKey = NULL)
    {
        // db is declared in utils 
        global $db;
        if ($searchKey != NULL) {
            $searchKey = trim(strtolower($searchKey));
            $searchKey = wordwrap($searchKey, 1, "%");
            $searchKey = trim($searchKey, "%");
            $query = "SELECT * FROM Tag WHERE name LIKE '%$searchKey%'";
        }
        else
        {
            $query = "SELECT * FROM Tag";
        }
        $result = $db->query($query);
        $result_array = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $result_array[$row["id"]] = $row["name"];
        }
        return $result_array;
    }*/
}
