<?php

/**
 * Created by PhpStorm.
 * User: janek.mander
 * Date: 2.03.2017
 * Time: 10:23
 */
class EmailConfirm extends DatabaseQuery 
{ 
    public static $table_name = 'emailConfirm'; 
    public static $db_fields = [ 
        'ID', //> SERIAL 
        'user_id', //> INT 11
        'password', //> VARCHAR 50
        'hash', //> VARCHAR 10 
        'added', //> DATETIME 
    ]; 

    public $ID; 
    public $user_id; 
    public $password; 
    public $hash; 
    public $added; 
    //Generates hash for emails
    public static function getHash($length = 10) { 

        do { 
            $hash = generateHash($length); 
        } while (self::find_by_hash($hash)); 

        return $hash; 

    } 
    //Looks through database with a specific hash
    public static function find_by_hash($hash = '') { 
        global $database; 

        $query = "SELECT * FROM " . PX . self::$table_name 
            . " WHERE hash='".$database->escape_value($hash)."' LIMIT 1"; 

        $eConfirm = self::find_by_query($query); 

        return empty($eConfirm) ? false : array_shift($eConfirm); 
    } 
}