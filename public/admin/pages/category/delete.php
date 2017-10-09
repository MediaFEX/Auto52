<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 9.03.2017
 * Time: 13:38
 */


require_once "../../../../include/start.php";  //Gets assets and starts session

if(!$session->is_logged_in() || !User::checkRights($session->user_id, 'categories.php')) { //Repels users and moderators
    $session->message('<div class="alert alert-danger">You don\'t have the rights to view that page.</div>');//If they don't have access throw them out.
    reDirectTo(ADMIN_URL . '?page=home');
}

//Variables
$ID = filter_input(INPUT_GET, 'ID', FILTER_VALIDATE_INT);

$category=invalidAccess($ID, $session, array('admin'));
//Checks if the user has enough rights

if(empty($ID)) { //Checks if the ID exists
    exit("ID puudu!");
}

$product = Category::find_by_ID($ID); //Checks if the category exists
if(empty($product)) { 
    exit("Kategooria puudu!"); 
} 

$parent = Category::find_parent($ID); //Looks for its child
if($parent) { //If it has a child, don't allow deletion and ask for the childs deletion first.
    exit('Kustuta alamkategooriad ennem ära!'); 
    reDirectTo(ADMIN_URL . '?page=categories'); 
}
if(!$product->delete()) { //Delete, if you can't, exit and relay to the user.
    exit("Tekkis probleem kustutamisel!");
}