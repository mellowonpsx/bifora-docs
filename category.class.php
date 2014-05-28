<?php
/**
 * Description of category
 *
 * @author mellowonpsx
 */

require_once "utils.php";

// the phylosophy behind this project expect to have already control and excaped variables
class Category
{
    public static function insertCategory($categoryName)
    {
        $categoryName = trim(strtoupper($categoryName));
        $db = new DB();
        $query = "INSERT INTO Category(id, name) VALUES (NULL, '$categoryName')";
        if($db->query($query))
        {
            return 0; //inserted
        }
        return 1; //not inserted
    }
    
    //exact name
    public static function existCategory($categoryName)
    {
        $categoryName = trim(strtoupper($categoryName));
        $db = new DB();
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
    
    public static function updateCategory($categoryToUpdate, $categoryUpdatedName)
    {
        $categoryToUpdate = trim(strtoupper($categoryToUpdate));
        $db = new DB();
        $query = "UPDATE Category SET name = '$categoryUpdatedName' WHERE id = $categoryToUpdate";
        if($db->query($query))
        {
            return 0; //updated
        }
        return 1; //not updated
    }
    
    public static function eraseCategory($categoryToErase)
    {
        $db = new DB();
        $query = "SELECT COUNT(*) AS elementNumber FROM Categorized WHERE idCategory = $categoryToErase";
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
        $query = "SELECT COUNT(*) AS elementNumber FROM Categorized WHERE idCategory = $categoryToErase";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return 1;
        }
        $query = "DELETE FROM Category WHERE id = $categoryToErase";
        $result = $db->query($query);
        //unlock
        $query = "UNLOCK TABLES";
        $result = $db->query($query);
        return 0;
    }
    
    //list all category or category that has part of string in name
    public static function getCategoryList($searchKey = NULL)
    {
        $db = new DB();
        if($searchKey != NULL)
        {
            $searchKey = trim(strtoupper($searchKey));
            $searchKey = wordwrap($searchKey, 1, "%");
            $searchKey = trim($searchKey, "%");
            $query = "SELECT * FROM Category WHERE name LIKE '%$searchKey%'";
        }
        else $query = "SELECT * FROM Category";
        $result = $db->query($query);
        $result_array = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $result_array[$row["id"]] = $row["name"];
        }
        return $result_array;
    }
}

?>