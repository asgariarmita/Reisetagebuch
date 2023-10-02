<?php
require("includes/conn.inc.php");

// Use a JOIN clause to retrieve all necessary information in one go.
$sql_reisen = "
    SELECT 
        r.Titel AS ReiseTitel,
        r.Beschreibung AS ReiseBeschreibung,
        a.*,
        s.Staat
    FROM tbl_reisen r
    LEFT JOIN tbl_abschnitte a ON r.IDReise = a.FIDReise
    LEFT JOIN tbl_staaten s ON a.FIDStaat = s.IDStaat
    WHERE NOT r.FIDUser = 2
";
$result_reisen = $conn->query($sql_reisen) or die("Fehler in der Query: " . $conn->error);

$reisen = [];
while ($row_reisen = mysqli_fetch_assoc($result_reisen)) {
    $reisen[] = $row_reisen;
}

$sql_skala = "SELECT * FROM tbl_skala";
$result_skala = $conn->query($sql_skala) or die("Fehler in der Query: " . $conn->error);

$skalas = [];
while ($row_skala = mysqli_fetch_assoc($result_skala)) {
    $skalas[] = $row_skala;
}

if (isset($_POST["submit"])) {
    $b = $_POST["bew"];
    $sql_vote = $conn->prepare("INSERT INTO tbl_votings (FIDUser, FIDReise, FIDBewertung, Zeitpunkt) VALUES (?, ?, ?, current_timestamp())");
    $sql_vote->bind_param("iii", $FIDUser, $FIDReise, $b);
    $FIDUser = 2; // Replace this with the appropriate user ID
    $FIDReise = $reise_id; // Ensure $reise_id is set beforehand
    if ($sql_vote->execute()) {
        echo "Bewertung erfolgreich abgegeben.";
    }
    $sql_vote->close();
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Bewertungen</title>
    <style>
        /* Styles */
    </style>
</head>

<body>
    <!-- PHP logic here -->
    <?php foreach ($reisen as $reise) : ?>
        <br><strong><?= $reise["ReiseTitel"] ?></strong><br><?= $reise["ReiseBeschreibung"] ?>
        <!-- Continue with the HTML and embedded PHP as needed -->
    <?php endforeach; ?>

    <form action="bewertungengpt.php" method="post">
        <label for=""></label>
        <select name="bew">
            <?php foreach ($skalas as $skala) : ?>
                <option value="<?= $skala["IDSkala"] ?>"><?= $skala["Bewertung"] ?> (<?= $skala["Wert"] ?>)</option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="submit" value="bewerten">
    </form>
</body>

</html>