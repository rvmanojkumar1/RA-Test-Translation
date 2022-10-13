<?php
   	function translate_text($lg, $text) {
       
    	$apiKey = 'AIzaSyCfFJ2pBRMVyg-sFc818iykH-l0xQuayiM';
      	
      	$url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source=en&target='.$lg.'';

      	$handle = curl_init($url);
      	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
      	$response = curl_exec($handle);                 
      	$responseDecoded = json_decode($response, true);
      	curl_close($handle);

      	return $responseDecoded['data']['translations'][0]['translatedText'];      
   	}

   	print_r(translate_text('bn','zsfe4'));
?>