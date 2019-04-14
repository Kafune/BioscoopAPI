<?php
    require "config/Database.php";

    class Authentication
    {
        public $api_id;
        private $api_key;
        public $api_level;

        public function checkApiKey($apiKey)
        {
            $apiKey = filter_var($apiKey, FILTER_SANITIZE_STRING);

            $db = new Database();
            $stmt = $db->connection->prepare("SELECT * FROM bioscoopapi WHERE apiKey = ?");
            $stmt->bind_param('s', $apiKey);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $db->connection->close();

            if ( $result['api_id'] > 0 ) {
                $this->api_id = $result['apiID'];
                $this->api_level = $result['apiLevels'];

                return true;
            }
            else return false;
        }
    }
?>