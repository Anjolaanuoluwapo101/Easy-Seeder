<?php
//this code just loads them the needful
require "src/Seeder.php";

$seeder = new Seeder\Seeder('sqlite');
$seeder->table = 'testTable';
$seeder->setFields(array(
 'name' => array("type" => "text","length"=>20),
 'age' => array("type" => "int","length"=>2),
));
//$seeder->hiddenFields = array("name");
/*$seeder->constrainedData = array(
 "name" => array("donovan","bragado"),
 "age" => array(16,17),
 );*/
/*$seeder->preDefinedData = array(
 "name" => array("donovan","bragado"),
 );*/
$string = function($length){
 return strval($length);
};
$seeder->addCustomFunction('text',$string);
echo $seeder->seed(1);
//echo $seeder->queryBuilder();
?>