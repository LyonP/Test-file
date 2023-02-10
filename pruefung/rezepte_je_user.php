<?php
	require("includes/default.inc.php");
	
	$vorname = '';
	$nachname = '';
	if(count($_POST)>0) {
		if(strlen(trim($_POST["vorname"]))>0) {
			$vorname = trim($_POST["vorname"]);
		}
		if(strlen(trim($_POST["nachname"]))>0) {
			$nachname = trim($_POST["nachname"]);
		}
	}
?>
<!doctype html>
<html lang="de">
<head>
	<title>Rezeptübersicht je User</title>
	<meta charset="utf-8">
</head>
<body>
	<ul>
		<li><a href="index.html">Startseite</a></li>
		<li><a href="rezeptdarstellung.php">Rezeptdarstellung</a></li>
		<li><a href="rezepte_und_zutaten.php">Zutaten und Rezepte</a></li>
	</ul>
	<h1>Rezeptübersicht je User</h1>
	<form method="post">
		<label>
			Vorname:
			<input type="text" name="vorname" value="<?php echo($vorname); ?>" >
		</label>
		<label>
			Nachname:
			<input type="text" name="nachname" value="<?php echo($nachname); ?>" >
		</label>
		<input type="submit" value="Suchen">
	</form>
	<br>
	<ul>
	<?php
		$arr = [];
		$where = "";
		if(strlen($vorname)>0) {
			$arr[] = "vorname LIKE '%"  . $vorname . "%'";
		}
		if(strlen($nachname)>0) {
			$arr[] = "nachname LIKE '%" . $nachname . "%'";
		}

		if(count($arr)>0) {
			$where = " WHERE ( " . implode(" AND ", $arr) . " ) ";
		}
		$sql = "SELECT 	 iduser
						,vorname
						,nachname
						,emailadresse
				FROM tbl_user
				". $where ."
				ORDER BY nachname ASC, vorname ASC"
		;
		$userliste = $conn->query($sql) or die("DB-Fehler: ".$conn->error."<br>".$sql);
		while($user = $userliste->fetch_object()) {
			echo("<li>".$user->vorname." ".$user->nachname." (".$user->emailadresse."):");
			$sql = "SELECT	 titel
							,COALESCE(beschreibung,'') AS beschreibung
					FROM tbl_rezepte
					WHERE fiduser = ".$user->iduser."
					ORDER BY titel ASC"
			;
			echo("<ul>");
			$rezeptliste = $conn->query($sql) or die("DB-Fehler: ".$conn->error."<br>".$sql);
			while($rezept = $rezeptliste->fetch_object()) {
				echo("<li>".$rezept->titel.": ".$rezept->beschreibung);
			}
			echo("</ul>
				  </li><br>"
			);
		}
	?>
	</ul>
</body>
</html>