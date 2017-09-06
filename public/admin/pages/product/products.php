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

$pageNr = filter_input(INPUT_GET, 'pageNr', FILTER_VALIDATE_INT);
$next = $pageNr+1;
$previous = $pageNr-1;

if(empty($pageNr)) {
    $pageNrInDb = 0;
} else {
    $pageNrInDb = $pageNr * MAX_CATEGORIES;
}

$product = Product::findAll($pageNrInDb, MAX_CATEGORIES);

$countProducts = Product::count_all();

$pagesCount = ceil( 8 / MAX_CATEGORIES);
?>
    <div class="row">
        <div class="col-sm-4">
            <h3><a href="<?php echo ADMIN_URL . "?page=product"; ?>"><span class="glyphicon glyphicon-plus-sign"></span> Lisa</a></h3>
        </div>
        <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="<?php echo translate('search_placeholder'); ?>" id="search-field">
        </div>
        <div class="col-sm-4">
            <h3 class="text-right"><?php echo $pages[$page]['name']?></h3>
        </div>
    </div>

<?php /*
<div class="row">
    <div class="col-lg-12">
        <?php echo isset($session->message) ? $session->message : '' ?>
        <div id="product-content"></div>
    </div>
</div>
*/
if (!empty($product)) : ?>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nimi</th>
            <th>Lisatud</th>
            <th>Vanem</th>
            <th>Muuda</th>
            <th>Kustuta</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($product as $cat) : ?>
            <tr>
                <td><?php echo $cat->ID?></td>
                <td><?php echo $cat->name?></td>
                <td><?php echo $cat->added?></td>
                <td>
                    <?php $category_name = Category::find_by_ID($cat->category_id); ?>
                    <?php echo empty($category_name)?'Põhikategooria':$category_name->name;?>
                </td>
                <td>
                    <a href="<?php echo ADMIN_URL . "?page=product&ID=" . $cat->ID; ?>">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                </td>
                <td>
                    <a href="<?php echo ADMIN_URL . "?page=delete&ID=" . $cat->ID; ?>">
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <ul class="pager">
        <?php if(!empty($pageNr)) : ?>
            <?php if($pageNr == 1) : ?>
                <li><a href="<?php echo ADMIN_URL . "?page=products"; ?>"><?php echo translate("previous_btn") ?></a></li>
            <?php else: ?>
                <li><a href="<?php echo ADMIN_URL . "?page=products&pageNr=" . $previous; ?>"><?php echo translate("previous_btn") ?></a></li>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($pagesCount-1 > $pageNr ) : ?>
            <li><a href="<?php echo ADMIN_URL . "?page=products&pageNr=" . $next; ?>"><?php echo translate("next_btn") ?></a></li>
        <?php endif; ?>
    </ul>
<?php else :
    echo infoMessage('info', 'Tooted puuduvad');
endif; ?>