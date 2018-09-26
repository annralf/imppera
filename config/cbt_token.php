<?php
session_start();
#include "cbt.php";
include "conex_manager.php";

class conex_manager {
	public $conn_db;
	public $client_id;
	public $client_secret_key;
	public $access_token;
	public $refresh_access_token;
	public $id_application;

	function __construct($id_application) {
		$this->id_application       = $id_application;
		$this->conn_db              = new Connect();
		$result                     = pg_fetch_object(pg_exec($this->conn_db->conn, "select * from cbt.shop where id = ".$this->id_application.";"));
		$this->client_id            = $result->application_id;
		$this->client_secret_key    = $result->secret_key;
		$this->access_token         = $result->access_token;
		$this->refresh_access_token = $result->refresh_access_token;
	}
	function get_application() {
		return array('client_id' => $this->client_id, 'client_secret_key' => $this->client_secret_key, 'access_token' => $this->access_token, 'refresh_access_token' => $this->refresh_access_token);
	}

	function set_access_token($access_token, $refresh_token, $time_refresh_token) {
		$application = pg_fetch_object(pg_query($this->conn_db->conn, "UPDATE cbt.shop SET  access_token='".$access_token."', refresh_access_token='".$refresh_token."' where id = ".$this->id_application.";"));
		return $access_token;
	}
	function get_access_token() {
		$result = pg_fetch_object(pg_exec($this->conn_db->conn, "select * from cbt.shop where id = ".$this->id_application.";"));
		return $result->access_token;
	}
	function refresh_access_token() {
		$params = array(
			'client_id'     => $this->client_id,
			'client_secret' => $this->client_secret_key,
			'grant_type'    => 'refresh_token',
			'refresh_token' => $this->refresh_access_token
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api-cbt.mercadolibre.com/oauth/token');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$response = json_decode(curl_exec($ch));
		curl_close($ch);
		$sql = "UPDATE cbt.shop  SET access_token='".$response->access_token."', refresh_access_token='".$response->refresh_token."', update_date= '".date("Y-m-d H:i:s")."' WHERE id ='".$this->id_application."';";
		try {
			pg_query($sql);
			return 1;
		} catch (Exception $e) {
			return 0;
		}
	}
	public function get_token($CODE, $client_id,$client_secret) {

		$VAR = [
			'grant_type'    => 'authorization_code',
			'client_id'     => $client_id,
			'client_secret' => $client_secret,
			'code'          => $CODE
		];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api-cbt.mercadolibre.com/oauth/token");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $VAR);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		$opciones = get_object_vars(json_decode($server_output, false));
		print_r($opciones);
		$refresh_token = $opciones['refresh_token'];
		$access_token  = $opciones['access_token'];
		$time_token    = $opciones['expires_in'];		
		curl_close($ch);

		$sql = "UPDATE cbt.shop  SET access_token='".$opciones['access_token']."', refresh_access_token='".$opciones['refresh_token']."', update_date= '".date("Y-m-d H:i:s")."' WHERE id ='".$this->id_application."';";

		try {
			pg_query($sql);
			//echo "==".$shop->name."==\n";
			return 1;
		} catch (Exception $e) {
			return 0;
		}
	}
}
$conn       = new Connect();
$query = pg_query($conn->conn, "SELECT * FROM cbt.shop WHERE id >2 ;");
while ($shop = pg_fetch_object($query)) {
	$shop_manager     = new conex_manager($shop->id);
	if (isset($_GET['code'])) {
		echo $shop_manager->get_token($_GET['code'], $shop->application_id,$shop->secret_key);
	}else{
		$application = $shop_manager->get_application();
		$shop_manager->refresh_access_token()."\n";
		##Para renovar el token se debe descomentar abajo y comentar arriba
		/**Active just for restart token**/
		//header("Location:https://cbt.mercadolibre.com/merchant/authorization/?client_id=".$shop->application_id."&response_type=code&redirect_uri=https://core.enkargo.com.co/config/cbt_token.php");
		//exit;	

	}
}
?>