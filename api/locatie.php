<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST PUT GET DELETE" );
header( "Access-Control-Allow-Credentials: true" );
header( 'Content-Type: application/json' );

include_once( '../config/database.php' );
include_once( '../models/bioscooplocatie.php' );

$database = new Database();
$db = $database->connect();

$method = $_SERVER[ 'REQUEST_METHOD' ];

switch ( $method ) {
	case 'GET':
		if ( !empty( $_GET[ "id" ] ) ) {
			$locatie = new Locatie( $db );

			$locatie->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();

			$locatie->read_single();

			if ( $locatie->locatieNaam != null ) {

				$locaties_arr = array(
					"id" => $locatie->id,
					"locatieNaam" => $locatie->locatieNaam,
					"locatieStraat" => $locatie->locatieStraat,
					"locatiePostcode" => $locatie->locatiePostcode,
					"locatieProvincie" => $locatie->locatieProvincie
				);

				http_response_code( 200 );

				echo json_encode( $locaties_arr );
			} else {
				http_response_code( 404 );

				echo json_encode(
					array( 'message' => 'Deze locatie is niet gevonden' )
				);
			}
		} else {
			$locatie = new Locatie( $db );

			$stmt = $locatie->read();
			$num = $stmt->rowCount();

			if ( $num > 0 ) {

				$locaties_arr = array();
				$locaties_arr[ "records" ] = array();

				while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {

					extract( $row );

					$locatie_item = array(
						"id" => $id,
						"locatieNaam" => $locatieNaam,
						"locatieStraat" => $locatieStraat,
						"locatiePostcode" => $locatiePostcode,
						"locatieProvincie" => $locatieProvincie
					);

					array_push( $locaties_arr[ "records" ], $locatie_item );
				}

				http_response_code( 200 );

				echo json_encode( $locaties_arr );
			} else {
				http_response_code( 404 );

				echo json_encode(
					array( 'message' => 'Geen locatie gevonden' )
				);
			}
		}
		break;



	case 'POST':

		$locatie = new Locatie( $db );

		$data = json_decode( file_get_contents( "php://input" ) );

		$locatie->locatieNaam = $data->locatieNaam;
		$locatie->locatieStraat = $data->locatieStraat;
		$locatie->locatiePostcode = $data->locatiePostcode;
		$locatie->locatieProvincie = $data->locatieProvincie;

		if ( $locatie->create() ) {
			echo json_encode(
				array( 'message' => 'Locatie aangemaakt' )
			);
		} else {
			echo json_encode(
				array( 'message' => 'Locatie kan niet toegevoegd worden.' )
			);
		}
		break;



	case 'PUT':
		$locatie = new Locatie( $db );

		$data = json_decode( file_get_contents( "php://input" ) );

		$locatie->id = $data->id;
		$locatie->locatieNaam = $data->locatieNaam;
		$locatie->locatieStraat = $data->locatieStraat;
		$locatie->locatiePostcode = $data->locatiePostcode;
		$locatie->locatieProvincie = $data->locatieProvincie;

		if ( $locatie->update() ) {
			http_response_code( 200 );

			echo json_encode( array( 'message' => 'Locatie bijgewerkt.' ) );
		} else {
			http_response_code( 503 );

			echo json_encode(
				array( 'message' => 'Locatie kan niet bijgewerkt worden.' )
			);
		}

		break;

	case 'DELETE':
		$locatie = new Locatie( $db );

		$data = json_decode( file_get_contents( "php://input" ) );

		$locatie->id = $data->id;

		if ( $locatie->delete() ) {
			http_response_code( 200 );

			echo json_encode(
				array( 'message' => 'Locatie verwijderd' )
			);
		} else {
			http_response_code( 503 );

			echo json_encode(
				array( 'message' => 'Voorstelling kan niet verwijderd worden.' )
			);
		}


		break;
}