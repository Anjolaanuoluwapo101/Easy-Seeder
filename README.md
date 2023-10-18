## Easy to use Database Seeder. 
<br>
Note: Uses PHP faker library(not the archived one) under the hood. <br>
Check here -> https://fakerphp.github.io/ <br>

### Currently Supports: 
<br>
-MySQL(MariaDB)<br>
-SQLite <br>
-Can definitely support more RDBMS due to it flexibity just contact me. <br>

### -INSTALLATION AND CONFIGURATION:
-Simply git clone this repo. <br>
-After that require the index.php file from your project. <br>
(that's require "path/to/seeder/index.php") <br>
-Next step is to set up the .env file <br>
-There's already one available so simply edit the values with yours. <br>
Note:The database.db present in the root folder (delete or ignore it) is an SQLite db <br>
-If you're working with an SQLite db, ensure that DB_DATABASE contains the relative file path to the SQLite DB from the .env file <br>
-If you're working with MySQL simply fill up the other keys the the appropriate values <br>


### -USAGE:
The interesting part ;) <br>

-To initialize the class for an SQLite connection ___(vital step) <br>
```
$seeder = new Seeder\Seeder('sqlite');
```
<br>
-To initialize the class for an SQLite connection ___(vital step) <br>

```
$seeder = new Seeder\Seeder('mysql');
```

<br>
-To set the table to be populated ___(vital step) <br>

```
$seeder->table = 'testDB'; 
```

<br>
-To set the fields of the table(Column names) ___(vital step) <br>

```
$seeder->setFields(array(
'name' => array("type" => "text","length"=>20),
'age' => array("type" => "int","length"=>2),
));
```

<br>

Let me explain further <br>
-'name' and 'age' in the above,are the column names. <br> <br>
-The description(child) array for each contains the description of the columns. <br> <br>
-The description array can accept the "type" and "length" attributes,and you probably guessed right. <br> <br>
-The "type" is required and should be any of the data types acceptable by either MySQL/SQLite (check the src/MySQLConnector or SQLiteConnector class to modify this) <br> <br>
-The "length" is optional and it helps limits the length of the data generated for that column <br> <br>
-You may decide not to specify the "length" in some of the defined fields... <br> <br>
-Instead,you can specify a general length <br>

```
$seeder->length = 10: //accepts an integer only 
```

### Note:
-If "length" attribute is present for a particular field,the will override this general length when generating fake data for that field. <br>

-You can specify hidden fields (fields that have been defined using Seeder\Seeder::setFields(array) but you want you still prevent them from being loaded with fake data); <br>

```
$seeder->hiddenFields = array('email',...,...); //accepts an array only
```

 <br>

### -Special Features 
-This library has what it call Constrained Data and Predefined Data feature <br>

### -Predefined Data:
-Let's say you have a particular column in your database(which you have specified using Seeder\Seeder::setFields(array)) called "gender"; <br>
-logically you will either want to fill it with 'male' or 'female'? <br> 
-this is where Predefined Data feature helps. <br>

```
$seeder->preDefinedData = array(
"gender" => array("male","female"),
); 
```

<br>

Ofcourse,you can specify more than one field that will utilize this Predefined Data feature <br>

```
$seeder->preDefinedData = array(
"gender" => array("male","female"),
"location" => array("Nigeria","Sweden"),
);
```

<br>
-The "location" column will only ever contain either "Nigeria" or "Sweden" <br>

### Note:
-The selection of Predefined Data for one column is completely independent of the other.(For dependency check the Constrained Data feature) <br>
-This is good for creating foreign key(that consist of one column only) <br>


### -Constrained Data:
-Let's say you have two/more columns where you want the data in one column to determine the data in another column <br>
-Let's say you have you have two columns like "gender" and "is_masculine" <br>
-logically if "gender" is male,then "is_masculine" should be "yes" right? and vice versa. <br>

```
$seeder->constrainedData = array(
 "gender" => array("male","female"),
 "is_masculine" => array("yes","no")
 ): 
 ```
 
 <br>

-Behind the scenes,the library simply pulls out a row from this 2-dimensional array provided <br>
-which would either be 

```
array("gender"=>"male","is_masculine" => "yes") or array("gender"=>"female","is_masculine" => "no")
```

<br>
### Note:
-This would be useful for composite primary/foreign key creation and certain Database Relationships <br>

### MUCH MORE IMPORTANT NOTICE: 
-You can not have a field present in both Predefined Data and Constrained Data.(Don't worry,the library throws a fatal error if that's done); <br>

-You can also create your own faker functions! <br>
-Only two limitations to this: <br>
-The function must be an anonymous function <br>
-The function can only accept one parameter and must be called $length <br>

```
$modifiedTextFakerFunction = function($length){
 return "static string";
}; 

$seeder->addCustomFunction('text',$modifiedTextFakerFunction);
//this will override any faker function for the data type 'text'; 
```

### -You have come this far!
-To populate the database after this,simply run <br>

```
echo $seeder->seed(n); // n is the number of records you want to create
```