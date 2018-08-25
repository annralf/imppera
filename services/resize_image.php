<?php
class ReziseImage {
	private $fullname;
	private $shortname;

	public function adapt($url = null) 
	{
		header('Content-Type: application/json');
		$fullname = $url;

		try {
			$shortname = substr($fullname, -1*strpos(strrev($fullname), '/'));

			$size = getimagesize($fullname);

			if(count($size))
			{
				$image = new Imagick($fullname);

				if ($size[0] < 300 || $size[1] < 300) 
				{
					$image->adaptiveResizeImage(300, 300);
					$fullname = "http://181.58.30.117/enkargo/download/$shortname";

					$f = fopen("../download/$shortname", 'wb');
					fwrite($f, $image);
					fclose($f);
				}
				elseif ($size[0] > 2000 || $size[1] > 2000) 
				{
					$image->adaptiveResizeImage(800, 800);
					$fullname = "http://181.58.30.117/enkargo/download/$shortname";

					$f = fopen("../download/$shortname", 'wb');
					fwrite($f, $image);
					fclose($f);
				}
			}

			http_response_code(200);
			die(json_encode(array('url' => $fullname), JSON_UNESCAPED_UNICODE));
		} catch (Exception $e) {
			http_response_code(500);
			die(json_encode(array('url' => $fullname, 'msg' => 'error', $e->getMessage(), JSON_UNESCAPED_UNICODE)));
		}
	}
}