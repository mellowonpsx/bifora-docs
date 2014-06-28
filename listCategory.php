<?php
/**
 * listCategory
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';
echo json_ok(Category::getCategoryList());
exit();