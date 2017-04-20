<?php
	// vars
	$devId = "1234"; // Your dev key
	$authKey = "01234567890123456789012345678901"; // your API auth key

	function get_api_session() {
		global $dbconn;
		$dev_id = $devId;
		$url = get_url("createsession", false);
		$query = "SELECT session_id FROM session WHERE creation_time > CURRENT_TIMESTAMP - interval '15 minutes'";
		$results = pg_query($dbconn, $query);
		if (pg_num_rows($results) == 0) {
			$response = file_get_contents($url);
			$response_decoded = json_decode($response);
			$session_id = $response_decoded->session_id;
			$insert_query = "INSERT INTO session VALUES ('$session_id', CURRENT_TIMESTAMP)";
			pg_query($dbconn, $insert_query);
			return $session_id;
		} else {
			$row = pg_fetch_row($results);
			$session_id = $row[0];
			return $row[0];
		}
	}
	
	function get_url($function, $session) {
		if ($session) {
			$current_session = get_api_session();
		}
		$dev_id = "1234";
		return "http://api.smitegame.com/smiteapi.svc/$function" . "Json/$dev_id/"
			. get_signature($function, gmdate('YmdHis')) . ($session ? "/$current_session/" : "/")
			. gmdate('YmdHis');
	}
	
	function get_signature($function, $date) {
		$dev_id = $devId;
		$auth_key = $authKey;
		$concat_string = $dev_id . $function . $auth_key . $date;
		return md5($concat_string);
	}
?>
