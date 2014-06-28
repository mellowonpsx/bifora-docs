<?php
/**
 * listTag
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';

// perché tagList non funziona semplicemente con questo?
/*echo json_encode(Tag::getTagList());
return*/
$i=0;
$arr=Tag::getTagList();
$r=array();
foreach($arr as &$value){
    $r[$i]=$value;
    $i++;
}
echo json_encode($r);