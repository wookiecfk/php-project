<?php
    require_once('../database/DatabaseConnectionWrapper.php');
    require_once ('../helper/helper.php');
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
            try {
                $con = $this->getDatabaseConnection();
                if ($this->userExists($escaped_user_name)) {
                    return false;
                }
                $insert_query = "insert into users(user_name, password) 
                    values ('$escaped_user_name', '$crypto_user_password')";
                $result = $con->query($insert_query);
                if ($result === false) {
                    throw new Exception('Cannot insert user due to unknown reason');
                }
                $con->close();
                return true;
            } catch (Exception $e) {
                //TODO some error handling
                if(isset($con)) {
                    $con->close();
                }
                return false;
            }
        }

        public function loginUser($user_name, $user_password) {
            if ($user_name == '' or $user_password == '') {
                throw new Exception('Incorrect input data');
            }
            console_log('Data valid');
            try {
                $escaped_user_name = $this->getEscapedUserName($user_name);
                $crypto_user_password = $this->getCryptoUserPassword($user_password);
                $con = $this->getDatabaseConnection();

                console_log('Does user exist ? ');
                //Check if user exists at all
                $result_user_exists = $con->query("SELECT * from users where user_name = '$escaped_user_name'
                and password = '$crypto_user_password'");
                if ($result_user_exists === false) {
                    throw new Exception("Database query unsuccessful");
                    console_log('Query crapped out');
                } else if ($result_user_exists->fetch_assoc() === NULL) {
                    $user_exist_result = false;
                    console_log('Query showed user does not exist');
                } else {
                    $user_exist_result = true;
                    console_log('Query showed user exists');
                }
                if ($user_exist_result === false) {
                    return false;
                }
                console_log('Is user already logged in? ');
                //Check if user already logged in
                $result_user_already_logged_in =
                    $con->query("SELECT * from loggedusers where user_name = '$escaped_user_name'");
                if ($result_user_already_logged_in === false) {
                    throw new Exception("Database query unsuccessful");
                    console_log('Query crapped out');
                } else if ($result_user_already_logged_in->fetch_assoc() === NULL) {
                    $user_already_logged_in = false;
                    console_log('User not logged in');
                } else {
                    $user_already_logged_in = true;
                    console_log('User already logged in');
                }
                if (@$user_already_logged_in === true) {
                    //TODO REDIRECT MAYBE
                    return false;
                }

                //Login user
                $sessionid = $this->sessionId();
                console_log('Assigning user session id:');
                console_log($sessionid);
                console_log('Logging in user');
                $result_user_login =
                    $con->query("INSERT INTO loggedusers(user_name, sessionid) 
                        values ('$escaped_user_name', '$sessionid')");
                if ($result_user_login === false) {
                    throw new Exception("Database query unsuccessful");
                    console_log('Query crapped out');
                }
                //Successful login with database insert
                $con->close();
                //Starting session
                session_start();
                //Adding sessionId and username to session variables
                $_SESSION['user_name'] = $escaped_user_name;
                $_SESSION['sessionId'] = $sessionid;
                console_log('User logged in');
                return true;
            } catch (Exception $e) {
                if (isset($con)) {
                    $con->close();
                }
                return false;
            }
        }

        public function logoutUser() {
            //Check if session is inactive
            console_log('Logging out user');
            if ($isSessionSet = !isset($_SESSION)) {
                //User session is gone
                console_log('Active session not set');
            } else {
                console_log('Active session is set');
            }
            $con = $this->getDatabaseConnection();
            $username = $_SESSION['user_name'];
            $sessionId = $_SESSION['sessionId'];
            $logout_result = $con->query("DELETE FROM loggedusers where user_name = '$username'
            and sessionid = '$sessionId'");
            if ($logout_result === false) {
                throw new Exception('Database query unsuccessful');
                console_log('Logging out unsuccessful');
            } else {
                console_log('User logged out');
                if ($isSessionSet) {
                    setcookie(session_name(), '', 100);
                    session_unset();
                    session_destroy();
                    console_log('Session destroyed');
                }
            }
        }

        private function sessionId() {
            return session_create_id();
        }

        public function doesUserExist($user_name, $user_password) {
            //TODO MAYBE ???
        }

        public function getEscapedUserName($user_name) {
            $con = $this->getDatabaseConnection();
            $escaped_user_name = $con->real_escape_string($user_name);
            return $escaped_user_name;
        }

        public function getDatabaseConnection() {
            $con_wrapper = new DatabaseConnectionWrapper();
            return $con_wrapper->getDatabaseConnection();
        }

        public function getCryptoUserPassword($user_password) {
            return sha1($user_password);
        }

        public function userExists($user_name) {
            $con = $this->getDatabaseConnection();
            $escaped_user_name = $this->getEscapedUserName($user_name);
            //TODO Add implementation
            return false;
        }
    }
?>
