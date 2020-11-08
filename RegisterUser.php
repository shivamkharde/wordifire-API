<?php
include_once "Config.php";
if($_GET['player_id']){

    // creating database connection
    $connection = createDatabaseConnection();

    if($connection != "error"){

        // sanitizing input
        $player_id = mysqli_real_escape_string($connection,$_GET['player_id']);

        // storing player id in database
        $result = storePlayerId($connection,$player_id);

        if($result == 1){
            http_response_code(200);
            echo json_encode(array(
                "status"=>200,
                "message" => "you are successfully registered",
            ));
        }else{
            // if player id is not inserted
            http_response_code(200);
            echo json_encode(array(
                "status" => 500,
                "message" => "something went wrong while registering "
            )); 
        }
    }else{
        // if database won't connect
        http_response_code(200);
        echo json_encode(array(
            "status" => 404,
            "message" => "database connection error"
        ));
    }


}else{
    // if parameters are not passed
    http_response_code(200);
    echo json_encode(array(
        "status" => 404,
        "message" => "insufficient parameters"
    ));
}

// function to store player_id in database
function storePlayerId($connection,$player_id){

    $query = "INSERT into `notification_configurations` VALUES (null,'$player_id',CURDATE())";
    $result = mysqli_query($connection,$query);

    if(mysqli_affected_rows($connection) > 0){
        return mysqli_affected_rows($connection);
    }else{
        return 0;
    }
}
?>