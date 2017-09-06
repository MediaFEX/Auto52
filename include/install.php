<?php 
/** 
 * Created by PhpStorm. 
 * User: andrus.jakobson
 * Date: 21.02.2017 
 * Time: 9:33 
 */ 

$create_user = "CREATE TABLE `TAK15_Jakobson`.`TACOLA_users`( 
  `ID` SERIAL, 
  `username` VARCHAR(50) NOT NULL, 
  `password` VARCHAR(60) NOT NULL, 
  `firstName` VARCHAR(50) NOT NULL, 
  `lastName` VARCHAR(50) NOT NULL, 
  `alias` VARCHAR(50) NOT NULL, 
  `lang` VARCHAR(2) NOT NULL, 
  `group` VARCHAR(50) NOT NULL, 
  `added` DATETIME NOT NULL, 
  `edited` DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `status` INT(1) NOT NULL 
) ENGINE = InnoDB;";


$create_table = "CREATE TABLE `TAK15_Jakobson`.`TACOLA_emailConfirm` (
`ID` SERIAL,
`user_id` INT NOT NULL,
`hash` VARCHAR(10) NOT NULL,
`added` DATETIME NOT NULL
) ENGINE = InnoDB;";