<?php
/**
 * Tagged
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';

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
    
    public static function existBindByName($tag, $idDocument)
    {                
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Tagged,Tag WHERE idDocument = '$idDocument' AND Tag.id=Tagged.idTag AND Tag.name='$tag'";
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
            return 1; // (already exist, or not exist tag, or not exist document) -> not inserted
        }
        $query = "INSERT INTO Tagged(id, idTag, idDocument) VALUES (NULL, '$idTag', '$idDocument')";
        if($db->query($query))
        {
            return 0; //inserted
        }
        return 1; //not inserted
    }
    
    public static function insertTaggedByTagName($tag, $idDocument)
    {
        global $db;
        if(!Tag::existTagByName($tag))
        {
            Tag::insertTag($tag);
        }
        if(Tagged::existBindByName($tag, $idDocument) || !Tag::existTagByName($tag) || !Document::existDocument($idDocument))
        {
            return 1; // (already exist, or not exist tag, or not exist document) -> not inserted
        }
        $query = "INSERT INTO Tagged(id, idTag, idDocument) SELECT NULL, Tag.id, '$idDocument' FROM Tag WHERE Tag.name='$tag'";
        if($db->query($query))
        {
            if(Tagged::existBindByName($tag, $idDocument))
            {
                return 0;
            }
            return 1; //erased tag during insertion (?)
        }
        return 1; //not inserted
    }
       
    public static function eraseBind($idTag, $idDocument, $eraseTagIfLast = true)
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
            if($eraseTagIfLast) Tag::eraseTag($idTag);
        }
        if(!$deletedRows)
        {
            return 1; //not erased
        }
        return 0; //erased
    }
    
    public static function eraseAllDocumentBind($idDocument, $eraseTagIfLast = true)
    {
        global $db;
        $query = "SELECT * FROM Tagged  WHERE idDocument = '$idDocument'";
        $result  = $db->query($query);
        $finalResult = 0;
        while($row = mysqli_fetch_array($result))
        {
            var_dump($row);
            $finalResult += Tagged::eraseBind($row["idTag"], $row["idDocument"], $eraseTagIfLast);
        }
        return $finalResult; //if all deletion are ok, return 0;
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
    
    //list all tag connected to a singel document
    public static function getTagListByDocumentIn($documentId)
    {
        global $db;
        $query = "SELECT Tag.id AS id, Tag.name AS name, Tagged.idDocument, Tagged.idTag FROM Tagged, Tag WHERE Tagged.idTag = Tag.id AND Tagged.idDocument = '$documentId'";
        $result = $db->query($query);
        $result_array = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $result_array[$row["id"]] = array("id" => $row["id"], "name" => $row["name"]);
        }
        return $result_array;
    }
    public static function getTagListByDocumentIn1($documentId)
    {
        global $db;
        $query = "SELECT Tag.id AS id, Tag.name AS name, Tagged.idDocument, Tagged.idTag FROM Tagged, Tag WHERE Tagged.idTag = Tag.id AND Tagged.idDocument = '$documentId'";
        $result = $db->query($query);
        $result_array = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $result_array[$row["id"]] = array("id" => $row["id"], "name" => $row["name"]);
        }
        return json_encode($result_array);
    }
}
