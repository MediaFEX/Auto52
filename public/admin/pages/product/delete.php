<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 13.03.2017
 * Time: 18:51
 */


if(!defined('MAIN_PATH')) { 
    header("Location: /"); 
    exit(); 
} 

$ID = filter_input(INPUT_GET, 'ID', FILTER_VALIDATE_INT); 
$btn = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING); 

if(isset($btn) && $btn == 'delete') { 
    $catID = filter_input(INPUT_POST, 'ID', FILTER_VALIDATE_INT); 

    $category = Product::find_by_ID($catID); 

    if(empty($category)) { 
        $session->message('<div class="alert alert-danger">Kategooria puudub</div>'); 
        reDirectTo(ADMIN_URL . '?page=products'); 
    } 

    if($category->delete()) { 
        $session->message('<div class="alert alert-success">Kategoori kustutati</div>'); 
        reDirectTo(ADMIN_URL . '?page=products');
    } 

    $session->message('<div class="alert alert-success">Kategoorit ei kustutatud</div>'); 
    reDirectTo(ADMIN_URL . '?page=products'); 
} 

if(empty($ID)) { 
    $session->message('<div class="alert alert-danger">Kategooria puudub</div>'); 
    reDirectTo(ADMIN_URL . '?page=products'); 
} 

$category = Product::find_by_ID($ID); 

if(empty($category)) { 
    $session->message('<div class="alert alert-danger">Kategooria puudub</div>'); 
    reDirectTo(ADMIN_URL . '?page=products'); 
} 

$parent = Product::find_parent($category->ID); 

if($parent) { 
    $session->message('<div class="alert alert-danger">Kustuta alamkategooriad ennem ära!</div>'); 
    reDirectTo(ADMIN_URL . '?page=products'); 
} 
?> 

<h1>Kas olete kindel, et tahate kustutada?</h1> 
<h3><?php echo $category->name; ?></h3> 
<h3><?php echo $category->added; ?></h3> 
<h3><?php echo $category->status == 1 ? 'aktiivne' : 'mitteaktiivne'; ?></h3> 
<form method="post"> 
    <input type="hidden" name="ID" value="<?php echo $category->ID; ?>"> 
    <button class="btn btn-danger" type="submit" value="delete" name="action">Kinnitan kustutamise!</button> 
</form> 





































<?php


/*require_once "../../../../include/start.php"; 

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
*/

?>