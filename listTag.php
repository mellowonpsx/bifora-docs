<?php

require_once "utils.php";
$i=0;
$arr=Tag::getTagList();
$r=array();
foreach($arr as &$value){
    $r[$i]=$value;
    $i++;
}
echo json_encode($r);