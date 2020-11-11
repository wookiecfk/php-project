<html>
<body>

Welcome <?php echo $_GET["name"]; ?><br>

<?php
require_once("UserManager.php");
$user_manager = new UserManager();
$username = $_GET["name"];
$password = $_GET["password"];
$result = $user_manager->loginUser($username, $password);
?>

Your login process was: <?php echo $result === true ? "successful" : "unsuccessful"?>

</body>
</html>
<?php
