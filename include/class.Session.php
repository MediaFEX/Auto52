<?php

/**
 * Created by PhpStorm.
 * User: janek.mander
 * Date: 10.05.2016
 * Time: 9:01
 */
class Session
{
    private $logged_in = false;
    public $user_id;
    public $lang;
    public $message;
    //Starts the session if he's logged in
    function __construct() {
        session_start();
        $this->check_login();
        $this->check_message();

    }
    //Controls if the user is logged in.
    public function is_logged_in() {
        return $this->logged_in;
    }
    //Controls if the user has logged in.
    private function check_login() {
        if(isset($_SESSION['user_id'])) {
            $this->user_id = $_SESSION['user_id'];
            $this->logged_in = true;
        } else {
            unset($this->user_id);
            $this->logged_in = false;
        }
    }
    //Checks the current saved message
    private function check_message() {
        if(isset($_SESSION['message'])) {
            $this->message = $_SESSION['message'];
            unset($_SESSION['message']);
        } else {
            $this->message = "";
        }
    }
    //Holds the current message for later relay
    public function message ($msg = ""){
        if(empty($msg)) {
            return $this->message;
        } else {
            $_SESSION['message'] = $msg;
        }
    }
    //Assigns current user current session values
    public function login($user) {
        if($user) {
            $this->user_id = $_SESSION['user_id'] = $user->ID;
            $this->lang = $_SESSION['lang'] = $user->lang;
            $this->rights = $_SESSION['rights'] = $user->rights;
            $this->logged_in = true;
        }
    }
    //Logs the user out.
    public function logout() {
        session_unset();
        unset($this->user_id);
        $this->logged_in = false;
    }
}

$session = new Session();
$message = $session->message();