<?php
/**
 * utils
 *
 * @author mellowonpsx
 */
function __autoload($classname)
{
    $filename =  strtolower("$classname.class.php");
    require_once($filename);
}

function escape($variables)
{
    return $variables;
}

// prepare configuration
$config = new Config();
// open database connection -> utils is require_once!!
$db = new DB();
