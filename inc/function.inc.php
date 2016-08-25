<?php
	function create_pin($pins, $token, $dest_board) {
		set_time_limit(1000);
		$url = "https://api.pinterest.com/v1/pins/?access_token=".$token."&fields=link%2Cnote%2Curl";

		foreach ($pins["data"] as $pin) {
			$data = array("board" => "$dest_board", "note" => $pin["note"], "image_url" => $pin["image"]["original"]["url"]);

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
		}
	}
?>