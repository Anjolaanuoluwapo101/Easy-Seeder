<?php
namespace ParentSeeder;

use ParentSeeder\ParentSeeder;
use MySQLConnector\MySQLConnector;
use SQLiteConnector\SQLiteConnector;
use Faker\Factory;


class ParentSeeder {
 //Bear with the crazy amount of variables 🙏
 public string $connectionType; //sqlite or mysql
 public $connection; //returns a PDO connection instance
 public $table; //stores the name of the table to be populated,provided by the user
 public array $fields; //stores an array of all the fields,including the data type for each field ["name"=>["type"=>string","length"=5],"email"=>["type"=>"text"],"password"=>["type"=>varchar","length"=>10],"..."]
 public array $hiddenFields; // stores an array of fields that you dont want to be populated with fake data
 protected array $accessibleFields; //fields - hidden fields
 protected $faker;
 protected string $insertQuery; //stores the dynamically created insert query
 public int $length; //the default length of data to be generated,can be overriden if a length is specified while setting the fields
 public array $preDefinedData; //sometimes we may want a field to have defined data specified by the user
 public array $constrainedData;
 protected array $fakerFunctions;
 
 //initiate a database connection and faker source
 public function __construct($connectionType) {
  $this->connectionType = $connectionType;
  $this->faker = Factory::create(); //instantiate our data source
  $this->SetPreDefinedFakerFunctions(); //imports some faker functions
  $this->hiddenFields = array();
  $this->fields = array();
  $this->accessibleFields = array();
  if ($connectionType == 'sqlite') {
   $this->connection = new SQLiteConnector();
   $this->connection = $this->connection->getConnection();
  } else if ($connectionType == 'mysql') {
   $this->connection = new MySQLConnector();
   $this->connection = $this->connection->getConnection();
  } else {
   die(trigger_error("Database -> $connectionType not yet supported \n"));
  }
 }

 //checks that the user defined fields are matched with supported data types
 public function setFields(array $fields):void {
  //the $fields should recieve an array containing the fields
  if ($this->connectionType == 'sqlite') {
   foreach ($fields as $key => $value) {
    //$value is an array that stores the length(optional) and type(required) of each provided field($key)
    if (!isset($value['type']) || !in_array(strtolower($value['type']), SQLiteConnector::$supportedDataTypes)) {
     $dataType = $value['type'];
     die(trigger_error("The data type: $dataType is not available for the field $key \n"));
    }
   }
   $this->fields = $fields;
  } else if ($this->connectionType == 'mysql') {
   foreach ($fields as $key => $value) {
    if (!isset($value['type']) || !in_array(strtolower($value['type']), MySQLConnector::$supportedDataTypes)) {
     $dataType = $value['type'];
     die(trigger_error("The data type: $dataType is not available for the field $key \n"));
    }
   }
   $this->fields = $fields;
  }
 }

 //is helps check that the user did not set a field as both preDefinedData and constrainedData
 protected function sortpreDefinedAndConstrainedFields() {
  if (!isset($this->preDefinedData, $this->constrainedData)) {
   return;
  }
  if (empty($this->preDefinedData) && empty($this->constrainedData)) {
   return;
  }
  $preDefinedDataFields = array_keys($this->preDefinedData);
  $constrainedDataFields = array_keys($this->constrainedData);
  foreach ($preDefinedDataFields as $index => $preDefinedDataField) {
   if (in_array($preDefinedDataField, $constrainedDataFields)) {
    die(trigger_error("<b> $preDefinedDataField </b>  present under definedFields and constrainedField"));
   }
  }
 }

 protected function setPreDefinedFakerFunctions() {
  $this->fakerFunctions = array(
   'text' => function($length) {
    return $this->faker->text($length);
   },
   'name' => function() {
    return $this->faker->name();
   },
   'int' => function($length) {
    return $this->faker->randomNumber($length);
   },
   'float' => function() {
    return $this->faker->randomFloat(2, 0, 1000);
   },
   'date' => function() {
    return $this->faker->date;
   }
   //You can hard code more data types and functions as needed right here!
  );
 }
}
?>