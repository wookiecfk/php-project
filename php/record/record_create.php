<html>
<head>
    <meta charset="UTF-8">
    <title>Application for music record emmision storage</title>
    <link rel="stylesheet" type='text/css' href="../styles.css">
</head>
<body>

<h4> Record creation... </h4><br>

<?php
require_once("RecordManager.php");
$record_manager = new RecordManager();
$username = $_GET["name"];
$password = $_GET["password"];
$password_repeat = $_GET["password_repeat"];
$result = $record_manager->createRecord($_GET["fileName"], $_GET["title"], $_GET["IRSCCode"], $_GET["compositor"],
    $_GET["author"], $_GET["coverAuthor"], $_GET["durationSeconds"]);
?>

<h4> Your record creation process was: <?php echo $result === true ? "successful" : "unsuccessful"?> </h4>

<h2>Welcome to the emission music storage application</h2>
<h2><a href="../record/create_record.html">Create Record</a></h2>
</body>
</html>
