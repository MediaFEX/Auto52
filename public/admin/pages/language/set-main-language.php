<?php
require_once "../../../../include/start.php"; 

$lang = filter_input(INPUT_POST, 'lang', FILTER_SANITIZE_STRING); 

$_SESSION['lang'] = $lang;