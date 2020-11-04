<?php
    class DatabaseConnectionWrapper {
        public function getDatabaseConnection() {
            $con = new mysqli('localhost', 'root', 'Woookieee111', 'php_app');
            return $con;
        }
    }
?>
