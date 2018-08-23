<?php
include '../config/conex_manager.php';
#ini_set("display_errors", 0);
#ini_set('error_reporting', E_ALL|E_STRICT);

class system_users {
	public $conn;
	public function __construct() {
		$this->conn = new Connect();
	}
	#*************** Create New User Function
	public function post() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type application/json; charset=utf-8');
		try {
			$name        = $_POST['name'];
			$last_name   = $_POST['last_name'];
			$id_card     = $_POST['id_card'];
			$user_name   = $_POST['user_name'];
			$password    = $_POST['password'];
			$avatar      = $_POST['avatar'];
			$rol         = $_POST['rol'];
			$create_date = date('y-m-d H:i:s');
			#Validating user name
			$user_name_query = pg_fetch_object(pg_query("SELECT id FROM system.users WHERE user_name ='".$user_name."';"));
			if (!$user_name_query->id) {
				#Create new user
				$createUser = pg_query("INSERT INTO system.users (name, last_name, id_card, user_name, password, avatar, rol, create_date) VALUES ('".$name."', '".$last_name."', '".$id_card."', '".$user_name."', '".$password."','".$avatar."', '".$rol."','".$create_date."')");
				if (!$createUser) {
					$this->conn->close();
					http_response_code(404);
					die(json_encode(array("message" => "Wrong data"), JSON_UNESCAPED_UNICODE));
				} else {
					$this->conn->close();
					http_response_code(202);
					die(json_encode(array("message" => "Register succesfull"), JSON_UNESCAPED_UNICODE));
				}

			} else {
				$this->conn->close();
				http_response_code(404);
				die(json_encode(array('message' => 'Duplicate user name')));
			}
		} catch (Exception $e) {
			$this->conn->close();
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	#*************** Update User Function
	public function get() {
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');

		try {
			$id = $_POST['id'];
			#Search for user id
			if (!$id) {
				$user_query = pg_query("SELECT u.* FROM system.users AS u;");
				while ($row = pg_fetch_object($user_query)) {
					$users[] = $row;
				}
				http_response_code(200);
				die(json_encode($users));
			} else {
				$user_query = pg_fetch_object(pg_query("SELECT * FROM system.users WHERE id = '".$id."'"));
				http_response_code(404);
				die(json_encode($user_query));
			}
			$this->conn->close();
		} catch (Exception $e) {
			$this->conn->close();
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	#*************** Update User Function
	public function put() {
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');

		try {
			$id = $_POST['id'];
			#Search for user id
			$user_query  = pg_fetch_object(pg_query("SELECT * FROM system.users WHERE id = '".$id."'"));
			$name        = ($user_query->name == $_POST['name'])?$user_query->name:$_POST['name'];
			$last_name   = ($user_query->last_name == $_POST['last_name'])?$user_query->last_name:$_POST['last_name'];
			$id_card     = ($user_query->id_card == $_POST['id_card'])?$user_query->id_card:$_POST['id_card'];
			$user_name   = ($user_query->user_name == $_POST['user_name'])?$user_query->user_name:$_POST['user_name'];
			$password    = ($user_query->password == $_POST['password'])?$user_query->password:$_POST['password'];
			$avatar      = ($user_query->avatar == $_POST['avatar'])?$user_query->avatar:$_POST['avatar'];
			$rol         = ($user_query->rol == $_POST['rol'])?$user_query->rol:$_POST['rol'];
			$update_date = date('y-m-d H:i:s');
			$update      = pg_query("UPDATE system.users SET name = '".$name."', last_name = '".$last_name."', id_card = '".$id_card."', user_name = '".$user_name."', password = '".$password."', avatar = '".$avatar."', rol = '".$rol."', update_date = '".$update_date."'");
			http_response_code(404);
			die(json_encode($user_query));
			$this->conn->close();
		} catch (Exception $e) {
			$this->conn->close();
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	#Delete user
	public function delete() {
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$id    = $_POST['id'];
			$query = pg_query("DELETE FROM system.users WHERE id = '".$id."'");
			http_response_code(404);
			die(json_encode(array('message' => 'Delete successfull!')));
			$this->conn->close();
		} catch (Exception $e) {
			$this->conn->close();
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
}