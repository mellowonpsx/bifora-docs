<?php
/**
 * TempKey
 *
 * @author mellowonpsx
 */

require_once "utils.php";

// the phylosophy behind this project expect to have already control and excaped variables
class TempKey
{
    public static function generateTempKey($userId, $duration)
    {
        //generate temp key
        $tempKey = md5(uniqid(rand(), true));
        global $db;
        //erase expired tempKey
        TempKey::purgeExpiredKey();
        //insert generated tempKey
        $query = "INSERT INTO TempKey (idUser, tempKey, expiration) VALUES ('$userId', '$tempKey', TIMESTAMPADD (SQL_TSI_SECOND, $duration, CURRENT_TIMESTAMP));";
        if($db->query($query))
        {
            return $tempKey; //ok
        }
        return null; //something bad happend
    }
    
    public static function existTempKey($tempKey, $userId)
    {
        global $db;
        //erase expired tempKey
        TempKey::purgeExpiredKey();
        //check if exist
        $query = "SELECT COUNT(*) AS elementNumber FROM TempKey WHERE tempKey = '$tempKey' AND idUser = '$userId'";
        $result = $db->query($query);
        $row = mysqli_fetch_assoc($result);
        $elementNumber = $row["elementNumber"];
        if($elementNumber > 0)
        {
            return true; //exist
        }
        return false; //not exist
    }
    
    public static function purgeExpiredKey()
    {
        global $db;
        $db->query("DELETE FROM TempKey WHERE expiration < CURRENT_TIMESTAMP");
    }
    
    public static function isValidTempKey($tempKey, $userId)
    {
        //erase expired tempKey
        TempKey::purgeExpiredKey();
        return TempKey::existTempKey($tempKey, $userId);
    }
    
    public static function useTempKey($tempKey, $userId)
    {
        //evaluate the TempKey
        $isValid = TempKey::isValidTempKey($tempKey, $userId);
        //make TempKey expire
        global $db;
        $db->query("UPDATE TempKey SET expiration = CURRENT_TIMESTAMP WHERE tempKey = '$tempKey' AND idUser = '$userId'");
        //erase expired tempKey
        TempKey::purgeExpiredKey();
        //return result
        return $isValid;
    }
}
