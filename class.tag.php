<?php
/**
 * Tag
 *
 * @author mellowonpsx
 */

require_once "utils.php";

// remember: the phylosophy behind this project expect to have already control and excaped variables
class Tag
{
    public static function existTagById($tagId)
    {
        // db is declared in utils 
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Tag WHERE id = $tagId";
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
    public static function existTagByName($tagName)
    {
        // db is declared in utils 
        global $db;
        $tagName = trim(strtolower($tagName));
        $query = "SELECT COUNT(*) AS elementNumber FROM Tag WHERE name = '$tagName'";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return true; //exist
        }
        return false; //not exist
    }
    
    //insert new tag
    public static function insertTag($tagName)
    {
        if(Tag::existTagByName($tagName))
        {
            return 1; //already exist -> not inserted
        }
        // db is declared in utils 
        global $db;
        $tagName = trim(strtolower($tagName));
        $query = "INSERT INTO Tag(id, name) VALUES (NULL, '$tagName')";
        if($db->query($query))
        {
            return 0; //inserted
        }
        return 1; //not inserted
    }
    
    //
    public static function eraseTag($tagId)
    {
        global $db;
        //single query check if there is row in Categoryzed, otherwise remove element
        //single query do not need lock (YEAH!!!)
        //improve performance
        if (!Tag::existTagById($tagId))
        {
            return 1; //not exist -> not erased
        }
        //improve performance
        if(Tagged::getBindNumberByTag($tagId) > 0)
        {
            return 1; //not eresable (has bind)
        }
        $query = "DELETE FROM Tag WHERE id= '$tagId' AND '0' IN (SELECT COUNT(*) FROM Tagged WHERE idTag = '$tagId')";
        $db->query($query);
        if(!$db->affectedRows())
        {
            return 1; //not erased
        }
        return 0; //erased
    }
    
    //list all tag or tag that has part of string in name
    public static function getTagList($searchKey = NULL)
    {
        // db is declared in utils 
        global $db;
        if($searchKey != NULL)
        {
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
    }
}
