<?php
require("../includes/common.inc.php");

$conn = new mysqli("localhost","root", "","db_phpmysql");

if($conn->connect_errno>0) {
    die("Fehler im Verbindungsaufbau; " . $conn->connect_error);
}

$msg = "";
$anzDatensaetzeProSeite = 5;
$seite = 1;

$sql = "
    SELECT COUNT(*) AS cnt FROM tbl_user
";
$daten = $conn->query($sql) or die ("Fehler in der Query: " . $conn->error);
$zeile = $daten->fetch_object();
$anzDatensaetzeGesamt = $zeile->cnt;
$maxSeite = ceil($anzDatensaetzeGesamt/$anzDatensaetzeProSeite); //ceil = aufrunden


if(count($_POST)>0) {
    ta($_POST);
    $seite = $_POST["neueSeite"];

    switch($_POST["wasTun"]) {
        case "einfügen":
            $sql = "
                INSERT INTO tbl_user
                    (Emailadresse, Passwort, Vorname, Nachname, GebDatum)
                
                VALUES (
                        '" . $_POST["Emailadresse_0"] . "',
                        '" . $_POST["Passwort_0"] . "',
                        '" . $_POST["Vorname_0"] . "',
                        '" . $_POST["Nachname_0"] . "',
                        '" . $_POST["GebDatum_0"] . "'
                    )
            ";
            ta($sql);
            $ok = $conn->query($sql) or die("Fehler in der Query: " . $conn->error);

            if($ok) {
                    $msg = '<p class="success">Vielen Dank. Ihr Eintrag war erfolgreich.</p>';
            }
            else {
                $msg = '<p class="error">Leider konnte Ihr Eintrag nicht wie gewünscht durchgeführt werden</p>';
            }
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL</title>
    <link rel="stylesheet" href="css/common.css">
    <script>
        function blaettere(richtung) {
            let aktuelleSeite = parseInt(document.getElementById("neueSeite").value);
            if((aktuelleSeite + richtung>0) && (aktuelleSeite + richtung<=<?php echo($maxSeite); ?>)) {
                let neueSeite = aktuelleSeite + richtung;
                document.getElementById("neueSeite").value = neueSeite;
                document.getElementById("frm").submit();
            }

        }
        function einfuegen() {
            document.getElementById("wasTun").value = "einfügen";
            document.getElementById("frm").submit();
            
        }
    </script>
</head>
<body>
    <?php echo($msg); ?>
    <form method="post" id="frm">
        <input type="hidden" name="wasTun" id="wasTun">
        <button type="button" onclick="blaettere(-1);">&lsaquo;</button>
        <button type="button" onclick="blaettere(1);">&rsaquo;</button>
        <input type="number" value="<?php echo($seite); ?>" name="neueSeite" id="neueSeite" min="1" max="<?php echo($maxSeite); ?> readonly">
        <table>
            <thead>
                <tr>
                    <th scope="col">IDUser</th>
                    <th scope="col">Vorname</th>
                    <th scope="col">Nachname</th>
                    <th scope="col">Email</th>
                    <th scope="col">Passwort</th>
                    <th scope="col">Geb.-Datum</th>
                    <th scope="col">Reg.-Zeitpunkt</th>
                    <th></th>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="text" name="Vorname_0" id="Vorname_0"></td>
                    <td><input type="text" name="Nachname_0" id="Nachname_0"></td>
                    <td><input type="email" name="Emailadresse_0" id="Emailadresse_0"></td>
                    <td><input type="password" name="Passwort_0" id="Passwort_0"></td>
                    <td><input type="date" name="GebDatum_0" id="GebDatum_0"></td>
                    <td></td>
                    <td><button type="button" onclick="einfuegen();">INS</button></td>
                </tr>
            </thead>
        <tbody>
            <?php
            $sql = "
            SELECT * FROM tbl_user
            LIMIT " . ($seite-1)*$anzDatensaetzeProSeite . "," . $anzDatensaetzeProSeite . "
            ";
            $antwort = $conn->query($sql) or die ("Fehler in der Query: " . $conn->error);
            
            while($zeile = $antwort->fetch_object()){
                echo('
                <tr>
                <td>' . $zeile->IDUser . '</td>
                <td>' . $zeile->Vorname . '</td>
                <td>' . $zeile->Nachname . '</td>
                <td>' . $zeile->Emailadresse . '</td>
                <td>' . $zeile->Passwort . '</td>
                <td>' . $zeile->GebDatum . '</td>
                <td>' . $zeile->RegZeitpunkt . '</td>
                <td></td>
                ');
            }
            ?>
        </tbody>
    </table>
</form>
</body>
</html> 