<?php
require("includes/conn.inc.php");
// echo "<script> alert('High') </script>";
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Anmeldeseite</title>
    <style>
        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 50%;
            padding: 10px;
            height: 300px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .warning {
            position: absolute;
            left: 20px;
            bottom: 20px;
            background-color: lightcoral;
            padding: 1em;
            width: 95vw;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="column">
            <h4>Regsitrierung</h4>

            <form action="regsiter.php" method="post">
                <label for="e">E-Mail:</label><br>
                <input type="email" name="e" required><br><br>

                <label for="p">Passwort:</label><br>
                <input type="password" name="p" minlength="8" required><br><br>
                <input type="password" placeholder="password wiederholen" name="p2" required title="Must contain at least 8 or more characters"><br><br>

                <label for="v">Vorname:</label><br>
                <input type="text" name="v" required><br>

                <p>Optionale Angaben:</p>
                <label for="n">Nachname:</label><br>
                <input type="text" name="n"><br><br>

                <label for="eb">eigene Beschreibung:</label><br>
                <textarea name="eb" rows="3"></textarea><br>

                <input type="submit" name="reg">
            </form>
        </div>
        <div class="column">
            <h4>Login</h4>
            <form action="regsiter.php" method="post">
                <label for="el">E-Mail:</label><br>
                <input type="email" name="el" require><br><br>

                <label for="pl">Passwort:</label><br>
                <input type="password" name="pl" require><br><br>

                <input type="submit" name="log">
            </form>
        </div>
    </div>
</body>

</html>
<?php
$IsUserRegistered = false;
if (isset($_POST["reg"])) {

    $sql = "SELECT * FROM tbl_user";
    $result = $conn->query($sql) or die("Fehler in der Query: " . $conn->error);
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row["Emailadresse"] == $_POST['e']) {
            echo "<div class='warning'>User already exists</div>";
            $IsUserRegistered = True;
        }
    }

    if ($IsUserRegistered == false) {
        if ($_POST["p"] == $_POST["p2"]) {
            $datetime = date('Y-m-d H:i:s');

            $e = mysqli_real_escape_string($conn, $_POST['e']);
            $p = mysqli_real_escape_string($conn, $_POST['p']);
            $v = mysqli_real_escape_string($conn, $_POST['v']);
            $n = empty($_POST['n']) ? 'NULL' : "'" . mysqli_real_escape_string($conn, $_POST['n']) . "'";
            $eb = empty($_POST['eb']) ? 'NULL' : "'" . mysqli_real_escape_string($conn, $_POST['eb']) . "'";

            $sql_register = "INSERT INTO tbl_user (Emailadresse, Passwort, Vorname, Nachname, Beschreibung, RegZeitpunkt) 
        VALUES ('$e', '$p', '$v', $n, $eb, current_timestamp())";

            $result_register = $conn->query($sql_register) or die("Fehler in der Query: " . $conn->error);

            if ($result_register) {
                $last_id = $conn->insert_id;
                $_SESSION["ID"] = $last_id;
                header("Location: logged.php");
            }
        } else {
            echo "<br><div class='warning'>Your passwords don't match!</div>";
        }
    }
}
?>