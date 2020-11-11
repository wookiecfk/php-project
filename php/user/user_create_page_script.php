
<html>
<body>

Welcome <?php echo $_GET["name"]; ?><br>

<?php
require_once("UserManager.php");
$user_manager = new UserManager();
$username = $_GET["name"];
$password = $_GET["password"];
$password_repeat = $_GET["password_repeat"];
$result = $user_manager->createUser($username, $password, $password_repeat);
?>

Your user creation process was: <?php echo $result === true ? "successful" : "unsuccessful"?>

<h2><a href="user_login.html">Login user</a></h2>
</body>
</html>
