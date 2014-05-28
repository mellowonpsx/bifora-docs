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
