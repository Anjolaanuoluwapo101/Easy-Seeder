##Easy to use Database Seeder.
Note: Uses PHP faker library(not the archived one) under the hood.
Check here -> https://fakerphp.github.io/

Currently Supports:
->MySQL(MariaDB)
->SQLite
->Can definitely support more RDBMS due to it flexibity just contact me.

-INSTALLATION AND CONFIGURATION:
->Simply git clone this repo.
->After that require the index.php file from your project.
(that's require "path/to/seeder/index.php")
-Next step is to set up the .env file
->There's already one available so simply edit the values with yours.
Note:The database.db present in the root folder (delete or ignore it) is an SQLite db
->If you're working with an SQLite db, ensure that DB_DATABASE contains the relative file path to the SQLite DB from the .env file
->If you're working with MySQL simply fill up the other keys the the appropriate values


-USAGE:
The interesting part ;)

-To initialize the class for an SQLite connection ___(vital step)
$seeder = new Seeder\Seeder('sqlite');

-To initialize the class for an SQLite connection ___(vital step)
$seeder = new Seeder\Seeder('mysql');

-To set the table to be populated ___(vital step)
$seeder->table = 'testDB';

-To set the fields of the table(Column names) ___(vital step)
$seeder->setFields(array(
'name' => array("type" => "text","length"=>20),
'age' => array("type" => "int","length"=>2),
));

let me explain...
->  'name' and 'age' in the above,are the column names.
->The description(child) array for each contains the description of tne columns.
->The description array can accept contain the "type" and "length" attributes,and you probably guessed right.
->The "type" is required and should be any of the data types acceptable by either MySQL/SQLite (check the src/MySQLConnector or SQLiteConnector class to modify this)
->The "length" is optional and it helps limits the length of the data generated for that column
->You may decide not to specify the "length" in some of the defined fields...
->Instead,you can specify a general length
$seeder->length = 10: //accepts an integer only
Note: If "length" attribute is present for a particular field,the will override this general length when generating fake data for that field.

-You can specify hidden fields (fields that have been defined using Seeder\Seeder::setFields(array) but you want you still prevent them from being loaded with fake data);
$seeder->hiddenFields = array('email',...,...); //accepts an array only

-Special Feature
->This library has what it call constrainedData and preDefinedData feature

-Prededined Data:

->Let's say you have a particular column in your database(which you have specified using Seeder\Seeder::setFields(array)) called "gender";
->logically you will either want to fill it with 'male' or 'female'?
->this is where preDefinedData feature helps.

$seeder->preDefinedData = array(
"gender" => array("male","female"),
);

Ofcourse,you can specify more than one field that will utilize this preDefinedData feature
$seeder->preDefinedData = array(
"gender" => array("male","female"),
"location" => array("Nigeria","Sweden"),
);
->The "location" column will only ever contain either "Nigeria" or "Sweden"
Note: The selection of preDefinedData for one column is completely independent of the other.(For dependency check the Constrained Data feature)
Note:This is good for creating foreign key(that consist of one column only)


-Constrained Data:

->Let's say you have two/more columns where you want the data in one column to determine the data in another column
->Let's say you have you have two columns like "gender" and "is_masculine"
->logically if "gender" is male,then "is_masculine" should be "yes" right? and vice versa.
$seeder->constrainedData = array(
 "gender" => array("male","female"),
 "is_masculine" => array("yes","no")
 ):

->Behind the scenes,the library simply pulls out a row from this 2-dimensional array provided
-> which would either be array("gender"=>"male","is_masculine" => "yes") or array("gender"=>"female","is_masculine" => "no")

Note:This would be useful for composite primary/foreign key creation and certain Database Relationships

MUCH MORE IMPORTANT NOTICE: You can not have a field present in both preDefinedData and constrainedData.(Don't worry,the library throws a fatal error if that's done);

-You can also create your own faker functions!
->Only two limitations to this:
->The function must be an anonymous function
->The function can only accept one parameter and must be called $length

$modifiedTextFakerFunction = function($length){
 return "static string";
};
$seeder->addCustomFunction('text',$modifiedTextFakerFunction); 
//this will override any faker function for the data type 'text';

-You have cme this far!
-To populate the database after this,simply run
echo $seeder->seed(n); // n is the number of records you want to create