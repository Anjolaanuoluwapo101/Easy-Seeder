<?

namespace MySQLConnector;

class MySQLConnector {
 public $db_connection;
 public $db_name;
 public $db_host;
 public $db_username;
 public $db_password;


 public static array $supportedDataTypes = array(
  'tinyint',
  'smallint',
  'mediumint',
  'int',
  'integer',
  'bigint',
  'float',
  'double',
  'decimal',
  'date',
  'time',
  'datetime',
  'timestamp',
  'year',
  'char',
  'varchar',
  'tinytext',
  'text',
  'mediumtext',
  'longtext',
  'enum',
  'set',
  'binary',
  'varbinary',
  'tinyblob',
  'blob',
  'mediumblob',
  'longblob',
  'geometry',
  'point',
  'linestring',
  'polygon',
  'multipoint',
  'multilinestring',
  'multipolygon',
  'geometrycollection',
  'json',
  'bit'
 );
 
 protected $connection;

 public function __construct() {
  $this->db_connection = parseEnv()['DB_CONNECTION'];
  $this->db_name = parseEnv()['DB_NAME'];
  $this->db_host = parseEnv()['DB_HOST'];
  $this->db_username = parseEnv()['DB_USERNAME'];
  $this->db_password = parseEnv()['DB_PASSWORD'];

  if (!isset(parseEnv()['DB_CONNECTION'], parseEnv()['DB_NAME'], parseEnv()['DB_HOST'])) {
   return "DB_CONNECTION:not set";
  }
  $dsn = "$this->db_connection:dbname=$this->db_name;host=$this->db_host";

  try {
   echo "MySQL connection created.";
   $this->connection = new \PDO($dsn, $this->db_username, $this->db_password);
   $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  } catch (\PDOException $e) {
   echo 'Connection failed: ' . $e->getMessage();
  }
 }
 
 
 public function getConnection(){
  return $this->connection;
 }
}

?>