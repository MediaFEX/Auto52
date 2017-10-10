<?php 
/** 
 * Created by PhpStorm. 
 * User: andrus.jakobson
 * Date: 16.09.2017 
 * Time: 12:43
 */ 


if(!defined('MAIN_PATH')) { 
    header("Location: /"); 
    exit(); 
} 

$ID = filter_input(INPUT_GET, 'ID', FILTER_VALIDATE_INT); 
if(!empty($ID)) { 
    $product = Product::find_by_ID($ID); 

    if(empty($product)) { 
        $session->message('<div class="alert alert-danger">Toode puudub</div>'); 
        reDirectTo(ADMIN_URL . '?page=products'); 
    } 
} 

$pictures = Picture::getPicturesByProduct($product->ID); 

$translations=translationGiver($product);

?> 
<h3 class="page-header text-right"><?php echo $pages[$page]['name'] ?></h3> 

<h1 class="page-header"><?php echo ProductLanguage::translate('name', $product, $translations) ?> <small><?php echo $product->price ?>€</small></h1> 

<ul> 
    <?php 
$category_name = Category::find_by_ID2($product->category_id);
echo '<ul>';
    foreach ($category_name as $key => $value) {
        echo '<li>';
            echo empty($category_name)?'Põhikategooria':$category_name[$key]->$categoryLang;
        echo '</li>';
    }
    ?>
    <li><?php echo ProductLanguage::translate('description', $product, $translations) ?></li> 
    <li><?php echo date("d.m.Y", strtotime($product->added)); ?></li> 
    <li><?php echo User::find_by_ID($product->added_by)->username; ?></li> 
</ul> 

<?php if (!empty($pictures)) : foreach ($pictures as $key => $pic) : ?> 
    <?php echo $key % 3 == 0 ? '<div class="clearfix"></div><br>' : '' ?> 
    <div class="col-xs-4"> 
        <img src="<?php echo makePictureLink($pic) . DS . PICTURE_THUMB . DS . $pic->name; ?>" class="img-responsive"> 
    </div> 
<?php endforeach; endif; ?> 










<?php
/*
if(!defined('MAIN_PATH')) { 
    header("Location: /"); 
    exit(); 
} 
//Variables
$ID = filter_input(INPUT_GET, 'ID', FILTER_VALIDATE_INT); 
if(!empty($ID)) {//If product Id isn't empty 
    $product = Product::find_by_ID($ID); //Look for the product by its ID

    if(empty($product)) { //If there is not product
        $session->message('<div class="alert alert-danger">Toode puudub</div>');//Put into message which is in session memory 
        reDirectTo(ADMIN_URL . '?page=home'); //Redirect to home page
    } 
}else{//If ID is empty
    $session->message('<div class="alert alert-danger">Toode puudub</div>');
    reDirectTo(ADMIN_URL . '?page=home');
}

$pictures = Picture::getPicturesByProduct($product->ID);
$translates = ProductLanguage::findByProductId($product->ID, LANG);

if(!empty($translates)) {
    $translations = (object) array_column($translates, 'column_value', 'table_column');
} else {
    $translations = null;
}

?> 

<link href="http://ubuntu.ametikool.ee/~TAK15_Jakobson/BackupAuto52/public/template/css/style.css" rel="stylesheet">

<h3 class="page-header text-right"><?php echo $pages[$page]['name'] ?></h3> 

<h1 class="page-header"><?php echo ProductLanguage::translate('name', $product, $translations) ?> <small><?php echo $product->price ?>€</small></h1>

<?php
$category_name = Category::find_by_ID2($product->category_id);
echo '<ul>';
    foreach ($category_name as $key => $value) {
        echo '<li>';
            echo empty($category_name)?'Põhikategooria':$category_name[$key]->$categoryLang;
        echo '</li>';
    }
?>
    <li><?php echo ProductLanguage::translate('description', $product, $translations) ?></li> 
    <li><?php echo date("d.m.Y", strtotime($product->added)); ?></li> 
    <li><?php echo User::find_by_ID($product->added_by)->username; ?></li> 
</ul> 

<div class="container">
    <h1 class="my-4 text-center text-lg-left">Gallery</h1>
    <div  class="row text-center text-lg-left"> </div>


        <?php if (!empty($pictures)) : foreach ($pictures as $key => $pic) : ?> 
                <?php
                    if($key==0){
                        echo '<img src="'.makePictureLink($pic) . $pic->name    .'" class="img-thumbnail"> ';
                    }else{
                        echo '<div class="col-lg-2 col-md-4 col-xs-6">';
                            echo '<img src="'.makePictureLink($pic) . DS . PICTURE_THUMB . DS . $pic->name.'" class="img-fluid img-thumbnail fixedSize">';
                        echo '</div>';
                    }
                ?>
        <?php endforeach; endif; ?> 


    </div>
</div>


<?php /*





*/