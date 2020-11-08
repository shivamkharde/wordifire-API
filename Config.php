<?php

// creating database connection
function createDatabaseConnection(){
    
    $HOST = "localhost";
    $USERNAME = "";
    $PASSWORD = "root";
    $DATABASE = "wordifire";
    
    $connection = mysqli_connect($HOST,$USERNAME,$PASSWORD,$DATABASE);
    
    if($connection){
        return $connection;
    }else{
        return "error";
    }
}



?>