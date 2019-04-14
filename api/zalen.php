<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST PUT GET DELETE" );
header( "Access-Control-Allow-Credentials: true" );
header( 'Content-Type: application/json' );

include_once( '../config/database.php' );
include_once( '../models/bioscoopzalen.php' );

$database = new Database();
$db = $database->connect();

$method = $_SERVER[ 'REQUEST_METHOD' ];

switch ( $method ) {
	case 'GET':
		if ( !empty( $_GET[ "id" ] ) ) {
			$zaal = new Zalen( $db );

			$zaal->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();

			$zaal->read_single();

			if ( $zaal->id != null) {

				$zalen_arr = array(
					"id" => $zaal->id,
					"zaalNummer" => $zaal->zaalNummer,
					"zaalAantalStoelen" => $zaal->zaalAantalStoelen,
					"zaalAantalRijen" => $zaal->zaalAantalRijen,
					"zaalBeeld" => $zaal->zaalBeeld
				);

				http_response_code( 200 );

				echo json_encode( $zalen_arr );
			} else {
				http_response_code( 404 );

				echo json_encode(
					array( 'message' => 'Zaal niet gevonden' )
				);
			}
		} else {
			$zaal = new Zalen( $db );

			$stmt = $zaal->read();
			$num = $stmt->rowCount();

			if ( $num > 0 ) {

				$zalen_arr = array();
				$zalen_arr[ "records" ] = array();

				while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {

					extract( $row );

					$zaal_item = array(
						"id" => $id,
						"zaalNummer" => $zaalNummer,
						"zaalAantalStoelen" => $zaalAantalStoelen,
						"zaalAantalRijen" => $zaalAantalRijen,
						"zaalBeeld" => $zaalBeeld
					);

					array_push( $zalen_arr[ "records" ], $zaal_item );
				}

				http_response_code( 200 );

				echo json_encode( $zalen_arr );
			} else {
				http_response_code( 404 );

				echo json_encode(
					array( 'message' => 'Geen zalen gevonden' )
				);
			}
		}
		break;



	case 'POST':
		$zaal = new Zalen( $db );
		
		$data = json_decode( file_get_contents( "php://input" ) );

		$zaal->zaalNummer = $data->zaalNummer;
		$zaal->zaalAantalStoelen = $data->zaalAantalStoelen;
		$zaal->zaalAantalRijen = $data->zaalAantalRijen;
		$zaal->zaalBeeld = $data->zaalBeeld;

		if ( $zaal->create() ) {
			echo json_encode(
				array( 'message' => 'Zaal aangemaakt' )
			);
		} else {
			echo json_encode(
				array( 'message' => 'Zaal kan niet aangemaakt worden.' )
			);
		}
		break;



	case 'PUT':
		$zaal = new Zalen( $db );
		
		

		$data = json_decode( file_get_contents( "php://input" ) );

		$zaal->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();
		$zaal->zaalNummer = $data->zaalNummer;
		$zaal->zaalAantalStoelen = $data->zaalAantalStoelen;
		$zaal->zaalAantalRijen = $data->zaalAantalRijen;
		$zaal->zaalBeeld = $data->zaalBeeld;

		if ( $zaal->update() ) {
			http_response_code( 200 );

			echo json_encode( array( 'message' => 'Zaal geupdated.' ) );
		} else {
			http_response_code( 503 );

			echo json_encode(
				array( 'message' => 'Zaal kan niet geupdate worden.' )
			);
		}

		break;

	case 'DELETE':
		$zaal = new Zalen( $db );

		$zaal->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();

		if ( $zaal->delete() ) {
			http_response_code( 200 );

			echo json_encode(
				array( 'message' => 'Zaal verwijderd' )
			);
		} else {
			http_response_code( 503 );

			echo json_encode(
				array( 'message' => 'Zaal kan niet verwijderd worden' )
			);
		}


		break;
}