<?php

$url = 'http://slack.local/showroom.php';
$lock = ' true';
$fields = array(
	'token' => 'yourToken',
	'command' => '/showroom',
	'user_name' => 'johnDoe',
	'text' => 'dev-web' . $lock
);

//url-ify the data for the POST
$fields_string = '';
foreach($fields as $key=>$value) {
  $fields_string .= $key.'='.$value.'&';
}
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

//execute post
printf(
  curl_exec($ch)
);

//close connection
curl_close($ch);
