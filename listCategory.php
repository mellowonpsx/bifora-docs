<?php
/**
 * listCategory
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';
echo json_encode(Category::getCategoryList());