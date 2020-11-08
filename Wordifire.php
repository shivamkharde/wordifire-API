<?php
//main url of "word of the day website"
$URL = "https://www.dictionary.com/e/word-of-the-day/";

// getting whole html of webiste
$websiteHTML = file_get_contents($URL);

//creating dom document object
$dom = new domDocument;

//loading html
@$dom->loadHTML($websiteHTML);

// getting word data
$data = getWordData($dom);

// creating database connection
$connection = createDatabaseConnection();

// getting all player ids 
$player_ids = getPlayerIds($connection);

// sending notification
sendNotification($data['word'],$data['defination'],$player_ids);

// storing data in database
storeWordData($connection,$data);
// echoing data
print_r($data);


// function to get the timestamp of the word
function getWordTimestamp($dom){

    $timestamp = "";
    // getting date of today
    foreach($dom->getElementsByTagName('div') as $divtag){

        if($divtag->getAttribute('class')=="otd-item-headword__date"){
            $timestamp =  trim($divtag->textContent);
            break;
        }
    }
    return $timestamp;
}

// function to ge the word of the day
function getWordOfTheDay($dom){
    
    $word = "";
    // getting word 
    foreach ($dom->getElementsByTagName('div') as $divtag) {
        
        if($divtag->getAttribute('class') == "otd-item-headword__word"){
            $word = trim($divtag->textContent);
            break;
        }
    }
    return $word;
}

// function to get the defination of the word
function getDefination($dom){

    $defination = "";
    // getting word defination
    foreach ($dom->getElementsByTagName('div') as $divtag) {
            
        if($divtag->getAttribute('class') == "otd-item-headword__pos"){
            $definationArray = explode("\n",trim($divtag->textContent));
            $len = count($definationArray);
            $defination = trim($definationArray[$len-1]);
            break;
        }
    }
    return $defination;
}

// function to get the pronounciation audio
function getWordPronounciationAudio($dom){
    
    $audio = "";
    // getting audio file of word
    foreach ($dom->getElementsByTagName('a') as $divtag) {
            
        if($divtag->getAttribute('class') == "otd-item-headword__pronunciation-audio"){
            $audio = trim($divtag->getAttribute('href'));
            break;
        }
    }    
    return $audio;
}

// function to get the pronounciation text
function getWordPronounciationText($dom){
    
    $audioText = "";
    // getting audio text of word
    foreach ($dom->getElementsByTagName('div') as $divtag) {
            
        if($divtag->getAttribute('class') == "otd-item-headword__pronunciation"){
            $audioText = trim($divtag->textContent);
            break;
        }
    }    
    return $audioText;
}

// function to get the origin of the word
function getWordOrigin($dom){

    $originText = "";
    // getting origin of the word
    foreach ($dom->getElementsByTagName('div') as $divtag) {
            
        if($divtag->getAttribute('class') == "wotd-item-origin__content wotd-item-origin__content-full"){
            $originArray = explode("\n",trim($divtag->textContent));
            $len = count($originArray);
            $originText = trim($originArray[$len-1]);
            break;
        }
    }    
    return $originText;
}

// function to get the example of word
function getWordExample($dom){

    $example = "";
    // getting origin of the word
    foreach ($dom->getElementsByTagName('div') as $divtag) {
            
        if($divtag->getAttribute('class') == "wotd-item-example__content"){
            $example = trim($divtag->textContent);
            break;
        }
    }    
    return $example;
}

// function to get the example source of word
function getWordExampleSource($dom){

    $exampleSource = "";
    // getting origin of the word
    foreach ($dom->getElementsByTagName('div') as $divtag) {
            
        if($divtag->getAttribute('class') == "wotd-item-example__source"){
            $exampleSource = trim($divtag->textContent);
            break;
        }
    }    
    return $exampleSource;
}

// function to get word podcast
function getWordPodcast($dom){

    $wordPodcast = "";
    // getting origin of the word
    foreach ($dom->getElementsByTagName('div') as $divtag) {
            
        if($divtag->getAttribute('class') == "wotd-item-podcast"){
            $wordPodcast = trim($divtag->getAttribute('data-url'));
            break;
        }
    }    
    return $wordPodcast;
}

// function to get total word data in json format
function getWordData($dom){

    // getting "word of the day" data
    $timestamp = getWordTimestamp($dom);
    $word = getWordOfTheDay($dom);
    $defination = getDefination($dom);
    $pronounciationAudio = getWordPronounciationAudio($dom);
    $pronounciationText = getWordPronounciationText($dom);
    $origin = getWordOrigin($dom); 
    $example = getWordExample($dom);
    $exampleSource = getWordExampleSource($dom);
    $wordPodcast = getWordPodcast($dom);

    // making json array of data
    $data = array(
        "timestamp"=>$timestamp,
        "word"=>$word,
        "defination"=>$defination,
        "pronounciationAudio"=>$pronounciationAudio,
        "pronounciationText"=>$pronounciationText,
        "origin"=>$origin,
        "example"=>$example,
        "exampleSource"=>$exampleSource,
        "wordPodcast"=>$wordPodcast
    );

    return $data;
}

// function to send the notification
function sendNotification($word,$defination,$player_ids){

    $headings = array(
        "en" => $word
    );
    $content = array(
        "en" => $defination
    );
    
    $fields = array(
        'app_id' => "f68a5232-0689-4d2e-86a9-c822436e942f",
        'include_external_user_ids' => $player_ids,
        'contents' => $content,
        'headings' => $headings
    );
    
    $fields = json_encode($fields);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
    'Authorization: Basic MjJiOWZiYzEtOTdlZi00NzgyLTllODctYzNiMjI5MWYxY2Jl'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    // print_r($response);
    curl_close($ch);
}

// function to get the all player ids available in db
function getPlayerIds($connection){

    $player_ids = array();
    $query = "SELECT * FROM `notification_configurations`";

    $result = mysqli_query($connection,$query);
    
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            array_push($player_ids,$row['player_id']);
        }
        return $player_ids;
    }else{
        return false;
    }
}

// function to store data in database
function storeWordData($connection,$data){
    $isAvailable = checkWordDataAvailablity($connection,$data["word"]);

    if($isAvailable == 0){
        // extracting data
        $word = $data['word'];
        $defination = $data['defination'];
        $pronounciationAudio = $data['pronounciationAudio'];
        $pronounciationText = $data['pronounciationText'];$origin = $data['origin'];
        $example = $data['example'];
        $exampleSource = $data['exampleSource'];
        $wordPodcast = $data['wordPodcast'];
        $timestamp = $data['timestamp'];

        $query = "INSERT INTO `word_data`(`id`, `word`, `defination`, `pronounciation_audio`, `pronounciation_text`, `origin`, `example`, `example_source`, `word_podcast`, `timestamp`) VALUES (null,'$word','$defination','$pronounciationAudio','$pronounciationText','$origin','$example','$exampleSource','$wordPodcast','$timestamp')";

        $result = mysqli_query($connection,$query);

    }else{
        // already available
    }
}

// function to see if word data is already available in database
function checkWordDataAvailablity($connection,$word){
    
    $query = "SELECT * FROM `word_data` WHERE word = '$word'";

    $result = mysqli_query($connection,$query);
    echo mysqli_num_rows($result);
    if(mysqli_num_rows($result) > 0){
        return 1;
    }else{
        return 0;
    }
}

//function to create database connection
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