<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST PUT GET DELETE" );
header( "Access-Control-Allow-Credentials: true" );
header( 'Content-Type: application/json' );

include_once( '../config/database.php' );
include_once( '../models/bioscoopdomain.php' );

$database = new Database();
$db = $database->connect();

$method = $_SERVER[ 'REQUEST_METHOD' ];
switch ( $method ) {
	case 'GET':
		$domain = new Domain( $db );
		$apidata = json_decode( file_get_contents( "php://input" ) );
		$domain->apiKey = $apidata->apiKey;
		$domain->verifyDomain();
		if ( $domain->apiKey ) {
			$domains_arr = array(
				"id" => $domain->id,
				"domainNaam" => $domain->domein_naam,
				"apiKey" => $domain->apiKey
			);

			http_response_code( 200 );

			echo json_encode( $domains_arr );

		} else {
			http_response_code( 404 );

			echo json_encode(
				array( 'message' => 'Geen domein gevonden met deze API key' )
			);
		}
		break;
	case 'POST':
		$domain = new Domain( $db );

		$data = json_decode( file_get_contents( "php://input" ) );

		$domain->api_key = md5( rand() );
		$domain->api_level = $data->api_level;

		if ( $domain->create() ) {
			echo json_encode(
				array( 'message' => 'API Sleutel aangemaakt. Uw API code is: ' . $domain->api_key . '. Maak nu een nieuwe domein aan om toegang te krijgen op de API.' )
			);
		} else {
			echo json_encode(
				array( 'message' => 'De API sleutel kon niet aangemaakt worden.' )
			);
		}
		break;

}