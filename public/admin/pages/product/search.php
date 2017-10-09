<?php 
/** 
 * Created by PhpStorm. 
 * User: andrus.jakobson
 * Date: 28.03.2017 
 * Time: 9:46 
 */ 
require_once "../../../../include/start.php"; 

$ID=$_SESSION['user_id'];

$name = filter_input(INPUT_POST, 's', FILTER_SANITIZE_STRING); 

$pageNr=$_SESSION['pageNr'];
if($pageNr=='empty'){
    $pageNrInDb = 0;
} else {
    $pageNrInDb = $pageNr * MAX_CATEGORIES;
}



if($_SESSION['rights']=='admin'||$_SESSION['rights']=='moderator'){
    if(empty($name)) {
    $products = Product::findAll($pageNrInDb, MAX_CATEGORIES);
    } else {
        $products = Product::find_by_name($name);
    }
}else{
    if(empty($name)) {
        $products = Product::find_user_all($pageNrInDb, MAX_CATEGORIES, $ID);
    } else {
        $products = Product::find_by_user_product_name($name, $ID);
    }
}

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
                    if(!empty($product->category_id)){
                        $category_name = Category::find_by_ID2($product->category_id);
                        $id_name=explode(',', $product->category_id);
                        foreach ($id_name as $key => $value) {
                            if($key!=0){
                                echo ", ";
                            }

                            echo empty($id_name)?'Põhikategooria':$category_name[$key]->$categoryLang;
                        }
                    }else{
                        echo "Põhikategooria";
                    }

                ?>
            </td> 
            <td> 
                <a href="<?php echo ADMIN_URL . "?page=product-view&ID=" . $product->ID; ?>"> 
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
<?php else : 
    echo infoMessage('info', 'Tooted puuduvad'); 
endif; ?> 

<script src="<?php echo TEMPLATE_URL; ?>js/custom.js"></script>