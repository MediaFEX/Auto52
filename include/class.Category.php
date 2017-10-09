<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 9.03.2017
 * Time: 13:19
 */
class Category extends DatabaseQuery 
{ 
    public static $table_name = 'categories'; 
    public static $db_fields = [ 
        'ID', //> SERIAL 
        'et_name', //> VARCHAR 100
        'en_name',   //> VARCHAR 100
        'parent', //> INT
        'added', //> DATETIME
        'status', //> INT 1
    ]; 

    public $ID; 
    public $et_name;
    public $en_name;
    public $parent;
    public $added; 
    public $status;



//              Looks for name through the category database
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
//              Looks for category product
    public static function find_parent($ID) { 
        global $database; 

        $sql = "SELECT * FROM " 
            . PX . self::$table_name 
            . " WHERE parent=" . $database->escape_value($ID) . " LIMIT 1"; 

        $result = static::find_by_query($sql); 

        return !empty($result) ? array_shift($result) : false; 
    }
//              Looks for all through category with a start to end (0 to 5, 6 to 10 etc)
    public static function findAll($start, $max) { 
        global $database; 

        $sql = "SELECT * FROM " 
            . PX . self::$table_name . " LIMIT " . $database->escape_value($start) . ", " . $database->escape_value($max); 

        //echo $sql; 

        $result = self::find_by_query($sql); 

        return !empty($result) ? $result : false; 
    }
//              Looks through the category database and searches for categories without parents
    public static function findAllWithNoParent(){ 
        $sql = "SELECT * FROM "
            . PX . static::$table_name . " WHERE parent=0 LIMIT 1000";

        $result = static::find_by_query($sql);

        return !empty($result) ? $result : false;
    }
}