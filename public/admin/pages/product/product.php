<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 9.03.2017
 * Time: 14:39
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

$btn = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

if(isset($btn)) {
    $errors = [];

    $names = filterArray($_POST['name'], FILTER_SANITIZE_STRING);
    $prices = filterArray($_POST['price'], FILTER_VALIDATE_FLOAT);
    $descriptions = filterArray($_POST['description'], FILTER_SANITIZE_STRING);
    $parents = filterArray($_POST['parent'], FILTER_VALIDATE_INT);

//    unset($names['et']);
//    unset($prices['et']);
//    unset($descriptions['et']);

//    pd($names);
//    pd($prices);
//    pd($descriptions);
//    pd($parents);

    //exit();

    if(empty($names['et'])) {
        $errors['name'] = "Nimi ei tohi olla tühi";
    } elseif (strlen($names['et']) > 100) {
        $errors['name'] = "Nimi ei tohi olla pikem kui 100 ühikut";
    }

    if(empty($prices['et'])) {
        $errors['price'] = "Hind ei tohi tühi olla";
    }

    if(empty($parent)) {
        $errors['parent'] = "Kategooria ei tohi tühi olla";
    }

    if(empty($errors)) {

        if(empty($ID)) {
            $product = new Product();
        }

        $product->name = $names['et'];
        $product->price = $prices['et'];
        $product->added = date("Y-m-d H:i:s");
        $product->category_id = $parent;
        $product->status = $status == 'on' ? 1 : 0;
        $product->added_by = $_SESSION['user_id'];
        $product->edited_by = $_SESSION['user_id'];
        $product->description = $descriptions['et'];

        if($product->save()) {

            $product_id = empty($ID) ? $database->get_last_ID() : $ID;

            unset($names['et']);
            unset($prices['et']);
            unset($descriptions['et']);

            foreach ($names as $lang => $name) {
                $pLang = ProductLanguage::findByColumnLangProduct('name', $lang, $product_id);
//                pd($pLang);
//                exit("ID: " . $product_id);
                if(empty($pLang)) {
                    $pLang = new ProductLanguage();
                }

                $pLang->product_id = $product_id;
                $pLang->table_column = 'name';
                $pLang->column_value = $name;
                $pLang->language = $lang;
                $pLang->added = date("Y-m-d H:i:s");
                $pLang->added_by = $_SESSION['user_id'];
                $pLang->edited_by = $_SESSION['user_id'];
                $pLang->status = 1;

                $pLang->save();
            }

            foreach ($prices as $lang => $name) {
                $pLang = ProductLanguage::findByColumnLangProduct('price', $lang, $product_id);

                if(empty($pLang)) {
                    $pLang = new ProductLanguage();
                }

                $pLang->product_id = $product_id;
                $pLang->table_column = 'price';
                $pLang->column_value = $name;
                $pLang->language = $lang;
                $pLang->added = date("Y-m-d H:i:s");
                $pLang->added_by = $_SESSION['user_id'];
                $pLang->edited_by = $_SESSION['user_id'];
                $pLang->status = 1;

                $pLang->save();
            }

            foreach ($descriptions as $lang => $name) {
                $pLang = ProductLanguage::findByColumnLangProduct('description', $lang, $product_id);

                if(empty($pLang)) {
                    $pLang = new ProductLanguage();
                }

                $pLang->product_id = $product_id;
                $pLang->table_column = 'description';
                $pLang->column_value = $name;
                $pLang->language = $lang;
                $pLang->added = date("Y-m-d H:i:s");
                $pLang->added_by = $_SESSION['user_id'];
                $pLang->edited_by = $_SESSION['user_id'];
                $pLang->status = 1;

                $pLang->save();
            }

            if(empty($ID)) {
                $session->message('<div class="alert alert-success">Toode lisati baasi</div>');
                reDirectTo(ADMIN_URL . '?page=products');
            } else {
                $session->message('<div class="alert alert-success">Toodet uuendati</div>');
                reDirectTo(ADMIN_URL . '?page=product&ID=' . $ID);
            }
        }

        $session->message('<div class="alert alert-warning">Toodet ei lisatud baasi</div>');
        reDirectTo(ADMIN_URL . '?page=products');
    }
}

?>
<h3 class="page-header text-right"><?php echo $pages[$page]['name'] ?></h3>

<?php echo isset($session->message) ? $session->message : '' ?>
<?php echo empty($errors) ? '' : "<ul><li>" . join("</li><li>", $errors) . "</li></ul>"; ?>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#et" aria-controls="et" role="tab" data-toggle="tab"><?php echo translate('flag', 'et'); ?></a></li>
    <?php if(!empty($languagesInPage)) : foreach ($languagesInPage as $item) : ?>
        <li role="presentation"><a href="#<?php echo $item; ?>" aria-controls="<?php echo $item; ?>" role="tab" data-toggle="tab"><?php echo translate('flag', $item); ?></a></li>
    <?php endforeach; endif; ?>
</ul>

<form method="post">

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="et">
            <div class="form-group">
                <label for="name"><?php echo translate('product_name'); ?></label>
                <input value="<?php echo isset($product->name) ? $product->name : ''; ?>" name="name[et]" type="text" class="form-control" id="name" placeholder="Lisage nimi">
            </div>
            <div class="form-group">
                <label for="name"><?php echo translate('product_price'); ?></label>
                <input value="<?php echo isset($product->price) ? $product->price : ''; ?>" name="price[et]" type="text" class="form-control" id="price" placeholder="Lisage Hind">
            </div>
            <div class="form-group">
                <label for="description"><?php echo translate('product_description'); ?></label>
                <textarea name="description[et]" class="form-control" id="description"><?php echo isset($product->description) ? $product->description : ''; ?></textarea>
            </div>
        </div>
        <?php

        if(!isset($product)) {
            $product_id = 0;
        } else {
            $product_id = $product->ID;
        }

        if(!empty($languagesInPage)) : foreach ($languagesInPage as $item) :

            $translates = ProductLanguage::findByProductId($product_id, $item);
            if(!empty($translates)) {
                $translations = (object) array_column($translates, 'column_value', 'table_column');
            } else {
                $translations = null;
            } ?>

            <div role="tabpanel" class="tab-pane" id="<?php echo $item; ?>">
                <div class="form-group">
                    <label for="name"><?php echo translate('product_name', $item); ?></label>
                    <input value="<?php echo isset($translations->name) ? $translations->name : ''; ?>" name="name[<?php echo $item; ?>]" type="text" class="form-control" id="name" placeholder="Lisage nimi">
                </div>
                <div class="form-group">
                    <label for="name"><?php echo translate('product_price', $item); ?></label>
                    <input value="<?php echo isset($translations->price) ? $translations->price : ''; ?>" name="price[<?php echo $item; ?>]" type="text" class="form-control" id="price" placeholder="Lisage Hind">
                </div>
                <div class="form-group">
                    <label for="description"><?php echo translate('product_description', $item); ?></label>
                    <textarea name="description[<?php echo $item; ?>]" class="form-control" id="description"><?php echo isset($translations->description) ? $translations->description : ''; ?></textarea>
                </div>
            </div>
        <?php endforeach; endif;
        ?>
    </div>
    <h4><?php echo translate('product_categories'); ?></h4>
    <?php $categories = Category::find_all(); ?>
    <?php
    $categoryMain = [];
    $categorySub = [];
    if(!empty($categories)) : foreach ($categories as $cat) {
        if($cat->parent == 0) {
            $categoryMain[$cat->ID] = $cat;
        } else {
            $categorySub[$cat->parent][] = $cat;

        }
    } endif;
    ?>
    <?php if(!empty($categoryMain)) : foreach ($categoryMain as $main) : ?>
        <div class="form-group">
            <label for="parent"><?php echo $main->name; ?></label>
            <select name="parent[]" class="form-control chosen-select">
                <option value="0">Valige</option>
                <?php if(!empty($categorySub[$main->ID])) : foreach ($categorySub[$main->ID] as $sub) : ?>
                    <?php if($ID != $sub->ID) : ?>
                        <option value="<?php echo $sub->ID; ?>"
                            <?php //echo isset($product->category_id) && $product->category_id == $sub->ID ? 'selected' : '' ?>
                        ><?php echo $sub->name; ?></option>
                    <?php endif; ?>
                <?php endforeach; endif; ?>
            </select>
        </div>
    <?php endforeach; endif; ?>
    <div class="checkbox">
        <label>
            <input name="status" type="checkbox"
                <?php echo isset($product->status) && $product->status == 1 ? 'checked' : '';?>> Status
        </label>
    </div>
    <button type="submit" value="add" name="action" class="btn btn-default">Loo</button>
</form>