<?php
/**
 * Category
 *
 * @author mellowonpsx
 */

require_once "utils.php";

// the phylosophy behind this project expect to have already control and excaped variables
class Category
{
    public static function insertCategory($categoryName)
    {
        if(existCategoryById($categoryId)) return 1; //not inserted
        $categoryName = trim(strtolower($categoryName));
        $db = new DB();
        $query = "INSERT INTO Category(id, name) VALUES (NULL, '$categoryName')";
        if($db->query($query, TRUE))
        {
            return 0; //inserted
        }
        return 1; //not inserted
    }
    
    public static function existCategoryById($categoryId)
    {
        $db = new DB();
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
        $categoryName = trim(strtolower($categoryName));
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
    
    public static function updateCategory($categoryId, $categoryUpdatedName)
    {
        if(!existCategoryById($categoryId)) return 1; //not updated
        $categoryUpdatedName = trim(strtolower($categoryUpdatedName));
        $db = new DB();
        $query = "UPDATE Category SET name = '$categoryUpdatedName' WHERE id = $categoryId";
        if($db->query($query, TRUE))
        {
            return 0; //updated
        }
        return 1; //not updated
    }
    
    public static function eraseCategory($categoryId)
    {
        $db = new DB();
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
        $db = new DB();
        if($searchKey != NULL)
        {
            $searchKey = trim(strtolower($searchKey));
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
