<?php
if(!defined('MAIN_PATH')) { 
    header("Location: /"); 
    exit(); 
} 
print_r($_SESSION['rights']);
$pageNr = filter_input(INPUT_GET, 'pageNr', FILTER_VALIDATE_INT); 
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
<div class="row">
    <div class="col-sm-8">
    </div>
    <div class="col-sm-4">
        <h3 class="text-right"><?php echo $pages[$page]['name'] ?></h3>
    </div>
</div>
<?php


$args = [ 
    'categories'  => [ 
        'filter' => FILTER_SANITIZE_STRING, 
        'flags'  => FILTER_REQUIRE_ARRAY, 
    ], 
    'action'      => FILTER_SANITIZE_STRING 
]; 

$categories = filter_input_array(INPUT_POST, $args); 

if(isset($categories['action']) && $categories['action'] == 'search') { 
    $category_ids = array_unique($categories['categories']); 
    pd($category_ids); 
    $category_ids = join(",", $category_ids); 

    $products = Product::find_by_category($category_ids); 
} else { 
    $products = Product::findAll($pageNrInDb, MAX_CATEGORIES);
} 


$categorys = Category::find_all(); 
$categorys = createCategoryArray($categorys); 

?> 
<div class="container"> 
    <div class="row"> 
        <div class="col-sm-3"> 
            <form method="post"> 
                <?php if(!empty($categorys[0])) : foreach ($categorys[0] as $category) : ?> 
                    <input type="hidden" value="<?php echo $category->ID; ?>" name="categories[]"> 
                    <h3><?php echo $category->name; ?></h3> 
                    <?php if(!empty($categorys[$category->ID])) : ?> 
                        <select name="categories[]"> 
                            <option value="0"><?php echo translate("select") ?></option> 
                            <?php foreach ($categorys[$category->ID] as $subCategory) : ?> 
                                <option value="<?php echo $subCategory->ID; ?>"><?php echo $subCategory->name; ?></option> 
                            <?php endforeach; ?> 
                        </select> 
                    <?php endif; ?> 
                <?php endforeach; endif; ?> 
                <hr> 
                <button class="btn btn-primary" type="submit" name="action" value="search">Search</button> 
            </form> 
        </div> 
        <div class="col-sm-9"> 
            <?php //require_once ADMIN_URL . $pages[$page]['path']; ?>
        </div>              
    </div> 
</div>





<div class="row"> 
    <div id="grid" > 
        <?php if(!empty($products)) : foreach ($products as $product) : ?> 
            <div> 
                <div class="thumbnail"> 
                    <?php if (empty($product->main_picture)) : ?> 
                        <img src="<?php echo MAIN_URL; ?>template/img/no-image-icon-24.jpg" class="img-responsive" width="200"> 
                    <?php else: ?> 
                        <?php $picture = Picture::getPictureByNameAndProduct($product->main_picture, $product->ID); ?> 
                        <img src="<?php echo makePictureLink($picture) . PICTURE_THUMB . '/' . $picture->name; ?>" class="img-responsive"> 
                    <?php endif; ?> 

                    <div class="caption"> 
                        <h3><?php echo $product->name; ?></h3> 
                        <div class="row"> 
                            <div class="col-xs-6"><?php echo $product->showPrice(); ?></div> 
                            <div class="col-xs-6"> 
                                <a class="btn btn-default pull-right" href="<?php echo ADMIN_URL . "?page=product-view&ID=" . $product->ID; ?>" ><?php echo translate("info") ?></a> 
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div> 
        <?php endforeach; else: ?>


            <?php echo infoMessage('info', 'Tooted puuduvad'); ?> 

        <?php endif; ?> 
    </div>

</div>
<ul class="pager"> 
    <?php if(!empty($pageNr)) : ?> 
        <?php if($pageNr == 1) : ?> 
            <li><a href="<?php echo ADMIN_URL . "?page=home&pageNr=".$previous; ?>"><?php echo translate("previous_btn") ?></a></li> 
        <?php else: ?> 
            <li><a href="<?php echo ADMIN_URL . "?page=home&pageNr=" . $previous; ?>"><?php echo translate("previous_btn") ?></a></li> 
        <?php endif; ?> 
    <?php endif; ?> 
    <?php if($pagesCount-1 > $pageNr ) : ?>
        <li><a href="<?php echo ADMIN_URL . "?page=home&pageNr=" . $next; ?>"><?php echo translate("next_btn") ?></a></li> 
    <?php endif; ?> 
</ul> 