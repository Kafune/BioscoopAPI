<?php 
    require_once "../Include/initialize.php";

    $film = new Film();
    $domain =  new Domain();
    $apiKey = $_SERVER['HTTP_X_API_KEY'];
    $domein = $_SERVER['SERVER_NAME'];

    if ( $authentication->checkApiKey($apiKey) )
    {
        if ( ($authentication->api_level >= 1)  ) 
        {
            if ( $domain->checkDomain($authentication->api_id, $domein) )
            {
                
            }
            else echo "Api verzoek afgewezen vanaf dit domein";
        }
        else echo "Niet de juiste rechten voor deze actie.";
    }
    else echo "Ongeldige api key.";

    // Check for a GET request
    if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) 
    {
        // Show all te films if it is an empty GET request
        if ( ($_REQUEST == array()) || ($_REQUEST['id'] == '') ) 
        {
            if ( $authentication->checkApiKey($apiKey) )
            {
                if ( ($authentication->api_level >= 1)  ) 
                {
                    if ( $domain->checkDomain($authentication->api_id, $domein) )
                    {
                        echo $film->getFilms();
                    }
                    else echo "Api verzoek afgewezen vanaf dit domein";
                }
                else echo "Niet de juiste rechten voor deze actie.";
            }
            else echo "Ongeldige api key.";
        } 
        // Show one film according to the requested id
        else 
        {
            if ( $authentication->checkApiKey($apiKey) )
            {
                if ( ($authentication->api_level >= 1)  ) 
                {
                    if ( $domain->checkDomain($authentication->api_id, $domein) )
                    {
                        if ( isset($_REQUEST['id']) && !empty($_REQUEST['id']) ) echo $film->getFilm($_REQUEST['id']);
                    }
                    else echo "Api verzoek afgewezen vanaf dit domein";
                }
                else echo "Niet de juiste rechten voor deze actie.";
            }
            else echo "Ongeldige api key.";
        }
    }
    // Check for a POST request
    else if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) 
    {
        if ( $authentication->checkApiKey($apiKey) )
        {
            if ( ($authentication->api_level >= 2)  ) 
            {
                if ( $domain->checkDomain($authentication->api_id, $domein) )
                {
                    $data = file_get_contents('php://input');

                    if (get_object_vars(json_decode($data)) == true) $film->insertFilm($data);
                }
                else echo "Api verzoek afgewezen vanaf dit domein";
            }
            else echo "Niet de juiste rechten voor deze actie.";
        }
        else echo "Ongeldige api key.";
    }
    // Check for a PUT request
    else if ( $_SERVER['REQUEST_METHOD'] === 'PUT' ) 
    {
        if ( $authentication->checkApiKey($apiKey) )
        {
            if ( ($authentication->api_level >= 3)  ) 
            {
                if ( $domain->checkDomain($authentication->api_id, $domein) )
                {
                    $data = file_get_contents('php://input');

                    if (get_object_vars(json_decode($data)) == true) $film->updateFilm($data);
                }
                else echo "Api verzoek afgewezen vanaf dit domein";
            }
            else echo "Niet de juiste rechten voor deze actie.";
        }
        else echo "Ongeldige api key.";
    }
    // Check for a PUT request
    else if ( $_SERVER['REQUEST_METHOD'] === 'DELETE' ) 
    {
        if ( $authentication->checkApiKey($apiKey) )
        {
            if ( ($authentication->api_level >= 4)  ) 
            {
                if ( $domain->checkDomain($authentication->api_id, $domein) )
                {
                    if ( isset($_REQUEST['id']) && !empty($_REQUEST['id']) ) $film->deleteFilm($_REQUEST['id']);
                }
                else echo "Api verzoek afgewezen vanaf dit domein";
            }
            else echo "Niet de juiste rechten voor deze actie.";
        }
        else echo "Ongeldige api key.";
    }
?>