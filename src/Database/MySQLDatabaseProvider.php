<?php declare(strict_types = 1);

namespace GTheatre\Database;

class MySQLDatabaseProvider implements DatabaseProvider
{
   private $dbProvider;

   public function __construct()
   {
//this is the ClearDB database url that is connected to our Heroku app
        $CLEARDB_DATABASE_URL = "mysql://b246c25ab72aba:3c0dafda@us-cdbr-iron-east-04.cleardb.net/heroku_d0dc4a6713d6673?reconnect=true";
        
        // parse the url for the various components and 
        // save the components required for a connection
        $url      = parse_url($CLEARDB_DATABASE_URL);
        $server   = $url["host"];
        $username = $url["user"];
        $password = $url["pass"];
        $db       = substr($url["path"], 1);

        // var_dump displays all the components of a variable
        // Note: useful for testing; uncomment to see the variable components
        // var_dump($url);


        // NOTE: to test in the local database comment out the first dbProvider line and 
        // uncomment the second dbProvider 
            // this is for remote connection to ClearDB!
        $this->dbProvider = new \MySQLi($server, $username, $password, $db);

        // if ($this->dbProvider) {
        //     var_dump($this->dbProvider);
        //     echo "YAAAA it works";
        // }
            // this is for localhost testing!
        //$this->dbProvider = new mysqli("localhost", "root","","GTheatre");

        // if our connection fails a connect_errno exists 
        if ($this->dbProvider->connect_errno) {
    
            echo "Sorry, this website is experiencing problems.";
            echo "Error: Failed to make a MySQL connection, here is why: \n";
            echo "Errno: " . $mysqli->connect_errno . "\n";
            echo "Error: " . $mysqli->connect_error . "\n";
            exit;
        };

    }

    public function selectQuery($query){
        $queryResult = $this->dbProvider->query($query);

        var_dump($queryResult);

        $queryArr = mysqli_result::fetch_array($queryResult, MYSQLI_ASSOC);

        return $queryArr;

    }

    public function selectMultipleRowsQuery($q){

        $queryResult = $this->dbProvider->query($q);
        $queryArr = mysqli_result::fetch_all($queryResult, MYSQLI_ASSOC);

        return $queryArr;
    }

    public function insertQuery($q) {

        $queryResult = $this->dbProvider->query($q);

        return $queryResult;

    }

    public function updateQuery($q) {

        return $this->insertQuery($q);

    }

    public function applyQueries($qArr) {

        $this->dbProvider->autocommit(FALSE);
        foreach( $qArr as $query) {
            $queryResult = $this->dbProvider->query($query);

            if (!$queryResult) {
                return false;
            }
        }
        $this->dbProvider->commit();
        return true;
    }

}


?>