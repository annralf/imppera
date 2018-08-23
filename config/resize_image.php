<?php
class ReziseImage {
	private $fullname;
	private $shortname;

	public function adapt($url = null) {
		$fullname = $url;
		try {
			$shortname = substr($fullname, -1*strpos(strrev($fullname), '/'));

			$size = getimagesize($fullname);

			if (count($size)) {
				$image = new Imagick($fullname);

				if ($size[0] < 300 || $size[1] < 300) {
					$image->adaptiveResizeImage(300, 300);
					$fullname = "https://core.enkargo.com.co/enkargo/download/$shortname";

					$f = fopen("../download/$shortname", 'wb');
					fwrite($f, $image);
					fclose($f);
				} elseif ($size[0] > 2000 || $size[1] > 2000) {
					$image->adaptiveResizeImage(800, 800);
					$fullname = "https://core.enkargo.com.co/enkargo/download/$shortname";

					$f = fopen("../download/$shortname", 'wb');
					fwrite($f, $image);
					fclose($f);
				}
			}
			return array('url' => $fullname);
			#http_response_code(200);
			#die();
		} catch (Exception $e) {
			return array('url' => $fullname, 'msg' => 'error', $e->getMessage());
			#http_response_code(500);
			#die();
		}
	}
}