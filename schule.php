<?php 
$conn = new MySQLi("localhost", "root", "", "schule_db");
if($conn->connect_errno>0) {
    die("Fehler im Verbindungsaufbau: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schule</title>
</head>
<body>
    <h1>Beispiel Schule</h1>
    <h2>Alle Klassen inkl. Raum, Lehrer und Schüler</h2>
    <ul>
        <?php
        $sql = "
        SELECT
            tbl_klasse.IDKlasse,
            tbl_klasse.Bezeichnung,
            tbl_raeume.Bezeichnung AS bezRaum,
            tbl_lehrer.Vorname,
            tbl_lehrer.Nachname
        FROM tbl_klasse
        LEFT JOIN tbl_raeume ON tbl_raeume.IDRaum=tbl_klasse.FIDRaum
        LEFT JOIN tbl_lehrer ON tbl_lehrer.IDLehrer=tbl_klasse.FIDLehrer
        ";
        $klassen = $conn->query($sql) or die("Fehler in der Query: " . $conn->error);
        while($klasse = $klassen->fetch_object()) {
            echo('
                <li>' . $klasse->Bezeichnung . ': Raum ' . $klasse->bezRaum . ' KV ' . $klasse->Nachname . '' . $klasse->Vorname
            );

            echo('<ul>');
            $sql = "
            SELECT Vorname, Nachname FROM tbl_schueler
            WHERE(
                FIDKlasse=" . $klasse->IDKlasse . "
            )
            ";
            $schuelerJeKlasse = $conn->query($sql) or die("Fehler in der Query: " . $conn->error);
            while($schueler = $schuelerJeKlasse->fetch_object()) {
                echo('
                    <li>' . $schueler->Nachname . '' . $schueler->Vorname . '</li>
                ');
            }
            echo('</ul>');
            }
            echo('<li>');
            ?>
    </ul>

    <h2>Alle Schüler</h2>
    <ul>
    <form method="post">
            <label for="VN">Vorname:</label>
            <input type="text" name="VN" id="VN">
            <label for="NN">Nachname:</label>
            <input type="text" name="NN" id="NN">
            <input type="submit" value="suchen">
    </form>

    <?php
        if(count($_POST)>0){
            $arr = array();
            if(strlen($_POST["VN"])>0) {
                $arr[] = "Vorname LIKE '%" . $_POST["VN"] . "%'";
            }
            if(strlen($_POST["NN"])>0) {
                $arr[] = "Nachname LIKE '%" . $_POST["NN"] . "%'";
            }

            $sqlW = "
            WHERE(
                " . implode("AND ", $arr) . "
                )
            ";
        }
        else {
            $sqlW = "";
        }

        $sql = "
            SELECT Vorname, Nachname FROM tbl_schueler
            " . $sqlW . "
            ORDER BY Nachname ASC, Vorname ASC
        ";
        $schuelerliste = $conn->query($sql) or die("Fehler in der Query: " . $conn->error);
        while($schueler = $schuelerliste->fetch_object()) {
            echo('
                <li>' . $schueler->Nachname . ' ' . $schueler->Vorname . '</li>
            ');
        }
    ?>
</ul>
        <h2>Alle Räume</h2>
        <ul>
            <?php
            $sql = "
            SELECT * FROM tbl_raeume
            ";
            $raeume = $conn->query($sql) or die("Fehler in der Query: " . $conn->error);
            while($raum = $raeume->fetch_object()) {
                $sql = "
                SELECT Bezeichnung FROM tbl_klasse
                WHERE(
                    FIDRaum=" . $raum->IDRaum . "
                )
            ";
            }
            $klassen = $conn->query($sql) or die("Fehler in der Query: " . $conn->error);
            $klasse = $klassen->fetch_object();
            if(is_object($klasse)) {
                $bezKlasse = $klasse->Bezeichnung;
            }
            else {
                $bezKlasse = "";
            }

            echo('
                <li>' . $raum->Bezeichnung . ': ' . $bezKlasse . '</li>');
            ?>
        </ul>



</body>
</html>