<?php
/**
 * Errors
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';

class Errors
{
    static public $ERROR_00 = "Error 00: user not logged";
    static public $ERROR_01 = "Error 01: user insufficient privileges";
    static public $ERROR_10 = "Error 10: impossible to update document";
    static public $ERROR_11 = "Error 11: document already exist";
    static public $ERROR_12 = "Error 12: document not exist";
    static public $ERROR_13 = "Error 13: category already exist/cannot insert category";
    static public $ERROR_14 = "Error 14: category not exist/cannot update category";
    static public $ERROR_15 = "Error 14: category not exist/category non empty/cannot remove category";
    static public $ERROR_20 = "Error 20: file not exist";
    static public $ERROR_21 = "Error 21: file not owned by user";
    static public $ERROR_22 = "Error 22: file not exist, already erased?";
    static public $ERROR_30 = "Error 30: error erasing tags";
    static public $ERROR_40 = "Error 40: error erasing categories";
    static public $ERROR_41 = "Error 41: at least one category needed";
    static public $ERROR_50 = "Error 50: temp key wrong, expired or already used";
    static public $ERROR_80 = "Error 80: database error ";
    static public $ERROR_90 = "Error 90: missing variabile";
}