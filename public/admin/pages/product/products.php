<?php 
/** 
 * Created by PhpStorm. 
 * User: janek.mander 
 * Date: 6.03.2017 
 * Time: 11:11 
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
<?php
//require_once('search.php');


$pageNr = filter_input(INPUT_GET, 'pageNr', FILTER_VALIDATE_INT);
if(!empty($pageNr)){
    $_SESSION['pageNr']=$pageNr;
}else{
    $_SESSION['pageNr']='empty';
}

$next = $pageNr+1;
$previous = $pageNr-1;

if(empty($pageNr)) {
    $pageNrInDb = 0;
} else {
    $pageNrInDb = $pageNr * MAX_CATEGORIES;
}

$countCategories = Product::count_all();

$pagesCount = ceil( $countCategories / MAX_CATEGORIES);

?>
    <ul class="pager">
        <?php if(!empty($pageNr)) : ?>
            <?php if($pageNr == 1) : ?>
                <li><a id="previous" value="<?php echo $previous; ?>" href="<?php echo ADMIN_URL . "?page=products"; ?>"><?php echo translate("previous_btn") ?></a></li>
            <?php else: ?>
                <li><a id="previous" value="<?php echo $previous; ?>" href="<?php echo ADMIN_URL . "?page=products&pageNr=" . $previous; ?>"><?php echo translate("previous_btn") ?></a></li>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($pagesCount-1 > $pageNr ) : ?>
            <?php /*<li><a id="next" value="<?php echo $next; ?>" href="<?php echo ADMIN_URL . "?page=products&pageNr=" . $next; ?>"><?php echo translate("next_btn") ?></a></li> */?>
            <li><a id="next" value="<?php echo $next; ?>" href="<?php echo ADMIN_URL . "?page=products&pageNr=" . $next; ?>" ><?php echo translate("next_btn") ?></a></li>
        <?php endif; ?>
    </ul>

<script src="<?php echo TEMPLATE_URL; ?>js/custom.js"></script>