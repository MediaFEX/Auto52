<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 10.05.2016
 * Time: 9:12
 */
//Gets the template
function get_template($template_part) { 
    $template_part_with_path = TEMPLATE_PATH . $template_part . ".php"; 
    if(file_exists($template_part_with_path)) { 
        require_once $template_part_with_path; 
    } 
} 
//Hashes the password
function better_crypt($input, $rounds = 10) { 
    $crypt_options = array( 
        'cost' => $rounds 
    ); 
    return password_hash($input, PASSWORD_BCRYPT, $crypt_options); 
} 
//Controls current data type and displays data
function pd($data) {
    if(empty($data)) { 
        echo "Data missing!";
        return true;
    } 

    if(is_array($data) || is_object($data)) {
        echo '<pre>'; 
        print_r($data); 
        echo '</pre>'; 
        return; 
    } 

    echo $data;
} 
//Redirects to a different page
function reDirectTo($url) { 

    if(empty($url)) { 
        exit(); 
    } 

    header('Location: ' . $url); 
    exit(); 
} 
//Holds pages who can access what.
function userRights($page) { 
    if(empty($page)) { 
        return []; 
    }
    switch ($page) {
        case 'index.php':
            return ['moderator', 'admin', 'user'];
            break;

        case 'categories.php':
            return ['admin'];
            break;

        case 'category.php':
            return ['admin'];
            break;

        case 'login.php':
            return ['user', 'moderator', 'admin'];
            break;

        case 'delete-product.php':
            return ['user', 'moderator', 'admin'];
            break;

        case 'delete.php':
            return ['user', 'moderator', 'admin'];
            break;

        case 'delete-user.php':
            return ['user', 'moderator', 'admin'];
            break;
    } 
}

function invalidAccess($ID, $session, $array, $class, $redirect, $table){//Give it ID, Session and the users who can access all. Never put a user here.
    $category='empty';
    if(!empty($ID)){//Checks if ID is empty if you want to make a new account
        $category = $class::find_by_ID($ID);//Gets data from ID
        foreach ($array as $key => $value){
            if($_SESSION['rights']==$value||$_SESSION['user_id']==$category->$table){//Checks if they can even access this
                return $category;//Gives valuable information
            }
        }
    }

    $session->message('<div class="alert alert-danger">Kategooria puudub</div>');//If they don't have access throw them out.
    reDirectTo(ADMIN_URL . '?page='.$redirect);
}

//Gives them the translation. because it was on 3 different pages.
function translationGiver($product){
    $translates = ProductLanguage::findByProductId($product->ID, LANG);//Gets all current products name and descritption among other things with the given language
    if(!empty($translates)) {
        $translations = (object) array_column($translates, 'column_value', 'table_column');//Filters out the most important things: Name and description
    } else { 
        $translations = null;//No translations to give, so surround it with [ ]
    }
    return $translations;
}

//Generates the hash used for password encryption
function generateHash($length) { 
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 
    return substr(str_shuffle($chars),0,$length); 
} 
//Displays the current message
function infoMessage($status, $info) { 
    return '<div class="alert alert-'. $status .'">'. $info .'</div>'; 
} 
//Makes the picture link
function makePictureLink($picture) { 
    //UPLOAD_PATH -> aasta -> kuu -> product_id 

    $year = date("Y", strtotime($picture->added)); 
    $month = date("m", strtotime($picture->added)); 

    return UPLOAD_URL . $year . "/" . $month . "/" . $picture->product_id . "/"; 
} 
//Makes a path for the picture
function makePicturePath($picture) { 
    //UPLOAD_PATH -> aasta -> kuu -> product_id 

    $year = date("Y", strtotime($picture->added)); 
    $month = date("m", strtotime($picture->added)); 

    return UPLOAD_PATH . $year . "/" . $month . "/" . $picture->product_id . "/"; 
} 
//Deletes a folder that is no longer in use.
function deleteFolder($str = ""){ 
    if(is_file($str)){ 
        return @unlink($str) ? true : false; 
    } elseif(is_dir($str)) { 
        $scan = glob(rtrim($str, '/') . '/*'); 
        foreach ($scan as $index => $path) { 
            deleteFolder($path); 
        } 
        return @rmdir($str) ? true : false; 
    } 
} 

function translate($translate, $l = null) { 
    global $t;

    if(!empty($l)) { 

        if(file_exists(INCLUDE_PATH . "languages" . DS . $l . '.php')) {
            require_once INCLUDE_PATH . "languages" . DS . $l . '.php'; 
            return isset($t[$translate]) ? $t[$translate] : "[" . $translate . "]"; 
        } 

    }
    return isset($t[$translate]) ? $t[$translate] : "[" . $translate . "]";

}
//Filters an array depending on the filter
function filterArray ($array, $filter) {

    if(empty($array)) {
        return false;
    }

    foreach ($array as $key => $value) {
        $array[$key] = filter_var($value, $filter);
    }

    return $array;
}
//Creates an array for categories
function createCategoryArray ($categories) {
    if(empty($categories)) {
        return false;
    }

    $array = [];

    foreach ($categories as $category) {
        $array[$category->parent][] = $category;
    }

    return $array;
}
//Creates a select to keep code beautiful
function createSelect($array, $object, $name, $whyLang, $disabled){
    if(empty($array)){
        return false;
    }
    ?>
    <div class="form-group">
        <label for="<?php echo $whyLang; ?>"><?php echo $whyLang; ?></label>
        <select <?php echo $disabled; ?> name="<?php echo $name ?>" class="form-control">
            <?php
            foreach ($array as $key => $value):?>
                <option <?php if(isset($object->$name)&&$object->$name==$value){echo "selected";} ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php
}

function pager($pageNr, $pagesCount, $next, $previous, $link){ ?>
    <ul class="pager">
        <?php if(!empty($pageNr)) : //If pagenr is empty, don't display previous otherwise you'll go to -1 page?>
            <?php if($pageNr == 1) : //If page is only 1 past the base page, go back without the pageNr in the url   ?>
                <li><a href="<?php echo ADMIN_URL . "?page=".$link; ?>"><?php echo translate("previous_btn") ?></a></li>
            <?php else: //Otherwise add the pageNr (4,3,2)?>
                <li><a href="<?php echo ADMIN_URL . "?page=".$link."&pageNr=" . $previous; ?>"><?php echo translate("previous_btn") ?></a></li>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($pagesCount-1 > $pageNr ) : //Show the next page when there is something to show there?>
            <li><a href="<?php echo ADMIN_URL . "?page=".$link."&pageNr=" . $next; ?>"><?php echo translate("next_btn") ?></a></li>
        <?php endif; ?>
    </ul>
<?php } ?>