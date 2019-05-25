<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST PUT GET DELETE");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once('../config/Database.php');
include_once('../models/bioscoopapikey.php');

$database = new Database();
$db = $database->connect();

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        $apikey = new ApiKey($db);
        $apidata = json_decode(file_get_contents("php://input"));
        $apikey->api_key = $apidata->api_key;
        $apikey->checkApiKey();
        if ($apikey->api_key) {
            $apikeys_arr = array(
                "id" => $apikey->id,
                "api_key" => $apikey->api_key,
                "api_level" => $apikey->api_level
            );

            http_response_code(200);

            echo json_encode($apikeys_arr);

        } else {
            http_response_code(404);

            echo json_encode(
                array('message' => 'Geen Api key gevonden met deze waarde')
            );
        }
        break;
    case 'POST':
        $apikey = new ApiKey($db);

        $data = json_decode(file_get_contents("php://input"));

        $apikey->api_key = md5(rand());
        $apikey->api_level = $data->api_level;

        if ($apikey->create()) {
            echo json_encode(
                array('message' => 'API Sleutel aangemaakt. Uw API code is: ' . $apikey->api_key . '. Maak nu een nieuwe domein aan om toegang te krijgen op de API.')
            );
        } else {
            echo json_encode(
                array('message' => 'De API sleutel kon niet aangemaakt worden.')
            );
        }
        break;

}