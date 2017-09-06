<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 21.02.2017
 * Time: 8:47
 */

require_once "../include/start.php";

if($session->is_logged_in() && User::checkRights($session->user_id, 'index.php')) {
    //reDirectTo('index.php');
}

$btn = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

if (isset($btn) && $btn == 'login') {
    $errors = [];

    //kontroll kasutajanimele, et ei ole tühi
    $username = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_EMAIL);
    if(empty($username)) {
        $errors['username'] = "Kasutajanimi ei tohi olla tühi!";
    }

    //kontroll paroolile, et ei ole tühi
    $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    if(empty($pass)) { //kontroll paroolile, et ei ole tühi ja vähemalt X väärtust pikad => X=3
        $errors['password'] = "Parool ei tohi olla tühi";
    }

    if(empty($errors)) {
        //kontroll baasi, kas kasutja salasõna ja kasutajanimi klapivad
        if($user = User::auth($username, $pass)) {
            //kui kõik okei, siis suuname index.php lehel
            $session->login($user);
            $session->message('<div class="alert alert-success">Login oli edukas!</div>');
            reDirectTo('index.php');
        }

        $session->message('<div class="alert alert-danger">Kasutajanimi ja parool ei klapi!</div>');
        reDirectTo('login.php');
    }

    $session->message('<div class="alert alert-danger"><ul><li>'. join("</li><li>", $errors).'</li></ul></div>');
    reDirectTo('login.php');


    //kui probleeme, siis väljastame vead
}

if (isset($btn) && $btn == 'register') {

    $errors = [];
    //kontroll kasutajanimele, et ei ole tühi
    $username = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_EMAIL);
    if(empty($username)) {
        $errors['username'] = "Kasutajanimi ei tohi olla tühi!";
    } elseif (User::getUserByName($username)) { //kontroll kasutajanimele, et ei ole baasis
        $errors['username'] = "Selline kasutaja on juba andmebaasis!";
    }

    $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    if(empty($pass) && $pass < MAX_PASS_LENGTH) { //kontroll paroolile, et ei ole tühi ja vähemalt X väärtust pikad => X=3
        $errors['password'] = "Parool ei tohi olla tühi ega väiksem kui " . MAX_PASS_LENGTH;
    }

    $pass2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);
    if(empty($pass2) && $pass < MAX_PASS_LENGTH) { //kontroll paroolile2, et ei ole tühi ja vähemalt X väärtust pikad => X=3
        $errors['password2'] = "Parool ei tohi olla tühi ega väiksem kui " . MAX_PASS_LENGTH;
    }

    if($pass != $pass2) { //kontroll et paroolid on võrdsed
        $errors['password'] = "Paroolid ei klapi!";
    }

    if(empty($errors)) { //kui tingimused vastavad, siis loome kasutaja

        $user = new User();
        $user->username = $username;
        $passCrypt = better_crypt($pass);
        $user->password = $passCrypt;
        $user->lang = 'et';
        $user->rights = 'user';
        $user->added = date("Y-m-d H:i:s");
        $user->status = 0;

        if($user->save()) {

            //3. loome objecti ConfirmEmail
            ////4. sisestame andmed objekti
            $eConfirm = new EmailConfirm();
            $eConfirm->user_id = $database->get_last_ID();
            //1. vaja genereerida suvaline hash (a-A0-9)
            //2. kontroll ega ei ole baasis sellist hashi
            $eConfirm->hash = EmailConfirm::getHash(10);
            $eConfirm->added = date("Y-m-d H:i:s");

            //5. salvestame
            if($eConfirm->save()) {
                $session->message('<div class="alert alert-success">Kasutaja sisestati baasi</div>');
                reDirectTo('login.php');
            }

            $user->ID = $eConfirm->user_id;
            $user->delete();
            $session->message('<div class="alert alert-danger">Kasutaja salvestamisel tekkis probeem. Looge uus kasutaja</div>');
            reDirectTo('public/login.php');
        }

        $session->message('<div class="alert alert-danger">Kasutaja sisestamisel tekkis probleem</div>');
        reDirectTo('public/login.php');

    } else {
        $session->message('<div class="alert alert-danger"><ul><li>'. join("</li><li>", $errors).'</li></ul></div>');
        reDirectTo('public/login.php');
    }

}

get_template('head'); ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-center"><?php echo translate('admin_login'); ?></h1>
            </div>
        </div>
        <?php if(!empty($session->message)) : ?>
            <div class="row">
                <div class="col-lg-12">
                    <?php echo $session->message; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-lg-6">
                <h3 class="page-header text-center"><?php echo translate('login_header'); ?></h3>
                <form method="post">
                    <input name="username" class="form-control" placeholder="<?php echo translate('add_username_text'); ?>"><br>
                    <input name="password" class="form-control" type="password" placeholder="<?php echo translate('add_password_text'); ?>"><br>
                    <button type="submit" name="action" value="login" class="btn btn-success"><?php echo translate('login_btn'); ?></button>
                </form>
            </div>
            <div class="col-lg-6">
                <h3 class="page-header text-center"><?php echo translate('register_header'); ?></h3>
                <form method="post">
                    <input name="username" class="form-control" placeholder="<?php echo translate('add_email_text'); ?>"><br>
                    <input name="password" class="form-control" type="password" placeholder="<?php echo translate('add_password_text'); ?>"><br>
                    <input name="password2" class="form-control" type="password" placeholder="<?php echo translate('add_password2_text'); ?>"><br>
                    <button type="submit" name="action" value="register" class="btn btn-primary"><?php echo translate('register_btn'); ?></button>
                </form>
            </div>
        </div>
    </div>

<?php get_template('footer');