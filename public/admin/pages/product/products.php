<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 9.03.2017
 * Time: 14:18
 */
if(!defined('MAIN_PATH')) { 
    header("Location: /"); 
    exit(); 
} 

?> 
<div class="row"> 
    <div class="col-sm-4"> 
        <h3><a href="<?php echo ADMIN_URL . "?page=product"; ?>"><span class="glyphicon glyphicon-plus-sign"></span> Lisa</a></h3> 
    </div> 
    <div class="col-sm-4"> 
        <input type="text" class="form-control" placeholder="<?php echo translate('search_placeholder'); ?>" id="search-field"> 
    </div> 
    <div class="col-sm-4"> 
        <h3 class="text-right"><?php echo $pages[$page]['name'] ?></h3> 
    </div> 
</div> 


<div class="row"> 
    <div class="col-lg-12"> 
        <?php echo isset($session->message) ? $session->message : '' ?> 
        <div id="product-content"></div> 
    </div> 
</div> 