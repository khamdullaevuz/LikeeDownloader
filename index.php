<?php

/**
 * @author Elbek Khamdullaev
 * @description Likee video downloader api
 * @license free and open source
 * @repository https://github.com/khamdullaevuz/likeedownloader
 */

$url = $_GET['url'];

function getContent($url, $geturl = false)
  {
    $ch = curl_init();
    $options = array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
        CURLOPT_ENCODING       => "utf-8",
        CURLOPT_AUTOREFERER    => true,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_MAXREDIRS      => 10,
    );
    curl_setopt_array( $ch, $options );
    if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
      curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($geturl === true)
    {
        return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    }
    curl_close($ch);
    return strval($data);
  }

  if (isset($url)) {
		$url = trim($url);
		$resp = getContent($url);
		//echo "$resp";
		$check = explode('video_url":"', $resp);
		if (count($check) > 1){
			$contentURL = explode("\"",$check[1])[0];
			$thumb = str_replace("\\","",explode("\"",explode('image2":"', $resp)[1])[0]);
			$username = explode("\"",explode('like_id":"', $resp)[1])[0];		

			$array = [
				'ok'=>true,
				'result'=>[
					'username'=>$username,
					'thumb'=>$thumb,
					'url'=>$contentURL
				]
			];
		}else{
			$array = [
				'ok'=>false,
				'result'=>'Nothing found'
			];
		}
  }else{
		$array = [
				'ok'=>false,
				'result'=>'Please enter url'
			];
  }

	header('Content-type: application/json');

	echo json_encode($array);
