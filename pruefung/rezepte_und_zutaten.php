<?php
	require("includes/default.inc.php");
	
	$sql = "SELECT idzutat, bezeichnung
			FROM tbl_zutaten
			ORDER BY bezeichnung ASC"
	;
	$zutatenliste = $conn->query($sql) or die("DB-Fehler: ".$conn->error."<br>".$sql);
	
	$where = "";
	$zutat_selected = FALSE;
	if(count($_GET)>0) {
		if($_GET["zutat"]>0) {
			$where = "WHERE tbl_rezepte_zutaten.fidzutat = ".$conn->real_escape_string($_GET["zutat"]);
			$zutat_selected = TRUE;
		}
	}
	$sql = "SELECT	 tbl_rezepte.titel
					,tbl_user.vorname
					,tbl_user.nachname
					,tbl_rezepte.anzahlpersonen
					,tbl_rezepte.beschreibung
			FROM tbl_rezepte
			INNER JOIN tbl_rezepte_zutaten ON tbl_rezepte.idrezept = tbl_rezepte_zutaten.fidrezept
			INNER JOIN tbl_user	ON tbl_user.iduser = tbl_rezepte.fiduser
			".$where."
			GROUP BY tbl_rezepte.idrezept
			ORDER BY tbl_rezepte.titel ASC"
	;
	$rezeptliste = $conn->query($sql) or die("DB-Fehler: ".$conn->error."<br>".$sql);
?>
<!doctype html>
<html lang="de">
<head>
	<title>Zutaten und Rezepte</title>
	<meta charset="utf-8">
</head>
<body>
	<ul>
		<li><a href="index.html">Startseite</a></li>
		<li><a href="rezeptdarstellung.php">Rezeptdarstellung</a></li>
		<li><a href="rezepte_je_user.php">Rezeptübersicht je User</a></li>
	</ul>
	<h1>Zutaten und Rezepte</h1>
	<form method="get">
		<label>
			Zutat:
			<select name="zutat">
				<option value="0">-- bitte wählen --</option>
				<?php
					while($zutat = $zutatenliste->fetch_object()) {
						$selected = "";
						if($zutat_selected) {
							if($zutat->idzutat == $_GET["zutat"]) {
								$selected = "selected";
							}
						}
						echo('<option value="'.$zutat->idzutat.'" '.$selected.'>'.$zutat->bezeichnung.'</option>');
					}
				?>
			</select>
		</label>
		<input type="submit" value="Suchen">
	</form>
	<ul>
	<?php
		while($rezept = $rezeptliste->fetch_object()) {
			echo("<li>".$rezept->titel." (von ".$rezept->vorname." ".$rezept->nachname.", für ".$rezept->anzahlpersonen." Personen):
				  <br>".$rezept->beschreibung."
				  </li><br>"
			);
		}
	?>
	</ul>
</body>
</html>