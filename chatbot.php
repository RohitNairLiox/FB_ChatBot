<?php

$access_token="xxxxx";

$verify_token = "xxxxx";

$hub_verify_token = null;

$wordList_greeting = array("hi", "hii", "hello", "hey");

if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}

if ($hub_verify_token === $verify_token) {
    echo $challenge;
}

$input = json_decode(file_get_contents('php://input'), true);

$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];
$message = strtolower($message);
if ($message) {
	if (in_array($message, $wordList_greeting)) {
			$message_Response = 'Hi! Welcome to my page';
		}
}
else
{
	$message_Response = "Hey I'm just a ChatBot. Wait for a real human to reply!";
}
//API Url
$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$access_token;


//Initiate cURL.
$ch = curl_init($url);
//JSON DATA
$jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
    	"text":"'.$message_Response.'",
      }
}';

//Encode the array into JSON.
$jsonDataEncoded = $jsonData;

//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);

//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

//Execute the request
if(!empty($message)){
    $result = curl_exec($ch);
}
curl_close($ch);
?>
