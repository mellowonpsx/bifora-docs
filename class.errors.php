<?php
/**
 * Errors
 *
 * @author mellowonpsx
 */

require_once "utils.php";

class Errors
{
    static public $ERROR_00 = "Error 00: user not logged";
    static public $ERROR_01 = "Error 01: user insufficient privileges";
    static public $ERROR_10 = "Error 10: impossible to update document";
    static public $ERROR_11 = "Error 11: document already exist";
    static public $ERROR_12 = "Error 12: document not exist";
    static public $ERROR_20 = "Error 20: file not exist";
    static public $ERROR_21 = "Error 21: file not owned by user";
    static public $ERROR_30 = "Error 30: error erasing tags";
    static public $ERROR_40 = "Error 40: error erasing categories";
    static public $ERROR_41 = "Error 41: at least one category needed";
    static public $ERROR_50 = "Error 50: temp key wrong or already used";
    static public $ERROR_51 = "Error 41: temp key expired";
    static public $ERROR_90 = "Error 90: missing variabile";
}