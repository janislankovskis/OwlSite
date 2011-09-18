<?php 

function smarty_function_url($params, &$smarty = null) 
{
	global $conf;
	
	$base = $conf['protocol'] . '://' . $_SERVER['HTTP_HOST'] . str_replace(strchr($_SERVER['REQUEST_URI'], '?'), '', $_SERVER['REQUEST_URI']);
	
	$url = $base;
	
	$remove = array('rewrite');
	if(isset($params['remove']))
	{
		$remove = array_merge(explode(',',$params['remove']), $remove);
	}
	
	$add = array();
	if(isset($params['add']))
	{
		$add = explode(',',$params['add']);
		//get keys and values
		$addParts = array();
		foreach($add  as $v)
		{
			$part = explode('=',$v);
			if(isset($part[0]))
			{
				$addParts[$part[0]] = '';
			}
			if(isset($part[1]))
			{
				$addParts[$part[0]] = $part[1];
			}
		}
	}

	$get = array();
	foreach($_GET as $key => $value)
	{
		if(!in_array($key, $remove))
		{
			$get[$key] = $key.'='.$value;
		}
	}
	
	//add 
	if(isset($addParts))
	{
		foreach($addParts as $key=>$value)
		{
			$get[$key] = $key . '='. $value;
		}
	}
	
	if(!empty($get))
	{
		$key = '&amp;';
		if(isset($params['escaped'])&&$params['escaped']==false)
		{
			$key = '&';
//			debug('dsfsd');
		}
		
		$getString = '?' . implode($key, $get);
		$url = $base . $getString;
	}
	
	if(isset($params['shorten']))
	{
		//global $project;
		//$apiKey = 'AIzaSyADhdAPA5X42ewylPfjkprpqdZ-DYionAA';
		$longUrl = $url;
		
		$postData = array('longUrl' => $longUrl/*, 'key' => $apiKey*/);
		$jsonData = json_encode($postData);
		 
		$curlObj = curl_init();
		 
		curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
		curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlObj, CURLOPT_HEADER, 0);
		curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
		curl_setopt($curlObj, CURLOPT_POST, 1);
		curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
		 
		$response = curl_exec($curlObj);

		
		
		//change the response json string to object
		$json = json_decode($response);
		 
		curl_close($curlObj);
		 
		$url  = $json->id;
		
		return $url;
		
	}
	

	return $url;	
}


/** 
 * Send a POST requst using cURL 
 * @param string $url to request 
 * @param array $post values to send 
 * @param array $options for cURL 
 * @return string 
 */ 
function curl_post($url, array $post = NULL, array $options = array()) 
{ 
    $defaults = array( 
        CURLOPT_POST => 1, 
        /*CURLOPT_HEADER => 0, */
        CURLOPT_URL => $url, 
        CURLOPT_FRESH_CONNECT => 1, 
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_FORBID_REUSE => 1, 
        CURLOPT_TIMEOUT => 4, 
        CURLOPT_POSTFIELDS => json_encode($post)
    ); 

    $ch = curl_init(); 
    curl_setopt_array($ch, ($options + $defaults)); 
    if( ! $result = curl_exec($ch)) 
    { 
        trigger_error(curl_error($ch)); 
    } 
    curl_close($ch); 
    return $result; 
}


?>
