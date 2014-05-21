<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    require_once "utils.php";
    
    $errors = array();
    $data = array();

    if(empty($_POST['name']))
    {
        $errors['name'] = 'Name cannot be blank';
        $data['success'] = false;
        $data['errors']  = $errors;
    }
    else
    { //If not, process the form, and return true on success
        $data['success'] = true;
        $data['posted'] = 'Data Was Posted Successfully';
        $data['category'] = Category::getCategoryListInArray();
    }
    
    echo json_encode($data);
    exit();
?>
A problem with php server.