<?php

namespace SQLiteConnector;

class SQLiteConnector{
 
 public static $supportedDataTypes = array(
    'text',
    'varchar',
    'char',
    'clob',
    'integer',
    'real',
    'float',
    'double',
    'numeric',
    'boolean',
    'date',
    'time',
    'datetime',
    'timestamp',
    'blob',
    'null',
    'int',
    'tinyint',
    'smallint',
    'mediumint',
    'bigint'
);
 
 protected $connection;
 
 public function __construct() {
   $dbpath = parseEnv()['DB_DATABASE'];
   if(!is_file($dbpath)){
    die(trigger_error("SQlite database doesn't exist,ensure path is set correctly in .env file"));
   }
  try {
   $this->connection = new \PDO("sqlite:$dbpath");
   $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
   echo "SQLite Connection created.";
  } catch (\PDOException $e) {
   die(trigger_error("Failed to connect to SQLite3 database: " . $e->getMessage()));
  }
 }
 
 public function getConnection(){
  return $this->connection;
 }

}

?>