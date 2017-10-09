<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 9.03.2017
 * Time: 13:38
 */

if(!defined('MAIN_PATH')) {
    header("Location: /");
    exit();
}
if(!$session->is_logged_in() || !User::checkRights($session->user_id, 'categories.php')) { //Repels users and moderators
    $session->message('<div class="alert alert-danger">You don\'t have the rights to view that page.</div>');//If they don't have access throw them out.
    reDirectTo(ADMIN_URL . '?page=home');
}
//Get ID that I'm modifying
$ID = filter_input(INPUT_GET, 'ID', FILTER_VALIDATE_INT);
if(!empty($ID)) { //If ID isn't empty, means I'm modifying some category so I'll get it
    $category = Category::find_by_ID($ID);

    if(empty($category)) { //If the current category is empty for some reason, just display it and send me back to catogories
        $session->message('<div class="alert alert-danger">Kategooria puudub</div>');
        reDirectTo(ADMIN_URL . '?page=categories');
    }
}
//Variables that I'm going to use
$btn = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$et_name = filter_input(INPUT_POST, 'et_name', FILTER_SANITIZE_STRING);
$en_name = filter_input(INPUT_POST, 'en_name', FILTER_SANITIZE_STRING);
$parent = filter_input(INPUT_POST, 'parent', FILTER_VALIDATE_INT);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

if(isset($btn)) { //If button is pressed
    $errors = [];

    if(empty($et_name)||empty($en_name)) {  //Make sure the name isn't empty or over 100 letters
        $errors['name'] = "Nimi ei tohi olla tühi";
    } elseif (strlen($en_name) > 100||strlen($et_name)>100) {
        $errors['name'] = "Nimi ei tohi olla pikem kui 100 ühikut";
    }

    if(empty($errors)) { //If there are no errors then advance

        if(empty($ID)) { //If instead of modifyi ng a current existing category, create one
            $category = new Category(); 
        } 

        $category->et_name = $et_name;
        $category->en_name = $en_name; 
        $category->added = date("Y-m-d H:i:s"); 
        $category->parent = $parent; 
        $category->status = $status == 'on' ? 1 : 0; 

        if($category->save()) {     //If the save is successful
            if(empty($ID)) { 
                $session->message('<div class="alert alert-success">Kategooria lisati baasi</div>');    //Then create the category
            } else { 
                $session->message('<div class="alert alert-success">Kategoori uuendati</div>');     //Or modify the current existing one
            } 
            reDirectTo(ADMIN_URL . '?page=category'); //Then throw me back to the category
        } 

        $session->message('<div class="alert alert-warning">Kategooriat ei lisatud baasi</div>'); //If something didn't work give me an error and then throw me back
        reDirectTo(ADMIN_URL . '?page=category'); 
    } 
} 

//The page the user sees  ?>
<h3 class="page-header text-right"><?php echo $pages[$page]['name'] ?></h3>
<?php echo isset($session->message) ? $session->message : '' ?>
<?php echo empty($errors) ? '' : "<ul><li>" . join("</li><li>", $errors) . "</li></ul>"; ?>
<form method="post">
    <div class="form-group">
        <label for="name">Eesti keelne nimi</label>
        <input value="<?php echo isset($category->et_name) ? $category->et_name : ''; //If category has a name reveal it, otherwise don't show it?>" name="et_name" type="text" class="form-control" id="name" placeholder="Lisage eesti nimi">
    </div>
    <div class="form-group">
        <label for="name">Inglise keelne nimi</label>
        <input value="<?php echo isset($category->en_name) ? $category->en_name : ''; //If category has a name reveal it, otherwise don't show it?>" name="en_name" type="text" class="form-control" id="name" placeholder="Lisage inglise nimi">
    </div>
    <div class="form-group">
        <label for="parent">Vanem</label> 
        <?php $categories = Category::findAllWithNoParent(); ?> 
        <select name="parent" class="form-control chosen-select"> 
            <option value="0">Valige</option> 
            <?php if(!empty($categories)) : foreach ($categories as $cat) : //Finds all without parents?> 
                <?php if($ID != $cat->ID) : ?> 
                    <option value="<?php echo $cat->ID; ?>" 
                        <?php echo isset($category->parent) && $category->parent == $cat->ID ? 'selected' : '' ?> 
                    ><?php echo $cat->$categoryLang; ?></option>
                <?php endif; ?> 
            <?php endforeach; endif; ?> 
        </select> 

    </div> 
    <div class="checkbox"> 
        <label> 
            <input name="status" type="checkbox" 
                <?php echo isset($category->status) && $category->status == 1 ? 'checked' : '';//Checks if it has a status or not.?>> Status 
        </label> 
    </div> 
    <button type="submit" value="add" name="action" class="btn btn-default">Loo</button> 
</form>