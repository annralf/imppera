<?php
#set_time_limit(20000);
class CBT {

	/**
	 * @version 1.1.0
	 */
	const VERSION = "1.1.0";

	/**
	 * @var $API_ROOT_URL is a main URL to access the Meli API's.
	 * @var $AUTH_URL is a url to redirect the user for login.
	 */

	public $header                 = false;
	protected static $API_ROOT_URL = "https://api.mercadolibre.com";
	protected static $OAUTH_URL    = "/oauth/token";
	public static $AUTH_URL        = array(
		"MLA" => "https://auth.mercadolibre.com.ar", // Argentina
		"MLB" => "https://auth.mercadolivre.com.br", // Brasil
		"MCO" => "https://auth.mercadolibre.com.co", // Colombia
		"MCR" => "https://auth.mercadolibre.com.cr", // Costa Rica
		"MEC" => "https://auth.mercadolibre.com.ec", // Ecuador
		"MLC" => "https://auth.mercadolibre.cl", // Chile
		"MLM" => "https://auth.mercadolibre.com.mx", // Mexico
		"MLU" => "https://auth.mercadolibre.com.uy", // Uruguay
		"MLV" => "https://auth.mercadolibre.com.ve", // Venezuela
		"MPA" => "https://auth.mercadolibre.com.pa", // Panama
		"MPE" => "https://auth.mercadolibre.com.pe", // Peru
		"MPT" => "https://auth.mercadolibre.com.pt", // Prtugal
		"MRD" => "https://auth.mercadolibre.com.do"// Dominicana
	);

	/**
	 * Configuration for CURL
	 */
	public static $CURL_OPTS = array(
		CURLOPT_USERAGENT      => "MELI-PHP-SDK-1.1.0",
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_TIMEOUT        => 360,
	);

	public $redirect_code = "https://181.58.30.117/core_enkargo/frontend/production/index_1.html";
	public $client_id;
	protected $client_secret;
	public $access_token;
	protected $refresh_token;
	public $redirect_uri = "https://core.enkargo/core_enkargo/models/conex_manager";

	/**
	 * Constructor method. Set all variables to connect in Meli
	 *
	 * @param string $client_id
	 * @param string $client_secret
	 * @param string $access_token
	 * @param string $refresh_token
	 */

	#public function __construct($client_id, $client_secret, $access_token = null, $refresh_token = null) {
	public function __construct($client_id, $client_secret, $access_token, $refresh_token) {
		$this->client_id     = $client_id;
		$this->client_secret = $client_secret;
		$this->access_token  = $access_token;
		$this->refresh_token = $refresh_token;
	}

	public function sendLogin() {
		header($URL_RESPONSE);
	}

	public function searchCode($url = null) {

		$host    = $_SERVER["HTTP_HOST"];
		$urlHost = $_SERVER["REQUEST_URI"];

		if ($url == null) {
			$UrlActual = "https://181.58.30.117/core_enkargo/frontend/production/index_1.html";
		} else {
			$UrlActual = $url;
		}

		$this->client_id     = $this->client_id;
		$this->client_secret = $this->client_secret;
		$this->redirect_uri  = $UrlActual;

		header("Location:https://cbt.mercadolibre.com/merchant/authorization/?client_id=".$this->client_id."&response_type=code&redirect_uri=".$UrlActual."");

	}

	public function __inicializar($code = null) {

		if (!$code) {
			#$this->client_id     = $client_id;
			#$this->client_secret = $client_secret;
			/*	$this->redirect_uri  = "https://www.enkargo.com.co:8080/CBT_core/conex.php";*/
			header("Location:https://cbt.mercadolibre.com/merchant/authorization/?client_id=".$this->client_id."&response_type=code&redirect_uri=https://core.enkargo.com.co/core_enkargo/models/conex_manager.php");
			exit;
		}

		if ($code) {

			if ($code && !($_SESSION['access_token'])) {
				// If the code was in get parameter we authorize
				$user = $this->authorize($code, 'https://localhost/testML/ejemplo1.php');

				// Now we create the sessions with the authenticated user
				$_SESSION['access_token']  = $user['body']->access_token;
				$_SESSION['expires_in']    = time()+$user['body']->expires_in;
				$_SESSION['refresh_token'] = $user['body']->refresh_token;
			} else {
				// We can check if the access token in invalid checking the time
				if ($_SESSION['expires_in'] < time()) {
					try {
						// Make the refresh proccess
						$refresh = $this->refreshAccessToken();
						// Now we create the sessions with the new parameters
						$_SESSION['access_token']  = $refresh['body']->access_token;
						$_SESSION['expires_in']    = time()+$refresh['body']->expires_in;
						$_SESSION['refresh_token'] = $refresh['body']->refresh_token;
					} catch (Exception $e) {
						echo "Exception: ", $e->getMessage(), "\n";
					}
				}
			}

			$this->access_token  = $_SESSION['access_token'];
			$this->refresh_token = $_SESSION['refresh_token'];

		}

	}

	/**
	 * Return an string with a complete Meli login url.
	 * NOTE: You can modify the $AUTH_URL to change the language of login
	 *
	 * @param string $redirect_uri
	 * @return string
	 */
	public function getAuthUrl($redirect_uri, $auth_url) {
		$this->redirect_uri = $redirect_uri;
		$params             = array("client_id" => $this->client_id, "response_type" => "code", "redirect_uri" => $redirect_uri);
		$auth_uri           = $auth_url."/authorization?".http_build_query($params);
		return $auth_uri;
	}

	/**
	 * Executes a POST Request to authorize the application and take
	 * an AccessToken.
	 *
	 * @param string $code
	 * @param string $redirect_uri
	 *
	 */
	public function authorize($code, $redirect_uri) {

		if ($redirect_uri) {
			$this->redirect_uri = $redirect_uri;
		}

		$body = array(
			"grant_type"    => "authorization_code",
			"client_id"     => $this->client_id,
			"client_secret" => $this->client_secret,
			"code"          => $code,
			"redirect_uri"  => $this->redirect_uri
		);

		$opts = array(
			CURLOPT_POST       => true,
			CURLOPT_POSTFIELDS => $body,
		);

		$request = $this->execute(self::$OAUTH_URL, $opts);

		if ($request["httpCode"] == 200) {
			$this->access_token = $request["body"]->access_token;

			if ($request["body"]->refresh_token) {
				$this->refresh_token = $request["body"]->refresh_token;
			}

			return $request;

		} else {
			return $request;
		}
	}

	/**
	 * Execute a POST Request to create a new AccessToken from a existent refresh_token
	 *
	 * @return string|mixed
	 */
	public function refreshAccessToken() {

		if ($this->refresh_token) {
			$body = array(
				"grant_type"    => "refresh_token",
				"client_id"     => $this->client_id,
				"client_secret" => $this->client_secret,
				"refresh_token" => $this->refresh_token
			);

			$opts = array(
				CURLOPT_POST       => true,
				CURLOPT_POSTFIELDS => $body,
			);

			$request = $this->execute(self::$OAUTH_URL, $opts);

			if ($request["httpCode"] == 200) {
				$this->access_token = $request["body"]->access_token;

				if ($request["body"]->refresh_token) {
					$this->refresh_token = $request["body"]->refresh_token;
				}

				return $request;

			} else {
				return $request;
			}
		} else {
			$result = array(
				'error'    => 'Offline-Access is not allowed.',
				'httpCode' => null
			);
			return $result;
		}
	}

	/**
	 * Execute a GET Request
	 *
	 * @param string $path
	 * @param array $params
	 * @param boolean $assoc
	 * @return mixed
	 */
	public function get($path, $params = null, $assoc = false) {

		//$exec = $this->execute($path, null, $params, $assoc);

		/*
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$VAR);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_close ($ch);
	return  $server_output = curl_exec ($ch);

	curl_setopt($this->curl, CURLOPT_URL, $url);
	curl_setopt($this->curl, CURLOPT_POST,false);
	curl_setopt($this->curl, CURLOPT_HEADER, $follow);
	curl_setopt($this->curl, CURLOPT_REFERER, '');
	curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, $follow);
	 */
	}

	/**
	 * Execute a POST Request
	 *
	 * @param string $body
	 * @param array $params
	 * @return mixed
	 */
	public function post($URL, $body = null, $VAR) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $VAR);
		#curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_close($ch);
		return $server_output = curl_exec($ch);

	}

	/** SENPOST
	 */

	public function sendPost($URL, $VAR) {
		//datos a enviar

		//url contra la que atacamos
		$ch = curl_init($URL);
		//a true, obtendremos una respuesta de la url, en otro caso,
		//true si es correcto, false si no lo es
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//establecemos el verbo http que queremos utilizar para la petición
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		//enviamos el array data
		curl_setopt($ch, CURLOPT_POSTFIELDS, $VAR);
		//obtenemos la respuesta
		if ($this->header) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
		}

		$response = curl_exec($ch);
		// Se cierra el recurso CURL y se liberan los recursos del sistema
		curl_close($ch);

		return $response;

	}

	public function sendGet($URL, $VAR) {
		//datos a enviar

		$ch = curl_init($URL);
		//a true, obtendremos una respuesta de la url, en otro caso,
		//true si es correcto, false si no lo es
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//establecemos el verbo http que queremos utilizar para la petición
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		if ($this->header) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
		}

		//enviamos el array data
		if ($VAR == null) {

		} else {
			$VAR = http_build_query($VAR);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $VAR);
		}

		//obtenemos la respuesta
		#curl_setopt($ch, CURLOPT_HEADER, 1);
		/*Porfa checa
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
		 */
		//obtenemos la respuesta
		$response = curl_exec($ch);
		// Se cierra el recurso CURL y se liberan los recursos del sistema
		curl_close($ch);

		return $response;

	}

	public function sendPut($URL, $VAR) {
		//datos a enviar

		$ch = curl_init($URL);
		//a true, obtendremos una respuesta de la url, en otro caso,
		//true si es correcto, false si no lo es
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//establecemos el verbo http que queremos utilizar para la petición
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		//enviamos el array data
		curl_setopt($ch, CURLOPT_POSTFIELDS, $VAR);

		if ($this->header) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
		}
		//obtenemos la respuesta
		$response = curl_exec($ch);
		// Se cierra el recurso CURL y se liberan los recursos del sistema
		curl_close($ch);

		return $response;

	}

	/**
	 * Execute a PUT Request
	 *
	 * @param string $path
	 * @param string $body
	 * @param array $params
	 * @return mixed
	 */
	public function put($path, $body = null, $params) {
		$body = json_encode($body);
		$opts = array(
			CURLOPT_HTTPHEADER    => array('Content-Type: application/json'),
			CURLOPT_CUSTOMREQUEST => "PUT",
			CURLOPT_POSTFIELDS    => $body
		);

		$exec = $this->execute($path, $opts, $params);

		return $exec;
	}

	/**
	 * Execute a DELETE Request
	 *
	 * @param string $path
	 * @param array $params
	 * @return mixed
	 */
	public function delete($path, $params) {
		$opts = array(
			CURLOPT_CUSTOMREQUEST => "DELETE",
		);

		$exec = $this->execute($path, $opts, $params);

		return $exec;
	}

	/**
	 * Execute a OPTION Request
	 *
	 * @param string $path
	 * @param array $params
	 * @return mixed
	 */
	public function options($path, $params = null) {
		$opts = array(
			CURLOPT_CUSTOMREQUEST => "OPTIONS",
		);

		$exec = $this->execute($path, $opts, $params);

		return $exec;
	}

	/**
	 * Execute all requests and returns the json body and headers
	 *
	 * @param string $path
	 * @param array $opts
	 * @param array $params
	 * @param boolean $assoc
	 * @return mixed
	 */
	public function execute($path, $opts = array(), $params = array(), $assoc = false) {
		$uri = $this->make_path($path, $params);

		$ch = curl_init($uri);
		curl_setopt_array($ch, self::$CURL_OPTS);

		if (!empty($opts)) {
			curl_setopt_array($ch, $opts);
		}

		$return["body"]     = json_decode(curl_exec($ch), $assoc);
		$return["httpCode"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		return $return;
	}

	/**
	 * Check and construct an real URL to make request
	 *
	 * @param string $path
	 * @param array $params
	 * @return string
	 */
	public function make_path($path, $params = array()) {
		if (!preg_match("/^http/", $path)) {
			if (!preg_match("/^\//", $path)) {
				$path = '/'.$path;
			}
			$uri = self::$API_ROOT_URL.$path;
		} else {
			$uri = $path;
		}

		if (!empty($params)) {
			$paramsJoined = array();

			foreach ($params as $param => $value) {
				$paramsJoined[] = "$param=$value";
			}
			$params = '?'.implode('&', $paramsJoined);
			$uri    = $uri.$params;
		}

		return $uri;
	}

	public function getToken__($CODE) {

		$VAR = [
			'grant_type'    => 'authorization_code',
			'client_id'     => $this->client_id,
			'client_secret' => $this->client_secret,
			'code'          => $CODE
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api-cbt.mercadolibre.com/oauth/token");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $VAR);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);

		$opciones = get_object_vars(json_decode($server_output, false));

		$this->refresh_token = $opciones['refresh_token'];
		$this->token_name    = $opciones['access_token'];
		$this->time_token    = $opciones['expires_in'];

		$_SESSION['access_token']  = $this->token_name;
		$_SESSION['refresh_token'] = $this->refresh_token;
		$_SESSION['time_token']    = $this->time_token+time();

		#setcookie('access_token', $this->token_name);
		#setcookie('refresh_token', $this->refresh_token);
		#setcookie('time_token', $_SESSION['time_token']);

		#echo "<pre>";
		#print_r($_COOKIE);
		#print_r($_SESSION);
		#print_r($opciones);
		#echo "</pre>";

		curl_close($ch);

		//return $server_output;

	}

	public function checkToken() {

		if ($_GET['code']) {
			$this->getToken__($_GET['code']);
		} else if (!$_SESSION['access_token']) {
			//$this->searchCode();
			$this->sendLogin();
		} else if (($_SESSION['time_token']-time() < (60*60))) {
			$this->renovateToken__();
		}

	}

	public function renovateToken__() {

		if (!$_SESSION['refresh_token'] || !$_SESSION['access_token']) {
			//$this->searchCode();
			$this->sendLogin();
		}

		$VAR = [
			'grant_type'    => 'refresh_token',
			'client_id'     => $this->client_id,
			'client_secret' => $this->client_secret,
			'refresh_token' => $_SESSION['refresh_token']
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api-cbt.mercadolibre.com/oauth/token");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $VAR);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		$opciones      = get_object_vars(json_decode($server_output, false));
		curl_close($ch);

		$_SESSION['refresh_token'] = $opciones['refresh_token'];
		$_SESSION['access_token']  = $opciones['access_token'];
		$_SESSION['time_token']    = $opciones['expires_in']+time();

		setcookie('access_token', $this->token_name);
		setcookie('refresh_token', $this->refresh_token);
		setcookie('time_token', $_SESSION['time_token']);

		return $opciones;

	}

	public function Token() {
		$this->checkToken();
		return $_SESSION['access_token'];
	}

	public function execute__($URL, $VAR, $TYPE = "POST") {

		if ($TYPE == "PUT") {

			$VAR = http_build_query($VAR);

			$server_output = $this->sendPut($URL, $VAR);

		} else if ($TYPE == "GET") {

			$server_output = $this->sendGet($URL, $VAR);

		} else if ($TYPE == "POST") {

			$VAR = http_build_query($VAR);

			$server_output = $this->sendPost($URL, $VAR);
		}

		return $server_output;

	}

}
