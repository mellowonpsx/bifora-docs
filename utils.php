<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of utils
 *
 * @author mellowonpsx
 */
function __autoload($classname)
{
    $filename =  strtolower("$classname.class.php");
    require_once($filename);
}
