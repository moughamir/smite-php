<?php
	function get_api_session() {
		global $dbconn;
		$dev_id = "1234";
		$url = get_url("createsession", false);
		$response = file_get_contents($url);
		$response_decoded = json_decode($response);
		$session_id = $response_decoded->session_id;
		return $session_id;
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
		$dev_id = "1234";
		$auth_key = "01234567890123456789012345678901";
		$concat_string = $dev_id . $function . $auth_key . $date;
		return md5($concat_string);
	}
?>
