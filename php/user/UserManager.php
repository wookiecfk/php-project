<?php
    require_once(LIB.'/database/DatabaseConnectionWrapper.php');
    class UserManager {
        public function createUser($user_name, $user_password, $user_password_repeat) {
            //TODO more robust validation
            if ($user_name == '' or $user_password == '' or $user_password_repeat == '') {
                throw new Exception('Incorrect input data');
            }
            if ($user_password != $user_password_repeat) {
                throw new Exception('Passwords do not match');
            }
            $escaped_user_name = $this->getEscapedUserName($user_name);
            $crypto_user_password = $this->getCryptoUserPassword($user_password);
        }

        public function getEscapedUserName($user_name) {
            $con_wrapper = new DatabaseConnectionWrapper();
            $con = $con_wrapper->getDatabaseConnection();
            $escaped_user_name = $con->real_escape_string($user_name);
            return $escaped_user_name;
        }

        public function getCryptoUserPassword($user_password) {
            return sha1($user_password);
        }

        public function userExists($user_name, $user_password) {
            $con_wrapper = new DatabaseConnectionWrapper();
            $con = $con_wrapper->getDatabaseConnection();
            $escaped_user_name = $this->getEscapedUserName($user_name);
            $crypto_user_password = $this->getCryptoUserPassword($user_password);
        }
    }
?>
