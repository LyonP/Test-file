<?php
require("../includes/common.inc.php");
require("../includes/config.inc.php");

$pfad = DATADIR;
$msg = "";

if(count($_POST)>0) {
    $pfad = $_POST["VZ"];
    ta($_POST);

    switch($_POST["wasTun"]) {
        case "anlegen":
            $ok = @mkdir($pfad . $_POST["VZNeu"],0755,false);
            if($ok) {
                $msg = '<p class="success">Das gewünschte Verzeichnus wurde erfolgreich angelegt.</p>';
            }
            else {
                $msg = '<p class="error">Leider konnte das gewünschte Verzeichnis nicht angelegt werden. Bitte überprüfen Sie den Verzeichnisnamen.</p>';
            }
            break;
        case "löschen":
            foreach($_POST["auswahl"] as $dvv) {
                if(is_dir($dvv)) {
                    loescheVZ($dvv . "/");
                }
                else {
                    unlink($dvv);
                }
            }
            break;
        case "verschieben":
            foreach($_POST["auswahl"] as $dvv) {
                $tmp = explode("/", $dvv);
                $name = $tmp[count($tmp)-1];
                rename($dvv,$_POST["VZToMove"] . $name);
            }
            break;
    }
}

if(count($_FILES)>0) {
    //es wurden vermutlich Dateien hochgeladen
    // ta($_FILES);
    $file = $_FILES["auswahlUL"];

    if(count($file["name"])>0 && strlen($file["name"][0])>0) {
            for($i=0; $i<count($file["name"]); $i++) {
                if($file["error"][$i]==0) {
                    $ok = move_uploaded_file($file["tmp_name"][$i],$pfad . $file["name"][$i]);
                    if($ok) {
                        $msg .= '<p class="success">Die Datei ' . $file["name"][$i] . ' wurde erfolgreich hochgeladen.</p>';
                    }
                    else {
                        $msg .= '<p class="error">Die Datei ' . $file["name"][$i] . ' konnte leider nicht hochgeladen werden.</p>';
                    }
                }
                else {
                    $msg .= '<p class="error">Die Datei ' . $file["name"][$i] . ' konnte leider nicht hochgeladen werden.</p>'; 
                }
            }
        }   
}


function loescheVZ($root) {
    $inhalt = scandir($root);
    foreach($inhalt as $dvv) {
        if($dvv!="." && $dvv!="..") {
            if(is_dir($root . $dvv)) {
                loescheVZ($root . $dvv . "/");
            }
            else {
                unlink($root . $dvv . "/");
            }
        }
    }

    rmdir($root);
}

function zeigeVZInh($pfadIn) {
    $html = '
            <table>
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Größe</th>
                        <th scope="col">zuletzt geändert</th>
                    </tr>
                </thead>
                <tbody>

                        
    ';

    $inhalt = scandir($pfadIn);
    foreach($inhalt as $dvv) {
        if($dvv!="." && $dvv!=".."){
            $code = "";
            switch(true) {
                case is_dir($pfadIn . $dvv):
                    $class = 'dir';
                    $code = 'onDblClick="JS_leseVZ(\''. $pfadIn . $dvv .'/\');"';
                    break;

                case is_file($pfadIn . $dvv):
                    $class = 'file';
                    break;

                case is_link($pfadIn . $dvv):
                    $class = 'link';
                    break;

            }
            $html.= '
                <tr class="' . $class . '">
                    <td><input type="checkbox" name="auswahl[]" value="' . $pfadIn . $dvv . '"></td>
                    <td class="dvvname">
                    <span ' . $code . '>' . $dvv . '</span></td>
                    <td></td>
                    <td></td>
                </tr>
            ';
        }
    }

    $html .= '
               </tbody>
               <tfoot>
               </tfoot>
            </table>
    ';

    return $html;
}

function createBreadcrumbnav($pfadIn) {
    $html = '
        <ul class="breadcrumb">
    ';

    $arr = explode("/", $pfadIn);
    $pfad = $arr[0] . "/";
    for($i=1; $i<count($arr)-1; $i++) {
    $pfad .= $arr[$i] . "/";    
    $html .= '
        <li><a onclick="JS_leseVZ(\'' . $pfad . '\');">' . $arr[$i] . '</a></li>';
    }

    $html .= '
        </ul>
    ';
    return $html;
}

function zeigeVZStruktur($root,$isRoot=true) {
    $html = '<ul>';

    if($isRoot) {
        $tmp = explode("/",DATADIR);
        $mainroot = $tmp[1];

        $html.= '
            <li><label><input type="radio" name="VZToMove" value="' . DATADIR . '">' . $mainroot . '</label><ul>
        ';
    }
    $inhalt = scandir($root);
    foreach($inhalt as $dvv) {
        if($dvv!="." && $dvv!=".."){
            if(is_dir($root . $dvv)) {
                $html.= '<li><label><input type="radio" name="VZToMove" value="' . $root . $dvv . '/">' . $dvv . '</label>';
                $html.= zeigeVZStruktur($root . $dvv . "/",false);
                $html.= '</li>';
            }
        }
    }
    if($isRoot) {
        $html.= '</ul></li>';
    }
    $html = '</ul>';
    return $html;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cloud Ablagesystem</title>
    <link rel="stylesheet" href="css/cloud.css">
    <link rel="stylesheet" href="../learn/css/common.css">
    <script src="js/jquery-3.5.0.min.js"></script>
    <script>
        function JS_leseVZ(js_pfad) {
            document.getElementById("VZ").value = js_pfad;
            document.getElementById("frm").submit();
        }
        
        function JS_legeVZAn() {
            document.getElementById("wasTun").value = "anlegen";
            document.getElementById("frm").submit();
        }
        function JS_delDvv() {
            document.getElementById("wasTun").value = "löschen";
            document.getElementById("frm").submit();
        }
        function JS_blendeVZStrukturEin() {
            $("#fsStruktur").toggle();
        }
        function JS_verschiebe() {
            document.getElementById("wasTun").value = "verschieben";
            document.getElementById("frm").submit();    
        }
    </script>
</head>

<body>
    <form method="post" id="frm" enctype="multipart/form-data">
        <input type="hidden" name="VZ" id="VZ" value="<?php echo($pfad); ?>">
        <input type="hidden" name="wasTun" id="wasTun">

    <header>
        <button type="button" onclick="$('#fsAnlegenVZ').slideToggle();">+ Verzeichnis anlegen</button>
    </header>
    <nav>
        <?php
            $breadcrumbnav = createBreadcrumbNav($pfad);
            echo($breadcrumbnav);
        ?>
    </nav>
    <main>
        <fieldset id="fsAnlegenVZ">
            <input type="text" name="VZNeu" id="VZNeu">
            <input type="button" value="Verzeichnis anlegen" onclick="JS_legeVZAn();">
        </fieldset>
        
        <fieldset id="fsHochladen">
            <input type="file" name="auswahlUL[]" multiple>
            <input type="submit" value="hochladen">
        </fieldset>
        
        <fieldset id="fsStruktur">
            <?php
            $struktur = zeigeVZStruktur(DATADIR);
            echo($struktur);
            ?>
            <button type="button" onclick="JS_verschiebe();">ok</button>
        </fieldset>
        <?php
        echo($msg);
        $content = zeigeVZInh($pfad);
        echo($content)
        ?>
        <button type="button" onclick="JS_delDvv();">ausgewählte Dateien und Verzeichnisse löschen</button>
        <button type="button" onclick="JS_blendeVZStrukturEin();">ausgewählte Dateien in Verzeichnisse verschieben...</button>
        <button type="button" onclick="JS_blendeVZStrukturEin();">ausgewählte Dateien in Verzeichnisse verschieben...</button>
    </main>
    <footer></footer>
</form>
</body>
</html>