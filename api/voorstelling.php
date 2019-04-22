<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST PUT GET DELETE" );
header( "Access-Control-Allow-Credentials: true" );
header( 'Content-Type: application/json' );

include_once( '../config/database.php' );
include_once( '../models/bioscoopvoorstelling.php' );

$database = new Database();
$db = $database->connect();

$method = $_SERVER[ 'REQUEST_METHOD' ];

switch ( $method ) {
	case 'GET':
		if ( !empty( $_GET[ "id" ] ) ) {
			$voorstelling = new Voorstelling( $db );

			$voorstelling->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();

			$voorstelling->read_single();

			if ( $voorstelling->voorstellingNummer != null) {

				$voorstellingen_arr = array(
					"id" => $voorstelling->id,
					"voorstellingNummer" => $voorstelling->voorstellingNummer,
					"voorstellingTicket" => $voorstelling->voorstellingTicket,
					"voorstellingZaal" => $voorstelling->voorstellingZaal,
					"voorstellingFilm" => $voorstelling->voorstellingFilm,
					"voorstellingDuur" => $voorstelling->voorstellingDuur
				);

				http_response_code( 200 );

				echo json_encode( $voorstellingen_arr );
			} else {
				http_response_code( 404 );

				echo json_encode(
					array( 'message' => 'Voorstelling niet gevonden' )
				);
			}
		} else {
			$voorstelling = new Voorstelling( $db );

			$stmt = $voorstelling->read();
			$num = $stmt->rowCount();

			if ( $num > 0 ) {

				$voorstellingen_arr = array();
				$voorstellingen_arr[ "records" ] = array();

				while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {

					extract( $row );

					$voorstelling_item = array(
						"id" => $id,
						"voorstellingNummer" => $voorstellingNummer,
						"voorstellingTicket" => $voorstellingTicket,
						"voorstellingZaal" => $voorstellingZaal,
						"voorstellingFilm" => $voorstellingFilm,
						"voorstellingDuur" => $voorstellingDuur					);

					array_push( $voorstellingen_arr[ "records" ], $voorstelling_item );
				}

				http_response_code( 200 );

				echo json_encode( $voorstellingen_arr );
			} else {
				http_response_code( 404 );

				echo json_encode(
					array( 'message' => 'Geen voorstellingen gevonden' )
				);
			}
		}
		break;



	case 'POST':
		$voorstelling = new Voorstelling( $db );
		
		$data = json_decode( file_get_contents( "php://input" ) );

		$voorstelling->voorstellingNummer = $data->voorstellingNummer;
		$voorstelling->voorstellingTicket = $data->voorstellingTicket;
		$voorstelling->voorstellingZaal = $data->voorstellingZaal;
		$voorstelling->voorstellingFilm = $data->voorstellingFilm;
		$voorstelling->voorstellingDuur = $data->voorstellingDuur;

		if ( $voorstelling->create() ) {
			echo json_encode(
				array( 'message' => 'Voorstelling aangemaakt' )
			);
		} else {
			echo json_encode(
				array( 'message' => 'Voorstelling kan niet aangemaakt worden.' )
			);
		}
		break;



	case 'PUT':
		$voorstelling = new Voorstelling( $db );
		
		

		$data = json_decode( file_get_contents( "php://input" ) );

		$voorstelling->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();
		$voorstelling->voorstellingNummer = $data->voorstellingNummer;
		$voorstelling->voorstellingTicket = $data->voorstellingTicket;
		$voorstelling->voorstellingZaal = $data->voorstellingZaal;
		$voorstelling->voorstellingFilm = $data->voorstellingFilm;
		$voorstelling->voorstellingDuur = $data->voorstellingDuur;

		if ( $voorstelling->update() ) {
			http_response_code( 200 );

			echo json_encode( array( 'message' => 'Voorstelling geupdated.' ) );
		} else {
			http_response_code( 503 );

			echo json_encode(
				array( 'message' => 'Voorstelling kan niet geupdate worden.' )
			);
		}

		break;

	case 'DELETE':
		$voorstelling = new Voorstelling( $db );

		$voorstelling->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();

		if ( $voorstelling->delete() ) {
			http_response_code( 200 );

			echo json_encode(
				array( 'message' => 'Voorstelling verwijderd' )
			);
		} else {
			http_response_code( 503 );

			echo json_encode(
				array( 'message' => 'Voorstelling kan niet verwijderd worden' )
			);
		}


		break;
}