<?php
$directory='./ul/';
if(move_uploaded_file($_FILES["file"]["tmp_name"], $directory.$_FILES["file"]["name"]))
    echo "success";
else {
    echo "pd";
}