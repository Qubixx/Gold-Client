<?php
function page() {
	$pageURL = 'http';
	if (isset($_SERVER["HTTPS"])) {
		if ($_SERVER["HTTPS"] == "on") {
			$pageURL .= "s";
		}
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	}
	else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

$extra = "";
$page = page();
if (substr_count($page, "?") > 0) {
	$explode = explode("?", $page);
	$extra = "?" . $explode[1];
}

$server = "showdown";
$url = "http://play.pokemonshowdown.com/~~" . $server . "/action.php" . $extra;

//get encoded variables
$postvars = "";
if (isset($_GET['post'])) {
	$postvars = urldecode($_GET['post']);
}
$postvarsarray = explode("|", $postvars);


if (@$_GET['act'] === 'dlteam') {
	header("Content-Type: text/plain");
	if (substr(@$_SERVER['HTTP_REFERER'], 0, 32) !== 'https://play.pokemonshowdown.com') {
		// since this is only to support Chrome on HTTPS, we can get away with a very specific referer check
		die("access denied");
	}
	echo base64_decode(@$_GET['team']);
	die();
}

//structure it
$fields_string = "";
foreach($postarray as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string,'& ');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($postarray));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);
