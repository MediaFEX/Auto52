<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 7.04.2017
 * Time: 10:33
 */

require_once "../../../../include/start.php"; 

$ID = filter_input(INPUT_POST, 'ID', FILTER_VALIDATE_INT); 

if(empty($ID)) { 
    exit("ID missing"); 
} 

$picture = Picture::find_by_ID($ID); 
if(empty($picture)) { 
    exit("Picture missing"); 
} 

if($picture->delete()) { 
    unlink(makePicturePath($picture) . $picture->name); 
    unlink(makePicturePath($picture) . PICTURE_THUMB . '/' . $picture->name); 
    unlink(makePicturePath($picture) . PICTURE_MED . '/' . $picture->name); 
} else { 
    echo translate("cannot_delete_picture"); 
}