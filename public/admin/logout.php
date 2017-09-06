<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 9.03.2017
 * Time: 13:59
 */
require_once "../../include/start.php"; 

$session->logout(); 
reDirectTo('login.php');