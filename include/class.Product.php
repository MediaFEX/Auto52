<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 9.03.2017
 * Time: 14:07
 */
class Product extends DatabaseQuery
{
    public static $table_name = 'en_products'; 
    public static $db_fields = [
        'ID', //> SERIAL
        'name', //> VARCHAR 50
        'price', //> DECIMAL(10,2)
        'description', //> TEXT
        'category_id', //> VARCHAR 50
        'main_picture', //> VARCHAR 255
        'added', //> DATETIME 50
        'added_by', //> INT 11
        'edited_by', //> INT11
        'status' //> INT 11
    ];


    public $ID; 
    public $name; 
    public $price; 
    public $description; 
    public $category_id; 
    public $main_picture; 
    public $added; 
    public $added_by; 
    public $edited_by; 
    public $status; 

    //Find product by name
    public static function find_by_name($s = "") { 
        global $database; 

        if(empty($s)) { 
            return false; 
        } 

        $query = "SELECT * FROM " . PX . self::$table_name 
            . " WHERE name LIKE '%" . $database->escape_value($s) . "%'"; 

        $results = self::find_by_query($query); 

        return empty($results) ? false : $results; 
    }
    //Finds product by name that the user has created
    public static function find_by_user_product_name($s = "", $ID) { 
        global $database; 

        if(empty($s)) { 
            return false; 
        } 

        $query = "SELECT * FROM " . PX . self::$table_name 
            . " WHERE name LIKE '%" . $database->escape_value($s) . "%' && added_by=".$ID;

        $results = self::find_by_query($query);

        return empty($results) ? false : $results;
    }
    //Finds by a specific category
    public static function find_by_category($categories = null) { 
        global $database; 

        if(empty($categories)) { 
            return false; 
        } 

        $query = "SELECT * FROM " . PX . self::$table_name 
            . " WHERE category_id IN (" . $database->escape_value($categories) . ") LIMIT 1000"; 

        $results = self::find_by_query($query); 

        return empty($results) ? false : $results; 
    } 
    //Adds the euro symbol
    public function showPrice() { 
        return $this->price . "€"; 
    }
    //Finds all with a beginning and end (0 to 5, 6 to 10, etc)
    public static function findAll($start, $max) {
        global $database;

        $sql = "SELECT * FROM "
            . PX . self::$table_name . " LIMIT " . $database->escape_value($start) . ", " . $database->escape_value($max);

        //echo $sql;

        $result = self::find_by_query($sql);


        return !empty($result) ? $result : false;
    }
    //Finds products the user has made
    public static function find_user_all($start, $max, $ID) {
        global $database;

        $sql = "SELECT * FROM "
            . PX . self::$table_name . " WHERE added_by=".$ID." LIMIT " . $database->escape_value($start) . ", " . $database->escape_value($max);

        //echo $sql;

        $result = self::find_by_query($sql);


        return !empty($result) ? $result : false;
    }


}