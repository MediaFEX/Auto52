<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 7.04.2017
 * Time: 10:32
 */

require_once "../../../../include/start.php"; 

$ID = filter_input(INPUT_POST, 'ID', FILTER_VALIDATE_INT); 
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING); 

if(empty($ID) || empty($name)) { 
    exit("Data missing!"); 
} 

$product = Product::find_by_ID($ID); 
$product->main_picture = $name; 

if(!$product->save()) { 
    echo translate("main_picture_save_error"); 
}