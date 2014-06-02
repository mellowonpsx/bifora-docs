<?php

/**
 * listCategory
 * @author mellowonpsx
 */

require_once "utils.php";
echo json_encode(Category::getCategoryList());