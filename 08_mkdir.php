<?php
require("includes/common.inc.php");
require("includes/config.inc.php");

$msg = "";

if(count($_POST)>0){
    $pfad = $_POST["VZneu"];
    if(file_exists($pfad)){
        $msg = '<p class="info">Dieses Verzeichnis existiert bereits.</p>';
    }
        else{
            $ok = @mkdir($pfad,0755,true);
            if($ok){
                $msg = '<p class="info">Das Verzeichnis wurde erfolgreich angelegt.</p>';
            }
            else {
                $msg = '<p class="error">Anlegen fehlgeschlagen.</p>';
            }
    
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mkdir</title>
    <link rel="stylesheet" href="css/common.css">
</head>
<body>
    <?php echo($msg); ?>
    <form method="post">
        <label for="VZneu">Pfad zum Verzeichnis:</label>
        <input type="text" name="VZneu" id="VZneu" required>
        <input type="submit" value="Verzeichnis anlegen">
    </form>
</body>
</html>