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

//default constrains
define("BD_DOCUMENT_DEFAULT_NAME", "new document");
define("BD_DOCUMENT_DEFAULT_TYPE", BD_DOCUMENT_TYPE_UNKNOW);

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
            $query = "INSERT INTO Document(id, title, filename, extension, description, type, date, isPrivate, ownerId) VALUES (NULL, NULL, NULL, NULL, NULL, NULL, CURRENT_TIMESTAMP, 'false', NULL)";
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
        $this->updateDBValue();
    }
    
    public function deleteDocument()
    {
        if(!isset($this->id))
        {
            return 0; // nothing to delete
        }
        global $db;
        $db->query("DELETE FROM Document WHERE id = '$this->id'");
        $this->status = BD_DOCUMENT_EMPTY;
        Tagged::eraseAllDocumentBind($this->id);
        Categorized::eraseAllDocumentBind($this->id);
        return;
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
    
    public static function existDocumentByFilename($filename)
    {                
        global $db;
        $query = "SELECT COUNT(*) AS elementNumber FROM Document WHERE filename = '$filename'";
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
    
    public static function isDocumentType($typeToCheck)
    {
        return in_array($typeToCheck, Document::listDocumentType());
    }
    
    public function getIsPrivate()
    {
        return $this->isPrivate;
    }
    
    public function setIsPrivate($isPrivate)
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
        if($title !== NULL)
        {
            $this->setTitle($title);
        }
        if($filename !== NULL)
        {
            $this->setFilename($filename);
        }
        if($extension !== NULL)
        {
            $this->setExtension($extension);
        }
        if($description !== NULL)
        {
            $this->setDescription($description);
        }
        if($type !== NULL)
        {
            $this->setType($type);
        }
        if($isPrivate !== NULL)
        {
            $this->setIsPrivate($isPrivate);
        }
        if($ownerId !== NULL)
        {
            $this->setOwnerId($ownerId);
        }
    }
    
    public static function getDocumentList($startLimit = 0, $endLimit = 0, $categoryListArray, $showPrivate = false, $ownerId = NULL, $yearLimit = NULL, $searchKey = NULL)
    {
        global $db, $config;
        $result_array = array();
        $result_list_array = array();
        // query start
        if(!empty($categoryListArray))
        {
            $categoryList = "(";
            foreach ($categoryListArray as $category)
            {
                $categoryList .= $category["id"].",";
            }
            $categoryList = rtrim($categoryList, ",");
            $categoryList .= ")";
        }
        else $categoryList = "(NULL)";
        //SELECT Document.id as id, title, filename, extension, description, date, isPrivate, ownerId, Categorized.id as idCategorized, Categorized.idDocument, Categorized.idCategory FROM Document, Categorized WHERE Categorized.idDocument = Document.id and idCategory in ('0')
        $query = "SELECT SQL_CALC_FOUND_ROWS Document.id as id, title, filename, extension, description, date, isPrivate, ownerId, "
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
        
        if ($searchKey != NULL)
        {
            //substring su più key
            $query .= " AND (";
            $searchKeyArray = explode(" ", $searchKey);
            $k=0;
            foreach ($searchKeyArray as $thisSearchKey)
            {
                $thisSearchKey = trim(strtolower($thisSearchKey));
                $thisSearchKey = wordwrap($thisSearchKey, 1, "%"); //utile o no?
                $thisSearchKey = trim($thisSearchKey, "%");
                if($k++ != 0) $query .= " AND ";
                $query .= "(title LIKE '%$thisSearchKey%' OR description LIKE '%$thisSearchKey%')";
            }
            $query .= ")";
        }
        
        if($yearLimit !== NULL)
        {
            $query .= " AND date > '$yearLimit-01-01 00:00:00'";
        }
        $query .= " GROUP BY Document.id ORDER BY date DESC";
        $query .= " LIMIT $startLimit,$endLimit";
        $query .= "; SELECT FOUND_ROWS() as numRow;";
        // query end;
        $i = 0;
        if (!$db->multi_query($query))
        {
            //return "Multi query failed: (".$db->db->errno.") ".$db->db->error;
            $result_array["numberOfDocument"] = 0;
            $result_array["documentPerPage"] = $config->getParam("documentPerPage");
            $result_array["documentList"] = "";
            return $result_array;
        }
        //else
        do
        {
            if($result = $db->store_result())
            {
                while($row = mysqli_fetch_assoc($result))
                {
                    if(isset($row["numRow"]))
                    {
                        $numberOfDocument = $row["numRow"];
                    }
                    else
                    {
                        // spostato rimepimento tags
                        $owned = false;
                        if($ownerId == $row["ownerId"])
                        {
                            $owned = true;
                        }
                        $result_list_array[$i++] = array("id" => $row["id"], "title" => $row["title"], "filename" => $row["filename"], "extension" => $row["extension"], "description" => $row["description"], "date" => $row["date"], "isPrivate" => $row["isPrivate"], "ownerId" => $row["ownerId"], "owned" => $owned, "tags" => "");
                    }
                }
            }
        }while($db->next_result());
        
        foreach ($result_list_array as &$thisElement)
        {
            $thisElement["tags"] = Tagged::getTagListByDocumentIn($thisElement["id"]);
        }
        
        $result_array["numberOfDocument"] = $numberOfDocument;
        $result_array["documentPerPage"] = $config->getParam("documentPerPage");
        $result_array["documentList"] = $result_list_array;
        return $result_array;
    }
}
