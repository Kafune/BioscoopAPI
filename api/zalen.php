<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST PUT GET DELETE");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once('../config/Database.php');
include_once('../models/bioscoopzalen.php');
include_once('../models/bioscoopapikey.php');
include_once('../models/bioscoopdomain.php');

$database = new Database();
$db = $database->connect();

// Check de api key
$apikey = new ApiKey($db);
$apidata = json_decode(file_get_contents("php://input"));
$apikey->api_key = $apidata->api_key;
$apikey->checkApiKey();

// Check voor geldige domein
$checkdomein = new Domain($db);
$domeindata = json_decode(file_get_contents("php://input"));
$checkdomein->domein_naam = $domeindata->domein_naam;
$checkdomein->checkDomain();

$method = $_SERVER['REQUEST_METHOD'];

if ($apikey->api_key) {
    if ($checkdomein->domein_naam) {
        switch ($method) {
            case 'GET':
                if ($apikey->api_level > 0) {
                    if (!empty($_GET["id"])) {
                        $zaal = new Zaal($db);

                        $zaal->id = isset($_GET['id']) ? $_GET['id'] : die();

                        $zaal->read_single();

                        if ($zaal->id != null) {

                            $zalen_arr = array(
                                "id" => $zaal->id,
                                "zaalNummer" => $zaal->zaalNummer,
                                "zaalAantalStoelen" => $zaal->zaalAantalStoelen,
                                "zaalAantalRijen" => $zaal->zaalAantalRijen,
                                "zaalBeeld" => $zaal->zaalBeeld
                            );

                            http_response_code(200);

                            echo json_encode($zalen_arr);
                        } else {
                            http_response_code(404);

                            echo json_encode(
                                array('message' => 'Zaal niet gevonden')
                            );
                        }
                    } else {
                        $zaal = new Zaal($db);

                        $stmt = $zaal->read();
                        $num = $stmt->rowCount();

                        if ($num > 0) {

                            $zalen_arr = array();
                            $zalen_arr["records"] = array();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                extract($row);

                                $zaal_item = array(
                                    "id" => $id,
                                    "zaalNummer" => $zaalNummer,
                                    "zaalAantalStoelen" => $zaalAantalStoelen,
                                    "zaalAantalRijen" => $zaalAantalRijen,
                                    "zaalBeeld" => $zaalBeeld
                                );

                                array_push($zalen_arr["records"], $zaal_item);
                            }

                            http_response_code(200);

                            echo json_encode($zalen_arr);
                        } else {
                            http_response_code(404);

                            echo json_encode(
                                array('message' => 'Geen zalen gevonden')
                            );
                        }
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(
                        array('message' => 'U heeft geen rechten om zalen op te halen.')
                    );
                }
                break;


            case 'POST':
                if ($apikey->api_level > 1) {
                    $zaal = new Zaal($db);

                    $data = json_decode(file_get_contents("php://input"));

                    $zaal->zaalNummer = $data->zaalNummer;
                    $zaal->zaalAantalStoelen = $data->zaalAantalStoelen;
                    $zaal->zaalAantalRijen = $data->zaalAantalRijen;
                    $zaal->zaalBeeld = $data->zaalBeeld;

                    if ($zaal->create()) {
                        echo json_encode(
                            array('message' => 'Zaal aangemaakt')
                        );
                    } else {
                        echo json_encode(
                            array('message' => 'Zaal kan niet aangemaakt worden.')
                        );
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(
                        array('message' => 'U heeft niet de rechten om een zaal aan te maken.')
                    );
                }
                break;


            case 'PUT':
                if ($apikey->api_level > 2) {
                    $zaal = new Zaal($db);


                    $data = json_decode(file_get_contents("php://input"));

                    $zaal->id = isset($_GET['id']) ? $_GET['id'] : die();
                    $zaal->zaalNummer = $data->zaalNummer;
                    $zaal->zaalAantalStoelen = $data->zaalAantalStoelen;
                    $zaal->zaalAantalRijen = $data->zaalAantalRijen;
                    $zaal->zaalBeeld = $data->zaalBeeld;

                    if ($zaal->update()) {
                        http_response_code(200);

                        echo json_encode(array('message' => 'Zaal geupdated.'));
                    } else {
                        http_response_code(503);

                        echo json_encode(
                            array('message' => 'Zaal kan niet geupdate worden.')
                        );
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(
                        array('message' => 'U heeft niet de rechten om een zaal te bewerken.')
                    );
                }

                break;

            case 'DELETE':
                if ($apikey->api_level > 3) {
                    $zaal = new Zaal($db);

                    $zaal->id = isset($_GET['id']) ? $_GET['id'] : die();

                    if ($zaal->delete()) {
                        http_response_code(200);

                        echo json_encode(
                            array('message' => 'Zaal verwijderd')
                        );
                    } else {
                        http_response_code(503);

                        echo json_encode(
                            array('message' => 'Zaal kan niet verwijderd worden')
                        );
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(
                        array('message' => 'U heeft niet de rechten om een zaal te verwijderen.')
                    );
                }

                break;
        }
    } else {
        echo json_encode(array('message' => 'Het verzoek komt niet van een geldig domein!'));
    }
} else {
    echo json_encode(array('message' => 'Geen geldige API sleutel opgegeven!'));
}