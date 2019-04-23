<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST PUT GET DELETE" );
header( "Access-Control-Allow-Credentials: true" );
header( 'Content-Type: application/json' );

include_once( '../config/database.php' );
include_once( '../models/bioscoopapikey.php' );

$database = new Database();
$db = $database->connect();

$method = $_SERVER[ 'REQUEST_METHOD' ];
switch ( $method ) {
	case 'GET':
		if ( !empty( $_GET[ "api_key" ] ) ) {
			$apikey = new checkApiKey( $db );

			$apikey->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();

			$apikey->checkApiKey();

			if ( $apikey->api_key != null) {

				$apikeys_arr = array(
					"id" => $apikey->id,
					"api_key" => $apikey->api_key,
					"api_level" => $apikey->api_level
				);

				http_response_code( 200 );

				echo json_encode( $apikeys_arr );
			}
		} else {
			http_response_code( 404 );

			echo json_encode(
				array( 'message' => 'Geen Api key gevonden met deze waarde' )
			);
		}
		break;

}