<?php
    require "config/Database.php";

	class checkApiKey {
		public $api_id;
        private $api_key;
        public $api_level;
		
		public function checkApiKey($api_key)
        {
            $db = new Database();
            $stmt = $db->connection->prepare("SELECT * FROM bioscoopapi WHERE apiKey = ?");
            $stmt->bind_param('?', $api_key);
            $stmt->execute();
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