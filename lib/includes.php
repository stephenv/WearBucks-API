<?php


/**
 * bodyJsonToArray
 * Converts string of json to a php array while checking that json is valid
 * @param $bodyJson body of request in unparsed string json 
 * @return $array body json in array
 */
function bodyJsonToArray($bodyJson){
  $bodyArray = json_decode(stripslashes($bodyJson), true);
  if($bodyArray === NULL || !$bodyArray){
    $array = false;
    return $array;
  }else{
    $array = $bodyArray;
    return $array;
  }
}


/**
 * generateError
 * Generates Json formatted error for return through API
 * @param $code error code int value
 * @param $text String value associated with error code
 * @param $description more information regarding error
 * @return $array 
 */
function generateError($code, $text, $description){
  $response = array(
      "error" => array(
        "code" => $code,
        "message" => $text,
        "description" => $description
      )
    );
  $responseJson = json_encode($response);
  return $responseJson;
}


/**
 * ctype_alpha
 * Checks if all of the characters in the provided string, text, are alphabetic (Standard PHP)
 * @param $text the tested string
 * @return $response Returns TRUE if every character in text is a letter from the current locale, FALSE otherwise.
 */
function ctypeAlpha($text){   
  $response = preg_match("/[A-Za-z]/",$text);
  return $response;
}


/**
 * validLogin
 * Checks if all of the characters in the provided string, text, are alphabetic (Standard PHP)
 * @param $bodyArray body of request in array
 * @return $response Returns array [0] with TRUE if login is valid, array [1] with more info
 */
function validLogin($bodyArray){
  $b = $bodyArray;
  $response = array();
  $username = $b['username'];
  $password = $b['password'];
  if(array_key_exists_r('username', $b) && array_key_exists_r('password', $b)) {
    if (empty($username) || !$username){
      $response[0] = false;
      $response[1] = "The Username field is empty!";
    }elseif (empty($password) || !$password){
      $response[0] = false;
      $response[1] = "The Password field is empty!";
    }else{
      $response[0] = true;
      $response[1] = "Login information can be sent";
    }
  } else {
    $response[0] = false;
    $response[1] = "Bad Request - Empty body or malformed JSON";
  }
  return $response;
}


/**
 * array_key_exists_r
 * http://www.php.net/manual/en/function.array-key-exists.php
 * @param $keys
 * @param $search_r 
 * @return boolean
 */
function array_key_exists_r($key,$arr) { 
    $e = 0; //$key = addslashes($key); 
    if(is_array($arr) && $arr !==array()) 
    { 
        foreach($arr as $k => $v) 
        {    
            if(strtolower($k) == strtolower($key)) 
                $e++; 
        } 
        if($e>0) 
            return true;        
        else 
            return false; 
    } 
    else 
        return false; 
} 

/**
 * logAnalytics
 * Uses Google Analytics Measurement Protocol 
 * @param $tid Tracking ID, should be environment variable
 * @param $cid Client ID Required for all hit types.
 * @param $t Hit type
 * @param $dp Document Path (in this case, the URI endpoint)
 * @param $dt Document title (in this case, the HTTP response code)
 * @param $uip User IP Address
 * @param $ua User Agent string
 * @return boolean
 */
function logAnalytics($tid,$cid,$t,$ec,$ea,$ev,$uip,$ua,$av) {
  $v="1"; //version number 1
  $url = "http://www.google-analytics.com/collect?".
    "v=".urlencode($v).
    "&tid=".urlencode($tid).
    "&cid=".urlencode($cid).
    "&t=".urlencode($t).
    "&ec=".urlencode($ec).
    "&ea=".urlencode($ea).
    "&el=".urlencode($ev).
    "&ev=".urlencode("1").
    "&uip=".urlencode($uip).
    "&ua=".urlencode($ua).
    "&an=".urlencode($_ENV['APP_NAME']).
    "&av=".urlencode($av);

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_TIMEOUT, 60);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 0 );
  $response = curl_exec($ch);
}

?>