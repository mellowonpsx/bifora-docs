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

function getSessionUser()
{
    $sessionId = session_id(); 
    if(empty($sessionId))
    {
        session_start() or die("Could not start session");
    }
    if(isset($_SESSION["user"]))
    {
        $user = $_SESSION["user"];
    }
    else
    {
        $user = new User();
    }
    return $user;
}

function setSessionUser($user)
{
    $sessionId = session_id(); 
    if(empty($sessionId))
    {
        session_start() or die("Could not start session");
    }
    $_SESSION["user"] = $user;
}

function removeSession()
{
    $sessionId = session_id(); 
    if(empty($sessionId))
    {
        session_start() or die("Could not start session");
    }
    $_SESSION["user"] = NULL;
    session_destroy();
}

// prepare configuration
$config = new Config();
// open database connection -> utils is require_once!!
$db = new DB();