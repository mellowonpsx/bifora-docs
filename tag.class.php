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
            return 1; //exist
        }
        return 0; //not exist
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
            return 1; //exist
        }
        return 0; //not exist
    }
    
    //insert new tag
    public static function insertTag($tagName)
    {
        if(Tag::existTagByName($tagName))
        {
            return 1;
        } //already exist -> not inserted
        // db is declared in utils 
        global $db;
        $tagName = trim(strtolower($tagName));
        $query = "INSERT INTO Tag(id, name) VALUES (NULL, '$tagName')";
        if($db->query($query, TRUE))
        {
            return 0; //inserted
        }
        return 1; //not inserted
    }
    
    //
    public static function eraseTag($tagId)
    {
        // db is declared in utils 
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Tagged WHERE idTag = $tagId";
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
        $query = "SELECT COUNT(*) AS elementNumber FROM Tagged WHERE idTag = $tagId";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return 1;
        }
        $query = "DELETE FROM Tag WHERE id = $tagId";
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
