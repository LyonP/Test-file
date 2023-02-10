<?php
    require("includes/common.inc.php");
    require("includes/config.inc.php");

    function leseVZ($root){
        $inhalt = scandir($root);
      
        echo('<ul>');
    
        foreach($inhalt as $dvv) {
            if($dvv!="." && $dvv!="..") {
                switch(true) {
                    case is_file($root . $dvv):
                        echo('<li class="file">' . $dvv . '</li>');
                        break;
                    case is_dir($root . $dvv):
                        echo('<li class="dir">' . $dvv);

                        leseVZ($root . $dvv . "/");

                        echo('</li>');
                        break;
                    case is_link($root . $dvv):
                        echo('<li class="link">' . $dvv . '</li>');
                        break;
                }
            }
        }
    
      echo('</ul>');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>scandir</title>
    <link rel="stylesheet" href="css/common.css">
    <style>
        .file{
            font-style: italic;
            font-weight: normal;
        }
        .dir{
            font-weight: bold;
            font-style: normal;
        }
        .link{
            color: blue;
        }
    </style>
</head>
<body>
  <?php
    $root2 = "./";
    leseVZ($root2);
 ?>

</body>
</html>