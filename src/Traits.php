<?php

//Didn't want put these functions in neither Seeder.php nor ParentSeeder.php
namespace Traits;

trait Traits {
 public function seed(int $count) {
  $this->querybuilder(); //build query first and resolve the accessibleFields
  //we check if $this->accessibleFields is not empty
  if (empty($this->accessibleFields)) {
   die(trigger_error("Atleast one field must be visible (not all fields can be hidden)"));
  }
  $insert = $this->connection->prepare($this->insertQuery); //initiate prepared statement
  for ($i = 0; $i < $count; $i++) {
   $params = [];
   //we check if constrainedData is set
   if (!empty($this->constrainedData)) {
    $constrainedData = getConstraintedFields($this->constrainedData); //just continue reading the script..you will see where it's used
   }

   foreach ($this->accessibleFields as $field => $details) {
    //check if this particular field has been set to have a predefined data for instance..the field "name" may have been set to have "Anjola" or "Dami" or "Wale" only
    if (!empty($this->preDefinedData)) {
     $preDefinedData = getpreDefinedData($this->preDefinedData, $field); //returns either [true,random defined data set by the user] or [false,null]
     if ($preDefinedData[0]) {
      //can either be true or false;
      $params[] = $preDefinedData[1];
      continue;
     }
    }

    //You may desire to have two or more field have some corresponding data
    //For instance,if field1 should have "Anjola" then field2 must have "16" / if field1 haves "Bayo" then field2 must have 30.
    if (!empty($constrainedData)) {
     if (in_array($field, array_keys($constrainedData))) {
      $params[] = $constrainedData[$field];
      continue;
     }
    }

    //We also consider that a column/field may just be that.And require fake data wheter from faker or you could define your own fake source and just return it
    //whenever the case is passed
    if (isset($details['length']) && is_int($details['length'])) {
     $length = $details['length'];
    } else if (!empty($this->length)) {
     $length = $this->length;
    }else{
     die(trigger_error("Length of field: <b>$field</b> not specified"));
    }
    $params[] = $this->generateFakerData($details['type'], $length);
   }
   $insert->execute($params);
  }
 }

 //this function helps loops through the associative array of faker functions(both follow come and user defined)
 protected function generateFakerData($type, $length) {
  if (array_key_exists($type, $this->fakerFunctions)) {
   return $this->fakerFunctions[$type]($length);
  } else {
   die(trigger_error("No faker function defined for type:<b> $type </b> \n"));
  }
 }

 //allows user to add their own custom function (to generate fake data)
 public function addCustomFunction(string $dataType, $callback) {
  if (!is_callable($callback)) {
   die(trigger_error("Only callables can be added \n"));
  };
  //validate the custom function being added
  $reflection = new \ReflectionFunction($callback);
  if(!$reflection->isClosure()){
   die(trigger_error("Custom Function must be an anonymous function(closure) not a normal named function"));
  }
  $parameters = $reflection->getParameters();
  if (count($parameters) == 1) {
   foreach ($parameters as $parameter) {
    if($parameter->getName() != 'length'){
     die(trigger_error('Your function must accept only 1(one) parameter called <b>$length</b> <br>'));
    }
   }
   //now we add the validated function
   $this->fakerFunctions[$dataType] = $callback; 
   return "Function added \n";
  } else {
   die(trigger_error('Your function must accept only one parameter which should be called <b> $length </b> <br>'));
  }
 }

}
?>