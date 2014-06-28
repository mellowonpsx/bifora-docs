<?php
/**
 * listTag
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';

$tagSearchKey = NULL;
if(isset($_POST["tagSearchKey"]))
{
   $tagSearchKey = $db->escape(filter_var($_POST["tagSearchKey"], FILTER_SANITIZE_STRING));
}

echo json_ok(Tag::getTagList($tagSearchKey));
exit();