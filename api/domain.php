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
		$domain->api_key = $apidata->api_key;
		$domain->verifyDomain();
		if ( $domain->api_key ) {
			$domains_arr = array(
				"id" => $domain->id,
				"domainNaam" => $domain->domein_naam,
				"api_key" => $domain->api_key
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

		$domain->domein_naam = $data->domein_naam;
		$domain->api_key = $data->api_key;

		if ( $domain->create() ) {
			echo json_encode(
				array( 'message' => 'Domein aangemaakt met de Api key '.$domain->api_key.' Uw domein is: ' . $domain->domein_naam . '. U heeft nu toegang tot de API op basis van uw API level.' )
			);
		} else {
			echo json_encode(
				array( 'message' => 'Het domein kon niet aangemaakt worden.' )
			);
		}
		break;

}