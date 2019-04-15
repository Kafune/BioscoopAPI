<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST PUT GET DELETE" );
header( "Access-Control-Allow-Credentials: true" );
header( 'Content-Type: application/json' );

include_once( '../config/database.php' );
include_once( '../models/bioscoop.php' );

$database = new Database();
$db = $database->connect();

$method = $_SERVER[ 'REQUEST_METHOD' ];
switch ( $method ) {
	case 'GET':
		if ( !empty( $_GET[ "id" ] ) ) {
			$bioscoop = new Bioscoop( $db );

			$bioscoop->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();

			$bioscoop->read_single();

			if ( $bioscoop->id != null) {

				$bioscopen_arr = array(
					"id" => $bioscoop->id,
					"bioscoopLocatie" => $bioscoop->bioscoopLocatie,
					"bioscoopFilm" => $bioscoop->bioscoopFilm,
					"bioscoopZaal" => $bioscoop->bioscoopZaal,
					"bioscoopTickets" => $bioscoop->bioscoopTickets,
					"bioscoopVoorstelling" => $bioscoop->bioscoopVoorstelling				);

				http_response_code( 200 );

				echo json_encode( $bioscopen_arr );
			} else {
				http_response_code( 404 );

				echo json_encode(
					array( 'message' => 'Bioscoop niet gevonden' )
				);
			}
		} else {
			$bioscoop = new bioscoop( $db );

			$stmt = $bioscoop->read();
			$num = $stmt->rowCount();

			if ( $num > 0 ) {

				$bioscopen_arr = array();
				$bioscopen_arr[ "records" ] = array();

				while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {

					extract( $row );

					$bioscoop_item = array(
						"id" => $id,
						"bioscoopLocatie" => $bioscoopLocatie,
						"bioscoopFilm" => $bioscoopFilm,
						"bioscoopZaal" => $bioscoopZaal,
						"bioscoopTickets" => $bioscoopTickets,
						"bioscoopVoorstelling" => $bioscoopVoorstelling
					);

					array_push( $bioscopen_arr[ "records" ], $bioscoop_item );
				}

				http_response_code( 200 );

				echo json_encode( $bioscopen_arr );
			} else {
				http_response_code( 404 );

				echo json_encode(
					array( 'message' => 'Geen bioscopen gevonden' )
				);
			}
		}
		break;



	case 'POST':
		$bioscoop = new Bioscoop( $db );
		
		$data = json_decode( file_get_contents( "php://input" ) );

		$bioscoop->bioscoopLocatie = $data->bioscoopLocatie;
		$bioscoop->bioscoopFilm = $data->bioscoopFilm;
		$bioscoop->bioscoopZaal = $data->bioscoopZaal;
		$bioscoop->bioscoopTickets = $data->bioscoopTickets;
		$bioscoop->bioscoopVoorstelling = $data->bioscoopVoorstelling;

		if ( $bioscoop->create() ) {
			echo json_encode(
				array( 'message' => 'Bioscoop aangemaakt' )
			);
		} else {
			echo json_encode(
				array( 'message' => 'Bioscoop kan niet aangemaakt worden.' )
			);
		}
		break;



	case 'PUT':
		$bioscoop = new Bioscoop( $db );

		$data = json_decode( file_get_contents( "php://input" ) );

		$bioscoop->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();
		$bioscoop->bioscoopLocatie = $data->bioscoopLocatie;
		$bioscoop->bioscoopFilm = $data->bioscoopFilm;
		$bioscoop->bioscoopZaal = $data->bioscoopZaal;
		$bioscoop->bioscoopTickets = $data->bioscoopTickets;
		$bioscoop->bioscoopVoorstelling = $data->bioscoopVoorstelling;

		if ( $bioscoop->update() ) {
			http_response_code( 200 );

			echo json_encode( array( 'message' => 'bioscoop geupdate.' ) );
		} else {
			http_response_code( 503 );

			echo json_encode(
				array( 'message' => 'Bioscoop kan niet gevonden/geupdate worden.' )
			);
		}

		break;

	case 'DELETE':
		$bioscoop = new Bioscoop( $db );

		$bioscoop->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();

		if ( $bioscoop->delete() ) {
			http_response_code( 200 );

			echo json_encode(
				array( 'message' => 'Bioscoop verwijderd' )
			);
		} else {
			http_response_code( 503 );

			echo json_encode(
				array( 'message' => 'Bioscoop kan niet verwijderd worden' )
			);
		}


		break;
}