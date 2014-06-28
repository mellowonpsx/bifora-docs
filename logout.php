<?php
/**
 * logout
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';

removeSession();
//$response = array("status" => user::logoutStatus());
//$json_response = json_encode($response);
//echo $json_response;
echo json_ok();
exit();
