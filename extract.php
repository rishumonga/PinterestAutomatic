<?php
	
/**
 * @Author: anirudh
 * @Date:   2016-07-11 7:10:00
 * @Last Modified by:   Anirudh Goel
 * @Last Modified time: 2016-06-17 00:23:10
 */


$options_1 = array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    )
);
$context_1  = stream_context_create($options_1);
// $token = $_GET['token'];																										// User Token
// $source_board = $_GET['source_board'];
// $destination_board = $_GET['destination_board'];

// $token = "AfD5YPj_adw_kVQRwQT9PQDsbk1-FGA1nmOh5S5DLHRgt0BD6QAAAAA";
$token = "jkkguygjo";
// $source_board = "anirudhgoel/colours";
$source_board = "tanuagupta/wallpaper";
// $source_board = "ewrjio/wef";
$destination_board = "anirudhgoel/trial";

$url = "https://api.pinterest.com/v1/boards/".$source_board."/pins/?access_token=".$token."&fields=note%2Cimage";

$data_json = file_get_contents($url, false, $context_1);
print_r($data_json);
$data = json_decode($data_json, true);

if ($data["data"]) {
	// create_pin($data, $token, $destination_board);

	while ($data["page"]["next"]) {
		echo("Continue <br>");
		$next_url = $data["page"]["next"];
		// echo($next_url."<br>");
		$data_json = file_get_contents($next_url, false, $context_1);
		$data = json_decode($data_json, true);

		// create_pin($data, $token, $destination_board);
	}

	echo("Done");
} else {
	echo($data["message"]);
}




function create_pin($pins, $token, $dest_board) {
	set_time_limit(1000);
	$url = "https://api.pinterest.com/v1/pins/?access_token=".$token."&fields=link%2Cnote%2Curl";

	foreach ($pins["data"] as $pin) {
		$data = array("board" => "$dest_board", "note" => $pin["note"], "image_url" => $pin["image"]["original"]["url"]);

		// use key 'http' even if you send the request to https://...
		$options_2 = array(
		    "ssl"=>array(
		        "verify_peer"=>false,
		        "verify_peer_name"=>false,
		    ),
		    "http" => array(
		        "header"  => "Content-type: application/x-www-form-urlencoded\r\n",
		        "method"  => 'POST',
		        "content" => http_build_query($data)
		    )
		);
		$context = stream_context_create($options_2);
		$result = file_get_contents($url, false, $context);
		echo("Pinned !");
	}
}

?>