<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include_once "Config.php";

// create database connection
$connection = createDatabaseConnection();

if($connection != "error"){
    
    //get word data
    $result  = getWordData($connection);

    // storing and echoing data
    if($result != false){
        $data = array();
        $data['count'] = mysqli_num_rows($result);
        $data['data'] = array();
        while($row = mysqli_fetch_assoc($result)){
            $word = array(
                "id" => $row['id'],
                "word" => $row['word'],
                "defination" => $row['defination'],
                "pronounciation_audio" => $row['pronounciation_audio'],
                "pronounciation_text" => $row['pronounciation_text'],
                "origin" => $row['origin'],
                "example" => $row['example'],
                "example_source"=> $row['example_source'],
                "word_podcast" =>$row['word_podcast'],
                "timestamp" => $row['timestamp']
            );
            array_push($data['data'],$word);
        }
        http_response_code(200);
        echo json_encode($data);
    }else{
        http_response_code(200);
        echo json_encode(array(
            "status" => 204,
            "message" => "No Words Available"
        ));
    }
}else{
    http_response_code(200);
    echo json_encode(array(
        "status" => 500,
        "message" => "database connection error"
    ));
}

// function to get the word data from database
function getWordData($connection){

    $query = "SELECT * FROM `word_data` ORDER BY id DESC";
    $result = mysqli_query($connection,$query);

    if(mysqli_num_rows($result) > 0){
        return $result;
    }else{
        return false;
    }
}

?>