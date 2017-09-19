<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 9.03.2017
 * Time: 14:18
 */


require_once "../../../../include/start.php";

$name = filter_input(INPUT_POST, 's', FILTER_SANITIZE_STRING);
$pageNr = filter_input(INPUT_GET, 'pageNr', FILTER_VALIDATE_INT);
$next = $pageNr+1;
$previous = $pageNr-1;

if(empty($pageNr)) {
    $pageNrInDb = 0;
    echo "PageNr==0";
} else {
    $pageNrInDb = $pageNr * MAX_CATEGORIES;
    echo "Hello";
}
if(empty($name)) {
    $products = Product::findAll($pageNrInDb, MAX_CATEGORIES);
} else {
    $products = Product::find_by_name($name);
}

//$product = Product::findAll($pageNrInDb, MAX_CATEGORIES);

$countCategories = Product::count_all();

$pagesCount = ceil( $countCategories / MAX_CATEGORIES);

?>

<?php if (!empty($products)) : ?>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nimi</th>
            <th>Lisatud</th>
            <th>Kategooria</th>
            <th>Galerii</th>
            <th>Vaata</th>
            <th>Muuda</th>
            <th>Kustuta</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product) : ?>
            <tr>
                <td><?php echo $product->ID ?></td>
                <td><?php echo $product->name ?></td>
                <td><?php echo $product->added ?></td>
                <td>
                    <?php

                        $category_name = Category::find_by_ID2($product->category_id);

                        $id_name=explode(',', $product->category_id);

                        foreach ($id_name as $key => $value) {
                            if($key!=0){
                                echo ", ";
                            }
                            echo empty($id_name)?'Põhikategooria':$category_name[$key]->name;
                        }
                        
                    ?>
                </td>
                <td>
                    <a href="<?php echo MAIN_URL . "?page=product-view&ID=" . $product->ID; ?>">
                        <span class="glyphicon glyphicon-globe"></span>
                    </a>
                </td>
                <td>
                    <a href="<?php echo ADMIN_URL . "?page=pictures&ID=" . $product->ID; ?>">
                        <span class="glyphicon glyphicon-picture"></span>
                    </a>
                </td>
                <td>
                    <a href="<?php echo ADMIN_URL . "?page=product&ID=" . $product->ID; ?>">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                </td>
                <td>
                <span
                        data-url="pages/product/delete"
                        data-delete-id="<?php echo $product->ID; ?>"
                        class="glyphicon glyphicon-trash bg-danger delete-confirm"
                        style="cursor: pointer;"></span>
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
<script src="<?php echo TEMPLATE_URL; ?>js/custom.js"></script>