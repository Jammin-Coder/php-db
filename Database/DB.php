<?php


namespace Database;

class DB
{
    public static $conn;
    public static function connect($dbConfigFile)
    {
        require $dbConfigFile;

        try {
            self::$conn = new \PDO("$dbType:host=$dbHost;dbname=$dbName", 
                $dbUsername, $dbPassword, $pdoOptions
            );
            
        } catch (\PDOException $e) {
            print "ERROR: Database connection error.";
            die();
        }
        
    }
    public static function query(string $query_str, array $query_bindings_array = null, bool $all = false)
    {   
        /**
         * Prepares a query and executes it securely.
         * 
         * DO NOT USE USER SUPPLIED VARIABLES INSIDE THE $query_str!!!! If you are using user supplied variables, structure your $query_str appropriately 
         * @param $query_str
         * 
         * The associative array of bindings for the SQL query, and is needed if using user supplied variables to avoid SQL injections
         * @param $query_bindings_array = null
         * 
         * Whether or not to return all of the results when running SELECT queries. If not supplied, it will only return one result
         * @param $all
         */

        $prepared_statement = self::$conn->prepare($query_str);
        $prepared_statement->execute($query_bindings_array);

        // Return all results if running a SELECT query.
        if ($all){
            return $prepared_statement->fetchAll();
        }
        
        // Return the first result when running a SELECT query.
        return $prepared_statement->fetch();
    }
}