<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST PUT GET DELETE" );
header( "Access-Control-Allow-Credentials: true" );
header( 'Content-Type: application/json' );

include_once( '../config/database.php' );
include_once( '../models/bioscoopfilm.php' );

$database = new Database();
$db = $database->connect();

$method = $_SERVER[ 'REQUEST_METHOD' ];

switch ( $method ) {
	case 'GET':
		if ( !empty( $_GET[ "id" ] ) ) {
			$film = new Film( $db );

			$film->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();

			$film->read_single();

			if ( $film->filmNaam != null ) {

				$films_arr = array(
					"id" => $film->id,
					"filmNaam" => $film->filmNaam,
					"filmTijd" => $film->filmTijd,
					"filmPrijs" => $film->filmPrijs,
					"filmType" => $film->filmType
				);

				http_response_code( 200 );

				echo json_encode( $films_arr );
			} else {
				http_response_code( 404 );

				echo json_encode(
					array( 'message' => 'Film niet gevonden' )
				);
			}
		} else {
			$film = new Film( $db );

			$stmt = $film->read();
			$num = $stmt->rowCount();

			if ( $num > 0 ) {

				$films_arr = array();
				$films_arr[ "records" ] = array();

				while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {

					extract( $row );

					$film_item = array(
						"id" => $id,
						"filmNaam" => $filmNaam,
						"filmTijd" => $filmTijd,
						"filmPrijs" => $filmPrijs,
						"filmType" => $filmType
					);

					array_push( $films_arr[ "records" ], $film_item );
				}

				http_response_code( 200 );

				echo json_encode( $films_arr );
			} else {
				http_response_code( 404 );

				echo json_encode(
					array( 'message' => 'Geen films gevonden' )
				);
			}
		}
		break;



	case 'POST':

		$film = new Film( $db );

		$data = json_decode( file_get_contents( "php://input" ) );

		$film->filmNaam = $data->filmNaam;
		$film->filmTijd = $data->filmTijd;
		$film->filmPrijs = $data->filmPrijs;
		$film->filmType = $data->filmType;

		if ( $film->create() ) {
			echo json_encode(
				array( 'message' => 'Film aangemaakt.' )
			);
		} else {
			echo json_encode(
				array( 'message' => 'Film kan niet aangemaakt worden.' )
			);
		}
		break;



	case 'PUT':
		$film = new Film( $db );

		$data = json_decode( file_get_contents( "php://input" ) );

		$film->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();
		$film->filmNaam = $data->filmNaam;
		$film->filmTijd = $data->filmTijd;
		$film->filmPrijs = $data->filmPrijs;
		$film->filmType = $data->filmType;

		if ( $film->update() ) {
			http_response_code( 200 );

			echo json_encode( array( 'message' => 'Film geupdate.' ) );
		} else {
			http_response_code( 503 );

			echo json_encode(
				array( 'message' => 'Film kan niet geupdate worden.' )
			);
		}

		break;

	case 'DELETE':
		$film = new Film( $db );

		$film->id = isset($_GET['id']) ? $_GET['id'] : die();
 
		if ( $film->delete() ) {
			http_response_code( 200 );

			echo json_encode(
				array( 'message' => 'Film verwijderd' )
			);
		} else {
			http_response_code( 503 );

			echo json_encode(
				array( 'message' => 'Film kan niet verwijderd worden.' )
			);
		}


		break;
}