<?php
	
/**
 * @Author: anirudh
 * @Date:   2016-07-11 7:10:00
 * @Last Modified by:   Anirudh Goel
 * @Last Modified time: 2016-08-23 21:20:00
 */

require_once('inc/function.inc.php');

$response = array(
	'code' => 0,
	'response' => "Error accessing API"
);
$options_1 = array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    )
);
$context_1  = stream_context_create($options_1);
$token = $_GET['token'];
$source_board = $_GET['source_board'];
$destination_board = $_GET['destination_board'];

// $token = "jkkguygjo";
// $source_board = "anirudhgoel/colours";
// $source_board = "tanuagupta/wallpaper";
// $source_board = "ewrjio/wef";
// $destination_board = "anirudhgoel/trial";

$url = "https://api.pinterest.com/v1/boards/".$source_board."/pins/?access_token=".$token."&fields=note%2Cimage";

@$data_json = file_get_contents($url, false, $context_1);
$data = json_decode($data_json, true);
// print_r($data_json);

if ($data["data"]) {
	create_pin($data, $token, $destination_board);

	while ($data["page"]["next"]) {
		// echo("Continue <br>");
		$next_url = $data["page"]["next"];
		$data_json = file_get_contents($next_url, false, $context_1);
		$data = json_decode($data_json, true);

		create_pin($data, $token, $destination_board);
	}

	$response["code"] = 2;
	$response["response"] = "successful";
} else {
	$response["code"] = 1;
	$response["response"] = "Error adding Pins";
}

echo json_encode($response);
?>