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
   $tempTagSearchKey = $db->escape(filter_var($_POST["tagSearchKey"], FILTER_SANITIZE_STRING));
   if(strrchr($tempTagSearchKey, " "))
   {
        $tagSearchKey = strrchr($tempTagSearchKey, " ");
   }
   else
   {
       $tagSearchKey=$tempTagSearchKey;
   }
}

echo json_ok(Tag::getTagList($tagSearchKey));
exit();