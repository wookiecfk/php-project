<html>
<body>

<?php
require_once("UserManager.php");
$user_manager = new UserManager();
$result = $user_manager->logoutUser();
?>

<h2>User logged out</h2>

</body>
</html>
<?php