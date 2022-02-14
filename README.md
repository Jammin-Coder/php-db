# php-db
An organized way to interact with a database in PHP.

# Usage:
Download the `Database`, put it somewhere in your PHP project that is accessible by the an autoloader.  
Then simply `use` the `DB` class in your PHP file, and initiate a connection to the database like this:  
```php
<?php

use Database\DB;

DB::connect('/path/to/db/db_connection_file.php');

```

The `db_connection_file` should be in a directory that is NOT accessible to the web server, and it should contain these variables:    
```php
<?php

$dbPassword = 'db_password'; // The password for your database user
$dbUsername = "username"; // The username you want to use for database connections
$dbName = "db_name"; // The name of the database
$dbHost = "db_host"; // The host name (IP address) of the database 
$dbType = "db_type"; // The type of database you want to use. Check out the list of supported DB drivers https://www.php.net/manual/en/pdo.drivers.php
$pdoOptions = array (PDO::ATTR_PERSISTENT => true); // Any connection options, stored in an assocciative array.
```

## Making queries:

The general query syntax is as follows:  
```php
DB::query(string $sql_query, array $query_bindings_array = null, bool $all = false);
```  
You can use any SQL query in the `$sql_query` param. If you are using user supplied variables to preform query operations, then you must supply a `$query_bindings_array` to avoid SQL injection.  
If you are preforming a `SELECT` query, by default the program only returns the first matching row; if you want to get all rows then you can optionally provide the `$all = true` parameter.  
If the provided SQL statement is not meant to return any rows, then the `query` method will return `null`.   

Here are some examples:
```php

use Database\DB;

DB::connect('/path/to/db/db_connection_file.php');

// Inserts a new muffin into the database. This does not return anything, it just executes the query.
DB::query("INSERT INTO muffins (muffin_type, muffin_size) VALUES ('Blueberry', 'Medium')");

// Binding query parameters to prevent SQLi.
// In this case we are assuming that we're extracting a muffin type and size that the user provided, so we must 
// bind the $userSuppliedMuffinType and $userSuppliedMuffinSize to "muffin_type" and "muffin_size" to prevent SQL injection.
DB::query(
  "INSERT INTO muffins (muffin_type, muffin_size) VALUES (:muffin_type, :muffin_size);",
  [
    "muffin_type" => $userSuppliedMuffinType,
    "muffin_size" => $userSuppliedMuffinSize
  ]
);

// Selecting items from the DB.


// Get the first muffin that has the muffin_type of `Oat Bran`.
$oatBranMuffin = DB::query("SELECT * FROM muffins WHERE muffin_type = 'Oat Bran';");

// Get all oat bran muffins
$allOatBranMuffins = DB::query("SELECT * FROM muffins WHERE muffin_type = 'Oat Bran';", $all = true);

// Selecting values based on parameters supplied by user

// Get all muffins of type supplied by user
$muffins = DB::query(
  "SELECT * FROM muffins WHERE muffin_type = (:muffin_type)",
  [
    "muffin_type" = $userSuppliedMuffinType
  ],
  $all = true
);
```  

I will add more methods for queries in the future!
