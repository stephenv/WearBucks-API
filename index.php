<?php

require 'lib/includes.php';
require 'lib/class.Starbucks.php';

//Application name (used for Google Analytics)
$_ENV['APP_NAME'] = "AppName";
// Maintenance mode
$_ENV['MAINTENANCE_MODE'] = false;
// Google Analytics
$_ENV['ANALYTICS'] = true;
$_ENV['TRACKING_ID'] = "UA-XXXXXXXX-X"; 
// MongoDB Locations database
$_ENV['LOCATIONS'] = false; 
$_ENV['MONGOHQ_URL'] = "mongodb://user:pass@server.mongohq.com/db_name"; // 


require 'lib/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
use Slim\Slim;

$app->get('/',function () {
    echo "*Drinking Coffee* <br />";
});

if(!$_ENV['MAINTENANCE_MODE']){

    $app->post('/account', function () use ($app) {

        if($_ENV['ANALYTICS']){
            // Fetches the IP and user agent.
            $req = $app->request;
            $uip = $req->getIp();
            $ua = $req->getUserAgent();
            // Put the android version and uuid in the header from the app
            $cid = $app->request->headers->get('cid');
            $av = $app->request->headers->get('av');
            // Hit Type for Google Analytics
            $t = "event";
            $ec = "account";
            $ea = "update";
        }

        $bodyJson = Slim::getInstance()->request()->getBody();
        $bodyArray = bodyJsonToArray($bodyJson);
        $validLogin = validLogin($bodyArray);

        if(!$bodyArray){

            $code = 400;
            $text = "Json Validation Error";
            $description = "Empty body or malformed JSON";

            $error = generateError($code, $text, $description);
            //Returns 200 "OK" even though there is an error. Not the best practice, but my preference for this API
            $response = $app->response();
            $response->status(200);
            $response['Content-Type'] = 'application/json';
            $response->body($error);
            //Log Analytics
            if($_ENV['ANALYTICS']){
                logAnalytics($_ENV['TRACKING_ID'],$cid,$t,$ec,$ea,$code,$uip,$ua,$av);
            }

        }elseif($bodyArray && !$validLogin[0]){

            $code = 400;
            $text = "Json Validation Error";
            $description = $validLogin[1];

            $error = generateError($code, $text, $description);
            //Returns 200 "OK" even though there is an error. Not the best practice, but my preference for this API
            $response = $app->response();
            $response->status(200);
            $response['Content-Type'] = 'application/json';
            $response->body($error);
            //Log Analytics
            if($_ENV['ANALYTICS']){
                logAnalytics($_ENV['TRACKING_ID'],$cid,$t,$ec,$ea,$code,$uip,$ua,$av);
            }

        }else{

            $username = $bodyArray['username'];
            $password = $bodyArray['password'];
            
            $Starbucks = new Starbucks($username, $password);
            
            if (!$Starbucks->customer_name) {

                $code = 401;
                $text = "Unauthorized";
                $description = "Authentication credentials were  malformed or incorrect.";
                //Returns 200 "OK" even though there is an error. Not the best practice, but my preference for this API
                $error = generateError($code, $text, $description);
                $response = $app->response();
                $response->status(200);
                $response['Content-Type'] = 'application/json';
                $response->body($error);
                //Log Analytics
                if($_ENV['ANALYTICS']){
                    logAnalytics($_ENV['TRACKING_ID'],$cid,$t,$ec,$ea,$code,$uip,$ua,$av);
                }

            }else{
            
                $returnData = json_encode(array(
                    'error' => false,
                    'customer_name' => $Starbucks->customer_name,
                    'stars' => $Starbucks->stars,
                    'rewards' => $Starbucks->rewards,
                    'dollar_balance' => $Starbucks->dollar_balance
                ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

                $Starbucks->cleanup();

                $code = 200;
                $response = $app->response();
                $response->status(200);
                $response['Content-Type'] = 'application/json';
                $response->body($returnData);
                //Log Analytics
                if($_ENV['ANALYTICS']){
                    logAnalytics($_ENV['TRACKING_ID'],$cid,$t,$ec,$ea,$code,$uip,$ua,$av);
                }
            }
            
        }
    });

    if($_ENV['LOCATIONS']){
        $app->post('/locations', function () use ($app) {

            $bodyJson = Slim::getInstance()->request()->getBody();
            $bodyArray = bodyJsonToArray($bodyJson);
            //$validLogin = validLogin($bodyArray);

            $lat = $bodyArray['latitude'];
            $lng = $bodyArray['longitude'];
            $lnglat = array($lat, $lng);

            $connection_url = $_ENV['MONGOHQ_URL'];
            
            $m = new MongoClient($connection_url);
            $url = parse_url($connection_url);
            $db_name = preg_replace('/\/(.*)/', '$1', $url['path']);
            $collection_name = "locations";

            $m->selectDB($db_name)->$collection_name->ensureIndex(array('location' => '2d'));

            $result = $m->selectDB($db_name)->command(array(
                        'geoNear' => $collection_name,
                        'near' => $lnglat,
                        'spherical' => true,
                        'maxDistance' => 25000,
                        'num' => 100 
                    )); 
            $resultJson = json_encode($result);
            
            $response = $app->response();
            $response->status(200);
            $response['Content-Type'] = 'application/json';
            $response->body($resultJson);

        });
    }

}else{
    $app->halt(503);
}

$app->run();
