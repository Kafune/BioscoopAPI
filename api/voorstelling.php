<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST PUT GET DELETE" );
header( "Access-Control-Allow-Credentials: true" );
header( 'Content-Type: application/json' );

include_once( '../config/Database.php' );
include_once( '../models/bioscoopvoorstelling.php' );
include_once( '../models/bioscoopapikey.php' );
include_once( '../models/bioscoopdomain.php' );

$database = new Database();
$db = $database->connect();

// Check de api key
$checkapikey = new ApiKey( $db );
$apidata = json_decode( file_get_contents( "php://input" ) );
$checkapikey->api_key = $apidata->api_key;
$checkapikey->checkApiKey();

// Check voor geldige domein
$checkdomein = new Domain( $db );
$domeindata = json_decode( file_get_contents( "php://input" ) );
$checkdomein->domein_naam = $domeindata->domein_naam;
$checkdomein->checkDomain();

$method = $_SERVER[ 'REQUEST_METHOD' ];

if ( $checkapikey->api_key ) {
	if ( $checkdomein->domein_naam ) {
switch ( $method ) {
	case 'GET':
		//if level = 1 of hoger: permit get
		
		if ( !empty( $_GET[ "id" ] ) ) {
			$voorstelling = new Voorstelling( $db );

			$voorstelling->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();

			$voorstelling->read_single();

			if ( $voorstelling->id != null) {

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
		//if level 2 of hofer
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
		//if level 3 of hoger
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
		//if level 4 of hoger
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
} else {
		echo json_encode( array( 'message' => 'Het verzoek komt niet van een geldig domein!' ) );
	}
} else {
	echo json_encode( array( 'message' => 'Geen geldige API sleutel opgegeven!' ) );
}