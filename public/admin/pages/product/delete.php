<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 13.03.2017
 * Time: 18:51
 */

require_once "../../../../include/start.php"; 

$ID = filter_input(INPUT_GET, 'ID', FILTER_VALIDATE_INT); 

if(!$session->is_logged_in() || !User::checkRights($session->user_id, 'delete-product.php')) { 
    exit("Teil puuduvad õigused kustutamiseks"); 
} 

if(empty($ID)) { 
    exit("ID puudu!"); 
} 

$product = Product::find_by_ID($ID); 
if(empty($product)) { 
    exit("Toode puudu!"); 
} 

if(!$product->delete()) { 
    exit("Tekkis probleem kustutamisel!"); 
}