<?php

/**
 * logout
 * @author mellowonpsx
 */

require_once "utils.php";

removeSession();
$response = array("status" => user::logoutStatus());
$json_response = json_encode($response);
echo $json_response;