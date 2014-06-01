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
    // get an array with one id=>name
    public static function getCategoryById($categoryId)
    {
        global $db;
        $query = "SELECT * FROM Category WHERE id = '$categoryId'";
        $result = $db->query($query);
        $result_array = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $result_array[$row["id"]] = $row["name"];
        }
        return $result_array;
    }
    
    public static function existCategoryById($categoryId)
    {
        // db is declared in utils $db = new DB();
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Category WHERE id = $categoryId";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return true; //exist
        }
        return false; //not exist
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
            return true; //exist
        }
        return false; //not exist
    }
    
    public static function insertCategory($categoryName)
    {
        if(Category::existCategoryByName($categoryName))
        {
            return 1; //already exist -> not inserted
        }
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
            return 1; //not exist -> not updated
        }
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
            return 1; //not exist -> not erased
        }
        if(Categorized::getBindNumberByCategory($categoryId) > 0)
        {
            return 1; //not eresable (has bind)
        }
        //lock
        $query = "LOCK TABLES Category WRITE, Categorized WRITE, Category as CategoryReadLock READ, Categorized as CategorizedReadLock READ";
        $db->query($query);
        //check again with table locked
        if(Categorized::getBindNumberByCategory($categoryId) > 0)
        {
            //unlock if fail 
            $query = "UNLOCK TABLES";
            $db->query($query);
            return 1; //not eresable (has bind)
        }
        $query = "DELETE FROM Category WHERE id = $categoryId";
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
    }
}
parse_str($_SERVER['QUERY_STRING'],$query);
$c=new Category();
if($query['type']=='list'){
    echo json_encode($c::getCategoryList());
}