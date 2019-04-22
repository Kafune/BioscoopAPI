<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST PUT GET DELETE" );
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

// get posted data
$data = json_decode( file_get_contents( "php://input" ) );

// get jwt
$jwt = isset( $data->jwt ) ? $data->jwt : "";

// if jwt is not empty
if ( $jwt ) {

	// if decode succeed, show user details
	try {
		// decode jwt
		$decoded = JWT::decode( $jwt, $key, array( 'HS256' ) );

		// set response code
		http_response_code( 200 );

		// show user details
		echo json_encode( array(
			"message" => "Access granted.",
			"data" => $decoded->data
		) );

	}

	// if decode fails, it means jwt is invalid
	catch ( Exception $e ) {

		// set response code
		http_response_code( 401 );

		// tell the user access denied  & show error message
		echo json_encode( array(
			"message" => "Access denied.",
			"error" => $e->getMessage()
		) );
	}
} else{
 
    // set response code
    http_response_code(401);
 
    // tell the user access denied
    echo json_encode(array("message" => "Access denied."));
}
?>