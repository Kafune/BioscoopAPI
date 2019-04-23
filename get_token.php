<?php 
include_once( '../config/database.php' );

header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: GET" );
header( "Access-Control-Allow-Credentials: true" );
header( 'Content-Type: application/json' );

include_once '../lib/php-jwt-master/src/BeforeValidException.php';
include_once '../lib/php-jwt-master/src/ExpiredException.php';
include_once '../lib/php-jwt-master/src/SignatureInvalidException.php';
include_once '../lib/php-jwt-master/src/JWT.php';
use\ Firebase\ JWT\ JWT;

$key = "testkey";
$iss = "http://example.org";
$aud = "http://example.com";
$iat = 1356999524;
$nbf = 1357000000;

$database = new Database();
$db = $database->connect();

$method = $_SERVER[ 'REQUEST_METHOD' ];

switch ( $method ) {
		case 'GET':
		$token = array(
   "iss" => $iss,
   "aud" => $aud,
   "iat" => $iat,
   "nbf" => $nbf,
   "data" => array(
       "id" => $user->id,
       "firstname" => $user->firstname,
       "lastname" => $user->lastname,
       "email" => $user->email
   )
);
		
		$jwt = JWT::encode($token, $key);
    echo json_encode(
            array(
                "API key" => $jwt
            )
        );
		break;
	case 'POST':
		
		break;
}



?>