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
    
    public static function eraseBind($idCategory, $idDocument, bool $eraseLast = false)
    {
        global $db;
        if(!Categorized::existBind($idCategory, $idDocument))
        {
            return 1; //bind not exist => no erase
        }
        if(Categorized::getBindNumber($idDocument)<1 && !$eraseLast)
        {
            return 1; //can't erase last bind for a document (a document must be categorized)
        }
        //lock
        $query = "LOCK TABLES Categorized WRITE, Categorized CategorizedReadLock READ";
        $db->query($query);
        //check again with table locked
        if(Categorized::getBindNumber($idDocument)<1 && !$eraseLast)
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
    
    /*public static function existCategoryById($categoryId)
    {
        // db is declared in utils $db = new DB();
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Category WHERE id = $categoryId";
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
    public static function existCategoryByName($categoryName)
    {
        // db is declared in utils $db = new DB();
        global $db;
        $categoryName = trim(strtolower($categoryName));
        $query = "SELECT COUNT(*) AS elementNumber FROM Category WHERE name = '$categoryName'";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return 1; //exist
        }
        return 0; //not exist
    }
    
    public static function insertCategory($categoryName)
    {
        if(Category::existCategoryByName($categoryName))
        {
            return 1;
        } //already exist -> not inserted
        // db is declared in utils $db = new DB();
        global $db;
        $categoryName = trim(strtolower($categoryName));
        $query = "INSERT INTO Category(id, name) VALUES (NULL, '$categoryName')";
        if($db->query($query, TRUE))
        {
            return 0; //inserted
        }
        return 1; //not inserted
    }
    
    public static function updateCategory($categoryId, $categoryUpdatedName)
    {
        if(!Category::existCategoryById($categoryId)) 
        {
            return 1;
        } //not exist -> not updated
        // db is declared in utils $db = new DB();
        global $db;
        $categoryUpdatedName = trim(strtolower($categoryUpdatedName));
        $query = "UPDATE Category SET name = '$categoryUpdatedName' WHERE id = $categoryId";
        if($db->query($query, TRUE))
        {
            return 0; //updated
        }
        return 1; //not updated
    }

    public static function eraseCategory($categoryId)
    {
        // db is declared in utils $db = new DB();
        global $db;
        if (!Category::existCategoryById($categoryId))
        {
            return 1;
        } //not exist -> not erased
        $query = "SELECT COUNT(*) AS elementNumber FROM Categorized WHERE idCategory = $categoryId";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return 1;
        }
        //lock
        $query = "LOCK TABLES Category WRITE, Categorized WRITE, Category as CategoryReadLock READ, Categorized CategorizedReadLock READ";
        //check again with table locked
        $query = "SELECT COUNT(*) AS elementNumber FROM Categorized WHERE idCategory = $categoryId";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return 1;
        }
        $query = "DELETE FROM Category WHERE id = $categoryId";
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
    public static function getCategoryList($searchKey = NULL)
    {
        // db is declared in utils 
        global $db;
        if ($searchKey != NULL) {
            $searchKey = trim(strtolower($searchKey));
            $searchKey = wordwrap($searchKey, 1, "%");
            $searchKey = trim($searchKey, "%");
            $query = "SELECT * FROM Category WHERE name LIKE '%$searchKey%'";
        }
        else
        {
            $query = "SELECT * FROM Category";
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
