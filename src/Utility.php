<?php
//Non-core functions.(Let's just put it like that.)

//parses the env file and returns it as an associative array
function parseEnv($filePath = __DIR__."/../.env") {
 if (file_exists($filePath)) {
  $envContent = file_get_contents($filePath);
  $envLines = explode("\n", $envContent);
  $envData = [];

  foreach ($envLines as $line) {
   $line = trim($line);
   if (!empty($line) && strpos($line, '=') !== false) {
    list($key, $value) = explode('=', $line, 2);
    $envData[$key] = $value;
   }
  }

  return $envData;
 } else {
  die(trigger_error(".env file not found,ensure it's placed in the seeder root directory"));
  //return false; // .env file not found
 }
}

//helps get a random row from a user defined two dimensional array-
function getConstraintedFields(array $array):array {
 $subarray = reset($array); // Get the first subarray to determine length
 $maxIndex = count($subarray) - 1;
 $randomIndex = rand(0, $maxIndex);
 $result = array();
 foreach ($array as $key => $values) {
  if (isset($values[$randomIndex])) {
   $result[$key] = $values[$randomIndex];
  }
 }
 return $result;
}

function getpreDefinedData($array, $field) {
 $result = array();
 if (array_key_exists($field, $array)) {
  $result[] = true; // Key exists
  $values = $array[$field];
  $randomIndex = array_rand($values);
  $result[] = $values[$randomIndex];
 } else {
  $result[] = false; // Key doesn't exist
  $result[] = null; // No value to return
 }
 return $result;
}


?>