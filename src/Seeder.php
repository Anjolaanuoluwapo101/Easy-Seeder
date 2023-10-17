<?php
namespace Seeder;

require __DIR__."/../vendor/autoload.php";
require __DIR__."/MySQLConnector.php";
require __DIR__."/ParentSeeder.php";
require __DIR__."/SQLiteConnector.php";
require __DIR__."/Utility.php";
require __DIR__."/Traits.php";

use ParentSeeder\ParentSeeder;
use MySQLConnector\MySQLConnector;
use SQLiteConnector\SQLiteConnector;
use Faker\Factory;
use Traits\Traits;

class Seeder extends ParentSeeder {
 //Checkout the ParentSeeder first
 
 use Traits;
 
 //this builds the SQL statement,called from another function so it's better protected
 public function querybuilder():string {
  //check for possible errors
  if (empty($this->table)) {
   die(trigger_error("Table not defined\n"));
  }
  if (empty($this->fields)) {
   die(trigger_error("Column names not defined\n"));
  }
  
  $this->sortpreDefinedAndConstrainedFields();//this checks that a field isn't under the definedFields or constrianedField at the same time
  
  if (!empty($this->hiddenFields)) {
   $fieldKeys = array_keys($this->fields);
   foreach($fieldKeys as $index => $fieldKey){
    if(!in_array($fieldKey,$this->hiddenFields)){
     $this->accessibleFields[$fieldKey] = $this->fields[$fieldKey];
    }
   }
  } else {
   $this->accessibleFields = $this->fields;
  }
  $this->insertQuery = "INSERT INTO $this->table (";
  $this->insertQuery .= implode(', ', array_keys($this->accessibleFields));
  $this->insertQuery .= ') VALUES (';
  $this->insertQuery .= rtrim(str_repeat('?, ', count($this->accessibleFields)), ', ');
  $this->insertQuery .= ')';
  return $this->insertQuery;
 }

}

?>