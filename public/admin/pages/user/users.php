<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 13.09.2017
 * Time: 14:34
 */
if(!defined('MAIN_PATH')) {
    header("Location: /");
    exit();
}

$ID = filter_input(INPUT_GET, 'ID', FILTER_VALIDATE_INT);
if(!empty($ID)) {
    $category = User::find_by_ID($ID);

    if(empty($category)) {
        $session->message('<div class="alert alert-danger">Kategooria puudub</div>');
        reDirectTo(ADMIN_URL . '?page=users');
    }
}

$btn = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
$email = filter_input(INPUT_POST, 'name', FILTER_VALIDATE_EMAIL);
$pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
$rights = filter_input(INPUT_POST, 'rights', FILTER_SANITIZE_STRING);

if(isset($btn)) {
    $errors = [];

    if(empty($email)) {
        $errors['name'] = "Email ei tohi olla tühi";
    } elseif (strlen($email) > 100) {
        $errors['name'] = "Email ei tohi olla pikem kui 100 ühikut";
    }

    if(empty($errors)) {

        if(empty($ID)) {
            $category = new User();
            $newAcc = 1;
        }

        $category->username = $email;
        if(isset($newAcc)){$category->added = date("Y-m-d H:i:s");}
        if(!strlen($pass)==0){
        	$passCrypt = better_crypt($pass);
        	$category->password = $passCrypt;
        }
        $category->status = $status;
        $category->rights = $rights;

        if($category->save()) {
            if(empty($ID)) {
                $session->message('<div class="alert alert-success">Kategooria lisati baasi</div>');
            } else {
                $session->message('<div class="alert alert-success">Kategoori uuendati</div>');
            }
            reDirectTo(ADMIN_URL . '?page=users');
        }

        $session->message('<div class="alert alert-warning">Kategooriat ei lisatud baasi</div>');
        reDirectTo(ADMIN_URL . '?page=users');

    }
}

?>
<h3 class="page-header text-right"><?php echo $pages[$page]['name'] ?></h3>
<?php echo isset($session->message) ? $session->message : '' ?>
<?php echo empty($errors) ? '' : "<ul><li>" . join("</li><li>", $errors) . "</li></ul>"; ?>
<form method="post">
    <div class="form-group">
        <label for="name">Email</label>
        <input value="<?php echo isset($category->username) ? $category->username : ''; ?>" name="name" type="text" class="form-control" id="name" placeholder="Lisa email">
    </div>
    <div class="form-group">
        <label for="name">Password</label>
        <input name="pass" type="text" class="form-control" id="pass" placeholder="Muuda parool">
    </div>
    <label for="status">Status</label>
    <select name="status" class="form-control">
      <option <?php if(isset($category->status)&&$category->status==0){echo "selected";} ?> value="0">0</option>
      <option <?php if(isset($category->status)&&$category->status==1){echo "selected";} ?> value="1">1</option>
    </select>
    <label for="status">Rights</label>
    <select name="rights" class="form-control">
      <option <?php if(isset($category->rights)&&$category->rights=='user'){echo "selected";} ?> value="user">User</option>
      <option <?php if(isset($category->rights)&&$category->rights=='moderator'){echo "selected";} ?> value="moderator">Moderator</option>
      <option <?php if(isset($category->rights)&&$category->rights=='admin'){echo "selected";} ?> value="admin">Admin</option>
    </select>


    <button type="submit" value="add" name="action" class="btn btn-default">Loo</button>
</form>