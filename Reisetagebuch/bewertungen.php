<?php
require("includes/conn.inc.php");

$sql_reisen = "SELECT * FROM tbl_reisen WHERE NOT tbl_reisen.FIDUser = 2";
$result_reisen = $conn->query($sql_reisen) or die("Fehler in der Query: " . $conn->error);

while ($row_reisen = mysqli_fetch_assoc($result_reisen)) {
    echo "<br><strong>" . $row_reisen["Titel"] . "</strong><br>" . $row_reisen["Beschreibung"];

    $sql_abscchnitte = "SELECT tbl_abschnitte.*, tbl_staaten.* 
    FROM tbl_abschnitte
    INNER JOIN tbl_staaten
    ON tbl_abschnitte.FIDStaat = IDStaat 
    WHERE tbl_abschnitte.FIDReise =" . $row_reisen["IDReise"];
    $result_abschnitte = $conn->query($sql_abscchnitte) or die("Fehler in der Query: " . $conn->error);

    $reise_id = $row_reisen['IDReise'];

    echo "<ul>";
    while ($row_abschnitte = mysqli_fetch_assoc($result_abschnitte)) {
        if ($row_abschnitte["FIDAbschnitt"] == 0) {
            echo "<li>";
            echo "<pre style='color:Gray'>" . $row_abschnitte["von"] . " Uhr bis " . $row_abschnitte["bis"] . " Uhr </pre>";
            echo $row_abschnitte["Titel"] . "<br>";
            echo $row_abschnitte["Beschreibung"];
            echo "<pre style='color:Gray'>" . $row_abschnitte["Staat"] . "</pre>";

            displaysub($conn, $row_abschnitte["IDAbschnitt"]);

            echo "</li>";
        }
    }
    echo "</ul>";
?>

    <!DOCTYPE html>
    <html lang="de">

    <head>
        <meta charset="UTF-8">
        <title>Bewertungen</title>
        <style>

        </style>
    </head>

    <body>
        <form action="bewertungen.php" method="post">
            <input type="hidden" name="reise_id" value="<?= $reise_id ?>"> <!-- Hidden input to store the reise_id -->
            <label for=""></label>
            <select name="bew">
                <?php
                $sql_skala = "SELECT * FROM tbl_skala";
                $result_skala = $conn->query($sql_skala) or die("Fehler in der Query: " . $conn->error);
                while ($row_skala = mysqli_fetch_assoc($result_skala)) {
                    $id_skala = $row_skala["IDSkala"];
                    $bewertung = $row_skala["Bewertung"];
                    $wert = $row_skala["Wert"];
                    echo "<option value='$id_skala' name=''>$bewertung ($wert)</option>";
                }
                ?>
            </select>
            <input type="submit" name="submit" value="bewerten">
        </form>
        <?php

        if (isset($_POST["submit"]) && isset($_POST["reise_id"]) && $_POST["reise_id"] == $reise_id) {
            $b = $_POST["bew"];
            $sql_vote = "INSERT INTO tbl_votings (FIDUser, FIDReise, FIDBewertung, Zeitpunkt)
            VALUES ('2','$reise_id','$b',current_timestamp())";
            $result_vote = $conn->query($sql_vote) or die("Fehler in der Query: " . $conn->error);
            if ($result_vote) {
                echo "Bewertung erfolgreich abgegeben.";
            }
        }

        ?>
        </form>
    </body>

    </html>

<?php
}

function displaysub($conn, $id)
{
    $sql_sub = "SELECT tbl_abschnitte.*, tbl_staaten.*
    FROM tbl_abschnitte
    INNER JOIN tbl_staaten
    ON tbl_abschnitte.FIDStaat = IDStaat
    WHERE tbl_abschnitte.FIDAbschnitt = $id";
    $result_sub = $conn->query($sql_sub) or die("Fehler in der Query: " . $conn->error);

    echo "<ul>";
    while ($row_sub = mysqli_fetch_assoc($result_sub)) {
        echo "<li>";
        echo "<pre style='color:Gray'>" . $row_sub["von"] . " Uhr bis " . $row_sub["bis"] . " Uhr </pre>";
        echo $row_sub["Titel"] . "<br>";
        echo $row_sub["Beschreibung"];
        echo "<pre style='color:Gray'>" . $row_sub["Staat"] . "</pre>";
        echo "</li>";
    }
    echo "</ul>";
}
?>