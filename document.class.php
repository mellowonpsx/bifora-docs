<?php
/**
 * Document
 *
 * @author mellowonpsx
 */

require_once "utils.php";

//status
define("BD_DOCUMENT_EMPTY", "BD_DOCUMENT_EMPTY");
define("BD_DOCUMENT_CHANGED", "BD_DOCUMENT_CHANGED");
define("BD_DOCUMENT_UNCHENGED", "BD_DOCUMENT_UNCHENGED");

//document type
define("BD_DOCUMENT_TYPE_UNKNOW", "UNKNOWN");
define("BD_DOCUMENT_TYPE_OTHER", "OTHER");
define("BD_DOCUMENT_TYPE_DOCUMENT", "DOCUMENT");
define("BD_DOCUMENT_TYPE_PHOTO", "PHOTO");
define("BD_DOCUMENT_TYPE_AUDIO", "AUDIO");
define("BD_DOCUMENT_TYPE_VIDEO", "VIDEO");
define("BD_DOCUMENT_TYPE_ARCHIVE", "ARCHIVE");

// the phylosophy behind this project expect to have already control and excaped variables
class Document
{
    private $id;
    private $title;
    private $filename;
    private $extension;
    private $description;
    private $type;
    private $date;
    private $isPrivate;
    private $ownerId;
    private $status;
    
    public function __construct($id = NULL)
    {
        if(!isset($id)) //create new empty on database document
        {
            $this->status = BD_DOCUMENT_EMPTY;
            global $db;
            $query = "INSERT INTO Document(id, title, filename, extension, description, type, date, isPrivate, ownerId) VALUES (NULL, NULL, NULL, NULL, NULL, NULL, CURRENT_TIMESTAMP, '0', NULL)";
            if(!$db->query($query))
            {
                return; //not inserted, why?
            }
            $id = $db->lastId(); //do the select
        }
        
        global $db;
        $query = "SELECT * FROM Document WHERE id = '$id'";
        $result = $db->query($query);
        // max one row cause id is unique
        $row = mysqli_fetch_assoc($result);
        if(!$row)
        {
            $this->status = BD_DOCUMENT_EMPTY;
            return;
        }
        $this->id = $row["id"];
        $this->title = $row["title"];
        $this->filename = $row["filename"];
        $this->extension = $row["extension"];
        $this->description = $row["description"];
        $this->type = $row["type"];
        $this->date = $row["date"];
        $this->isPrivate = $row["isPrivate"];
        $this->ownerId = $row["ownerId"];
        $this->status = BD_DOCUMENT_UNCHENGED;
        return;
    }
    
    public function __destruct()
    {
        if($this->updateDBValue())
        {
            //error
        }
    }
    
    public static function existDocument($idDocument)
    {                
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Document WHERE id = '$idDocument'";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return true; //exist
        }
        return false; //not exist
    }
    
    public function updateDBValue()
    {
        if(!isset($this->id))
        {
            return 0; // nothing to update
        }
        
        if($this->status != BD_DOCUMENT_CHANGED)
        {
            return 0; // nothing to update
        }
        
        global $db;
        
        $query = "UPDATE Document SET title = '$this->title', filename = '$this->filename', "
               . "extension = '$this->extension', description = '$this->description', type = '$this->type', "
               . "isPrivate = '$this->isPrivate', ownerId = '$this->ownerId' WHERE id = $this->id";
            
        if($db->query($query))
        {
            return 0; //value updated
        }
        
        return 1; //not updated
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    // use standard date format, return string
    public function getDate($format)
    {
        return date($format, strtotime($this->date));
    }

    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        //100
        $title = substr($title,0,100);
        $this->title = $title;
        $this->status = BD_DOCUMENT_CHANGED;
    }
    
    public function getFilename()
    {
        return $this->filename;
    }
    
    public function setFilename($filename)
    {
        //300
        $filename = substr($filename,0,300);
        $this->filename = $filename;
        $this->status = BD_DOCUMENT_CHANGED;
    }
    
    public function getExtension()
    {
        return $this->extension;
    }
    
    public function setExtension($extension)
    {
        //10
        $extension = substr($extension,0,10);
        $this->extension = $extension;
        $this->status = BD_DOCUMENT_CHANGED;
    }
    
    public function getDescription()
    {
        return $this->description;       
    }
    
    public function setDescription($description)
    {
        //1000
        $description = substr($description,0,1000);
        $this->description = $description;
        $this->status = BD_DOCUMENT_CHANGED;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        //50
        $type = substr($type,0,50);
        $this->type = $type;
        $this->status = BD_DOCUMENT_CHANGED;
    }
    
    public static function listDocumentType()
    {
        $documentType = array(BD_DOCUMENT_TYPE_UNKNOW, BD_DOCUMENT_TYPE_OTHER,
                              BD_DOCUMENT_TYPE_DOCUMENT, BD_DOCUMENT_TYPE_PHOTO,
                              BD_DOCUMENT_TYPE_AUDIO, BD_DOCUMENT_TYPE_VIDEO,
                              BD_DOCUMENT_TYPE_ARCHIVE);
        return $documentType;
    }
    
    public function getIsPrivate()
    {
        return $this->isPrivate;
    }
    
    public function setIsPrivate(bool $isPrivate)
    {
        $this->isPrivate = $isPrivate;
        $this->status = BD_DOCUMENT_CHANGED;
    }
    
    public function getOwnerId()
    {
        return $this->ownerId;
    }
    
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
        $this->status = BD_DOCUMENT_CHANGED;
    }
    
    public function setMultipleValues($title = NULL, $filename  = NULL, $extension  = NULL, $description  = NULL, $type  = NULL, $isPrivate  = NULL, $ownerId  = NULL)
    {
        if($title != NULL)
        {
            $this->setTitle($title);
        }
        if($filename != NULL)
        {
            $this->setFilename($filename);
        }
        if($extension != NULL)
        {
            $this->setExtension($extension);
        }
        if($description != NULL)
        {
            $this->setDescription($description);
        }
        if($type != NULL)
        {
            $this->setType($type);
        }
        if($isPrivate != NULL)
        {
            $this->setIsPrivate($isPrivate);
        }
        if($ownerId != NULL)
        {
            $this->setOwnerId($ownerId);
        }
    }
    
    public static function getDocumentNumber($showPrivate = false, $ownerId = NULL)
    {
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Document";
        if($showPrivate == false)
        {
            $query .= " WHERE isPrivate = false";
        }
        else
        {
            if($ownerId != NULL)
            {
                $query .= " WHERE isPrivate = false OR ownerId = '$ownerId'";
            }
        }           
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        return $elementNumber;
    }
    
    public static function getDocumentList($startLimit = 0, $endLimit = 0, $categoryListArray, $showPrivate = false, $ownerId = NULL, $yearLimit = NULL)
    {
        global $db, $config;
        $result_array = array();
        //$documentPerPage = $config->getParam(numRighe);
        //$startLimit = $page*$documentPerPage;
        //$endLimit = ($page+1)*$documentPerPage;
        //$elementNumber = Document::getDocumentNumber($showPrivate, $ownerId);
        //$result_array["documentNumber"] = $elementNumber;
        //$result_array["pageNumber"] = ceil($elementNumber/$documentPerPage);
        //$result_array["documentPerPage"] = $documentPerPage;
        // query start
        $categoryList = "(";
        foreach ($categoryListArray as $category)
        {
            $categoryList .= $category["id"].",";
        }
        $categoryList = rtrim($categoryList, ",");
        $categoryList .= ")";
        //SELECT Document.id as id, title, filename, extension, description, date, isPrivate, ownerId, Categorized.id as idCategorized, Categorized.idDocument, Categorized.idCategory FROM Document, Categorized WHERE Categorized.idDocument = Document.id and idCategory in ('0')
        $query = "SELECT Document.id as id, title, filename, extension, description, date, isPrivate, ownerId, "
               . "Categorized.id as idCategorized, Categorized.idDocument, Categorized.idCategory "
               . "FROM Document, Categorized WHERE Categorized.idDocument = Document.id and idCategory in $categoryList";
        if($showPrivate == false)
        {
            if($ownerId === NULL)
            {
                $query .= " AND isPrivate = false";
            }
            if($ownerId !== NULL)
            {
                $query .= " AND (isPrivate = false OR ownerId = '$ownerId')";
            }
        }
        if($yearLimit !== NULL)
        {
            $query .= " AND date > '$yearLimit-01-01 00:00:00'";
        }
        $query .= " GROUP BY Document.id ORDER BY date DESC";
        $query .= " LIMIT $startLimit,$endLimit";
        // query end;
        $result = $db->query($query);
        while($row = mysqli_fetch_assoc($result))
        {            
            $result_array[$row["id"]] = array("id" => $row["id"], "title" => $row["title"], "filename" => $row["filename"], "extension" => $row["extension"], "description" => $row["description"], "date" => $row["date"], "isPrivate" => $row["isPrivate"], "ownerId" => $row["ownerId"], "tags" => Tagged::getTagListByDocumentIn($row["id"]));
        }
        return $result_array;
    }
}
