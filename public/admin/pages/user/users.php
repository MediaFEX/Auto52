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
//$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

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
        if(i)
        $passCrypt = better_crypt($pass);
        $category->password = $passCrypt;
        $category->status = $status;
        //$category->status = $status == 'on' ? 1 : 0;

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
        <input value="<?php echo isset($category->username) ? $category->username : ''; ?>" name="name" type="text" class="form-control" id="name" placeholder="Lisage parool">
    </div>
    <div class="form-group">
        <label for="name">Password</label>
        <input value="<?php echo isset($category->password) ? $category->password : ''; ?>" name="pass" type="text" class="form-control" id="pass" placeholder="Lisage parool">
    </div>
    <label for="status">Status</label>
    <select name="status" class="form-control">
      <option <?php if(isset($category->status)&&$category->status==0){echo "selected";} ?> value="0">0</option>
      <option <?php if(isset($category->status)&&$category->status==1){echo "selected";} ?> value="1">1</option>
      <option <?php if(isset($category->status)&&$category->status==2){echo "selected";} ?> value="2">2</option>
      <option <?php if(isset($category->status)&&$category->status==3){echo "selected";} ?> value="3">3</option>
    </select>

    <button type="submit" value="add" name="action" class="btn btn-default">Loo</button>
</form>