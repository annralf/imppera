<?php

class Connect {
	public $conn = NULL;

	public function __construct() {

		$this->conn = pg_connect('host=185.44.66.53 port=5432 dbname=enkargo user=u_enkargo password=#enkargo#');

		if (!$this->conn) {
			http_response_code(500);
			die(json_encode(array('msg' => "No se estableció la conexión con la base de datos"), JSON_UNESCAPED_UNICODE));
		}
	}

	public function close() {
		pg_close($this->conn);
	}
}
