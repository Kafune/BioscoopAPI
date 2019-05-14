<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST PUT GET DELETE" );
header( "Access-Control-Allow-Credentials: true" );
header( 'Content-Type: application/json' );

include( '../config/Database.php' );
include( '../models/bioscoopticket.php' );
include( '../models/bioscoopapikey.php' );
include( '../models/bioscoopdomain.php' );

$database = new Database();
$db = $database->connect();

// Check de api key
$apikey = new ApiKey( $db );
$apidata = json_decode( file_get_contents( "php://input" ) );
$apikey->api_key = $apidata->api_key;
$apikey->checkApiKey();

// Check voor geldige domein
$domain = new Domain( $db );
$domeindata = json_decode( file_get_contents( "php://input" ) );
$domain->domein_naam = $domeindata->domein_naam;
$domain->checkDomain();



if ( $apikey->api_key ) {
	if ( $domain->domein_naam ) {
        $method = $_SERVER[ 'REQUEST_METHOD' ];
switch ( $method ) {
	case 'GET':
		if ( !empty( $_GET[ "id" ] ) ) {
			$ticket = new Ticket( $db );

			$ticket->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();

			$ticket->read_single();

			if ( $ticket->id != null) {

				$tickets_arr = array(
					"id" => $ticket->id,
					"ticketNummer" => $ticket->ticketNummer,
					"ticketKlant" => $ticket->ticketKlant,
					"ticketDatum" => $ticket->ticketDatum,
					"ticketTijd" => $ticket->ticketTijd,
					"ticketZaal" => $ticket->ticketZaal,
					"ticketPrijs" => $ticket->ticketPrijs
				);

				http_response_code( 200 );

				echo json_encode( $tickets_arr );
			} else {
				http_response_code( 404 );

				echo json_encode(
					array( 'message' => 'Ticket niet gevonden' )
				);
			}
		} else {
			$ticket = new ticket( $db );

			$stmt = $ticket->read();
			$num = $stmt->rowCount();

			if ( $num > 0 ) {

				$tickets_arr = array();
				$tickets_arr[ "records" ] = array();

				while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {

					extract( $row );

					$ticket_item = array(
						"id" => $id,
						"ticketNummer" => $ticketNummer,
						"ticketKlant" => $ticketKlant,
						"ticketDatum" => $ticketDatum,
						"ticketTijd" => $ticketTijd,
						"ticketZaal" => $ticketZaal,
						"ticketPrijs" => $ticketPrijs
					);

					array_push( $tickets_arr[ "records" ], $ticket_item );
				}

				http_response_code( 200 );

				echo json_encode( $tickets_arr );
			} else {
				http_response_code( 404 );

				echo json_encode(
					array( 'message' => 'Geen tickets gevonden' )
				);
			}
		}
		break;



	case 'POST':
		$ticket = new ticket( $db );
		
		$data = json_decode( file_get_contents( "php://input" ) );

		$ticket->ticketNummer = $data->ticketNummer;
		$ticket->ticketKlant = $data->ticketKlant;
		$ticket->ticketDatum = $data->ticketDatum;
		$ticket->ticketTijd = $data->ticketTijd;
		$ticket->ticketZaal = $data->ticketZaal;
		$ticket->ticketPrijs = $data->ticketPrijs;

		if ( $ticket->create() ) {
			echo json_encode(
				array( 'message' => 'Ticket aangemaakt' )
			);
		} else {
			echo json_encode(
				array( 'message' => 'Ticket kan niet aangemaakt worden.' )
			);
		}
		break;



	case 'PUT':
		$ticket = new ticket( $db );

		$data = json_decode( file_get_contents( "php://input" ) );

		$ticket->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();
		$ticket->ticketNummer = $data->ticketNummer;
		$ticket->ticketKlant = $data->ticketKlant;
		$ticket->ticketDatum = $data->ticketDatum;
		$ticket->ticketTijd = $data->ticketTijd;
		$ticket->ticketZaal = $data->ticketZaal;
		$ticket->ticketPrijs = $data->ticketPrijs;

		if ( $ticket->update() ) {
			http_response_code( 200 );

			echo json_encode( array( 'message' => 'Ticket geupdate.' ) );
		} else {
			http_response_code( 503 );

			echo json_encode(
				array( 'message' => 'Ticket kan niet geupdate worden.' )
			);
		}

		break;

	case 'DELETE':
		$ticket = new Ticket( $db );

		$ticket->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();

		if ( $ticket->delete() ) {
			http_response_code( 200 );

			echo json_encode(
				array( 'message' => 'Ticket verwijderd' )
			);
		} else {
			http_response_code( 503 );

			echo json_encode(
				array( 'message' => 'Ticket kan niet verwijderd worden' )
			);
		}


		break;
}
} else {
        http_response_code( 404 );
		echo json_encode( array( 'message' => 'Het verzoek komt niet van een geldig domein!' ) );
	}
} else {
    http_response_code( 404 );
	echo json_encode( array( 'message' => 'Geen geldige API sleutel opgegeven!' ) );
}