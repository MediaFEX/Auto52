<?php
if(!defined('MAIN_PATH')) { 
    header("Location: /"); 
    exit(); 
}
//Variables
$pageNr = filter_input(INPUT_GET, 'pageNr', FILTER_VALIDATE_INT); 
$next = $pageNr+1; 
$previous = $pageNr-1;
if(empty($pageNr)) {//If pageNr is empty, give 0 otherwise begin looking from pageNr 
    $pageNrInDb = 0;
} else {
    $pageNrInDb = $pageNr * MAX_CATEGORIES;
}
$countCategories = Product::count_all();//Count all products

$pagesCount = ceil( $countCategories / MAX_CATEGORIES); //Finds out how many pages are needed.
?>
<div class="row">
    <div class="col-sm-8">
    </div>
    <div class="col-sm-4">
        <h3 class="text-right"><?php echo $pages[$page]['name'] //Echoes pages name?></h3>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <?php echo isset($session->message) ? $session->message : '' //Display current session message?>
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

if(isset($categories['action']) && $categories['action'] == 'search') { //If button is pressed and button value is 'search'
    $category_ids = array_unique($categories['categories']); //Remove dublicate values
    //pd($category_ids); 
    $category_ids = join(",", $category_ids); //Implodes category ids and creates a single string.

    $products = Product::find_by_category($category_ids); //Find with the Id's we're loocking for
} else { 
    $products = Product::findAll($pageNrInDb, MAX_CATEGORIES);//Otherwise just give all.
} 


$categorys = Category::find_all(); //Find all categories
$categorys = createCategoryArray($categorys); //Creates array for Category

?> 
<div class="container"> 
    <div class="row"> 
        <div class="col-sm-3"> 
            <form method="post"> 
                <?php if(!empty($categorys[0])) : foreach ($categorys[0] as $category) : //If the first element isn't?> 
                    <input type="hidden" value="<?php echo $category->ID; ?>" name="categories[]"> 
                    <h3><?php echo $category->$categoryLang; //Then echo category in the users language?></h3>
                    <?php if(!empty($categorys[$category->ID])) : ?> 
                        <select name="categories[]"> 
                            <option value="0"><?php echo translate("select") //First select option is neutral?></option> 
                            <?php foreach ($categorys[$category->ID] as $subCategory) : //Look for subcategories?> 
                                <option value="<?php echo $subCategory->ID; ?>"><?php echo $subCategory->$categoryLang; //If sub categories exist, ID as value and echo it.?></option>
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
        <?php if(!empty($products)) : foreach ($products as $product) : //If products aren't empty?> 
            <div> 
                <div class="thumbnail"> 
                    <?php if (empty($product->main_picture)) : //If product doesn't have a thumbnail?> 
                        <img src="<?php echo MAIN_URL; ?>template/img/no-image-icon-24.jpg" class="img-responsive" width="200"> 
                    <?php else: ?>
                        <?php $picture = Picture::getPictureByNameAndProduct($product->main_picture, $product->ID); //If it does, show it.?>
                        <img src="<?php echo makePictureLink($picture) . PICTURE_THUMB . '/' . $picture->name; ?>" class="img-responsive">
                    <?php endif; ?>

                    <div class="caption">
                    <?php $translations=translationGiver($product); ?>
                        <h3><?php echo ProductLanguage::translate('name', $products, $translations)//Display product name?></h3>
                        <div class="row">
                            <div class="col-xs-6"><?php echo $product->showPrice(); //Put an euro symbol at the end?></div>
                            <div class="col-xs-6"> 
                                <a class="btn btn-default pull-right" href="<?php echo ADMIN_URL . "?page=product-view&ID=" . $product->ID; ?>" ><?php echo translate("info") //More info button?></a> 
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div> 
        <?php endforeach; else: ?>


            <?php echo infoMessage('info', 'Tooted puuduvad'); //Nothing to show?> 

        <?php endif; ?> 
    </div>

</div>
<?php pager($pageNr, $pagesCount, $next, $previous, 'home'); //Calls for next page and previous?>