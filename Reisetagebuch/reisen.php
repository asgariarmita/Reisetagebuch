<?php
// 17:27
require("includes/conn.inc.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reisen</title>
</head>

<body>
    <form action="reisen.php" method="post">
        <label for="v">Vorname:</label>
        <input type="text" name="v">
        <input type="submit" value="search" name="s">
    </form>
</body>

<?php

if (isset($_POST["s"])) {

    $searchstring = $_POST['v'];

    $sql_search = "SELECT * FROM tbl_user WHERE tbl_user.Vorname LIKE '%$searchstring%';";
    $result_search = $conn->query($sql_search) or die("Fehler in der Query: " . $conn->error);

    echo "<ul>";
    while ($row_search = mysqli_fetch_assoc($result_search)) {
        echo "<li>";
        echo $row_search["Vorname"] . " " . $row_search["Nachname"] . "<br>";

        $sql_search_reisen = "SELECT * FROM tbl_reisen WHERE tbl_reisen.FIDUser = " . $row_search["IDUser"] . ";";
        $result_search_reisen = $conn->query($sql_search_reisen) or die("Fehler in der QUery: " . $conn->error);

        echo "<ul>";
        while ($row_search_reisen = mysqli_fetch_assoc($result_search_reisen)) {
            echo "<li>";
            echo "<strong>" . $row_search_reisen["Titel"] . "</strong><br>" . $row_search_reisen["Beschreibung"];
            echo "</li>";
        }
        echo "</ul>";

        echo "</li>";
    }
    echo "</ul>";
} else {

    $sql_user = "SELECT * FROM tbl_user";
    $result_user = $conn->query($sql_user) or die("Fehler in der Query: " . $conn->error);

    echo "<ul>";
    while ($row_user = mysqli_fetch_assoc($result_user)) {
        echo "<li>";
        echo $row_user["Vorname"] . " " . $row_user["Nachname"] . "<br>";

        $sql_reisen = "SELECT * FROM tbl_reisen WHERE tbl_reisen.FIDUser = " . $row_user["IDUser"] . ";";
        $result_reisen = $conn->query($sql_reisen) or die("Fehler in der QUery: " . $conn->error);

        echo "<ul>";
        while ($row_reisen = mysqli_fetch_assoc($result_reisen)) {
            echo "<li>";
            echo "<strong>" . $row_reisen["Titel"] . "</strong><br>" . $row_reisen["Beschreibung"];
            echo "</li>";
        }
        echo "</ul>";

        echo "</li>";
    }
    echo "</ul>";
}
?>

</html>