<?php
    if(function_exists('mysqli_connect')) {
        printf(nl2br("Rozszerzenie MySQLi zainstalowane poprawnie\n"));
    } else {
        echo "Niestety MySQLi nie działa";
    }
    $con = new mysqli('localhost', 'root', 'Woookieee111', 'php_app');
    $result_of_query = $con->query("SELECT * FROM test");
    if (gettype($result_of_query) == "boolean") {
        printf("BOOLEAN VALUE!!!");
    } else {
        while ($row = $result_of_query->fetch_assoc()) {
            printf("ID %s VARCHAR1 %s VARCHAR2 %s NUMBER %s",
                $row["TEST1"], $row["TEST2"], $row["TEST3"], $row["TEST4"]);
            printf(nl2br("\n"));
        }
    }
?>