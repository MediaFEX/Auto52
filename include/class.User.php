<?php

/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 21.02.2017
 * Time: 9:03
 */
class User extends DatabaseQuery
{
    public static $table_name = 'users';
    public static $db_fields = [
        'ID', //> SERIAL
        'username', //> VARCHAR 50
        'password', //> VARCHAR 60
        'firstName', //> VARCHAR 50
        'lastName', //> VARCHAR 50
        'alias', //> VARCHAR 50
        'lang', //> VARCHAR 2
        'rights', //> VARCHAR 50
        'added', //> DATETIME
        'status' //> INT 1
    ];

    public $ID;
    public $username;
    public $password;
    public $firstName;
    public $lastName;
    public $alias;
    public $lang;
    public $rights;
    public $added;
    public $status;
    //Gets the current user that's logged in
    public static function getUserByName($username) {
        global $database;

        $query = "SELECT * FROM " . PX . self::$table_name
            . " WHERE username='". $database->escape_value($username) ."' LIMIT 1";

        $user = self::find_by_query($query);

        return empty($user) ? false : array_shift($user);
    }
    //Controls if he can log in
    public static function auth($username, $password) {

        $user = User::getUserByName($username);

        if(!$user) {
            return false;
        }

        if($user->status == 0) {
            return false;
        }

        if(password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }
    //Checks if the user can access the current page
    public static function checkRights($user_id, $page) {
        if(empty($user_id)) {
            return false;
        }

        $user = User::find_by_ID($user_id);

        if(empty($user)) {
            return false;
        }

        if(in_array($user->rights, userRights($page))) {
            return true;
        }

        return false;
    }
    //Finds all from database with set intervals (0 to 5, 6 to 10, etc)
    public static function findAll($start, $max)
    {
        global $database;

        $sql = "SELECT * FROM "
            . PX . self::$table_name . " LIMIT " . $database->escape_value($start) . ", " . $database->escape_value($max);

        $result = self::find_by_query($sql);

        return !empty($result) ? $result : false;
    }
}