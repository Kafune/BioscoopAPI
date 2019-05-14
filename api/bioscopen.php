<?php
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: POST PUT GET DELETE" );
header( "Access-Control-Allow-Credentials: true" );
header( 'Content-Type: application/json' );

include_once( '../config/Database.php' );
include_once( '../models/bioscoop.php' );
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


		switch ( $method ) {
			case 'GET':
                if ( $checkapikey->api_key ) {
                    if ( $checkdomein->domein_naam ) {
				if ( $checkapikey->api_level > 1 ) {
				if ( !empty( $_GET[ "id" ] ) ) {
					$bioscoop = new Bioscoop( $db );

					$bioscoop->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();

					$bioscoop->read_single();

					if ( $bioscoop->id != null ) {

						$bioscopen_arr = array(
							"id" => $bioscoop->id,
							"bioscoopLocatie" => $bioscoop->bioscoopLocatie,
							"bioscoopZaal" => $bioscoop->bioscoopZaal,
							"bioscoopAantalPersoneel" => $bioscoop->bioscoopAantalPersoneel
						);

						http_response_code( 200 );

						echo json_encode( $bioscopen_arr );
					} else {
						http_response_code( 404 );

						echo json_encode(
							array( 'message' => 'Bioscoop niet gevonden' )
						);
					}
				} else {
					$bioscoop = new Bioscoop( $db );

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
								"bioscoopZaal" => $bioscoopZaal,
								"bioscoopAantalPersoneel" => $bioscoopAantalPersoneel
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
				} else {
					echo json_encode(
						array( 'message' => 'U heeft geen rechten om bioscopen op te halen.' )
					);
				}
                } else {
                    echo json_encode( array( 'message' => 'Het verzoek komt niet van een geldig domein!' ) );
                }
                } else {
                    echo json_encode( array( 'message' => 'Geen geldige API sleutel opgegeven!' ) );
                }



				break;



			case 'POST':
				if ( $checkapikey->api_level > 1 ) {
					$bioscoop = new Bioscoop( $db );

					$data = json_decode( file_get_contents( "php://input" ) );

					$bioscoop->bioscoopLocatie = $data->bioscoopLocatie;
					$bioscoop->bioscoopZaal = $data->bioscoopZaal;
					$bioscoop->bioscoopAantalPersoneel = $data->bioscoopAantalPersoneel;

					if ( $bioscoop->create() ) {
						echo json_encode(
							array( 'message' => 'Bioscoop aangemaakt' )
						);
					} else {
						echo json_encode(
							array( 'message' => 'Bioscoop kan niet aangemaakt worden.' )
						);
					}
				} else {
					echo json_encode(
						array( 'message' => 'U heeft niet de rechten om dit bioscoop aan te maken.' )
					);
				}
				break;



			case 'PUT':
				if ( $checkapikey->api_level > 2 ) {
					$bioscoop = new Bioscoop( $db );

					$data = json_decode( file_get_contents( "php://input" ) );

					$bioscoop->id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : die();
					$bioscoop->bioscoopLocatie = $data->bioscoopLocatie;
					$bioscoop->bioscoopZaal = $data->bioscoopZaal;
					$bioscoop->bioscoopAantalPersoneel = $data->bioscoopAantalPersoneel;

					if ( $bioscoop->update() ) {
						http_response_code( 200 );

						echo json_encode( array( 'message' => 'bioscoop geupdate.' ) );
					} else {
						http_response_code( 503 );

						echo json_encode(
							array( 'message' => 'Bioscoop kan niet gevonden/geupdate worden.' )
						);
					}
				} else {
					echo json_encode(
						array( 'message' => 'U heeft niet de rechten om dit bioscoop te bewerken.' )
					);
				}


				break;

			case 'DELETE':
				if ( $checkapikey->api_level > 3 ) {
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
				} else {
					echo json_encode(
						array( 'message' => 'U heeft niet de rechten om dit bioscoop te verwijderen.' )
					);
				}


				break;
		}

