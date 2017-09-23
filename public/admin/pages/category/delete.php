<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 9.03.2017
 * Time: 13:38
 */


require_once "../../../../include/start.php"; 

$ID = filter_input(INPUT_GET, 'ID', FILTER_VALIDATE_INT); 

if(!$session->is_logged_in() || !User::checkRights($session->user_id, 'delete.php')) { 
    exit("Teil puuduvad õigused kustutamiseks"); 
} 

if(empty($ID)) { 
    exit("ID puudu!"); 
} 

$product = Category::find_by_ID($ID); 
if(empty($product)) { 
    exit("Kategooria puudu!"); 
} 

$parent = Category::find_parent($ID); 
if($parent) { 
    exit('Kustuta alamkategooriad ennem ära!'); 
    reDirectTo(ADMIN_URL . '?page=categories'); 
}
if(!$product->delete()) { 
    exit("Tekkis probleem kustutamisel!");
}