<?php
    require "config/Database.php";

    class Domain
    {
        private $domain_id;
        private $domain_url;
        private $api_id;

        public function checkDomain($apiId, $domein)
        {
            $found = false;

            $db = new Database();
            $stmt = $db->connection->prepare("SELECT * FROM domain WHERE api_id = ?");
            $stmt->bind_param('i', $apiId);
            $stmt->execute();
            $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $db->connection->close();

            foreach ($results as $result)
            {
                if ( $result['domain_url'] == $domein ) 
                {
                    $found = true;
                    break;
                }
            }

            if ( $found ) return true;
            else return false;
        }
    }
?>