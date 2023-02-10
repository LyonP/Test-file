<?php
	require("includes/default.inc.php");
?>
<!doctype html>
<html lang="de">
<head>
	<title>Rezeptdarstellung</title>
	<meta charset="utf-8">
</head>
<body>
	<ul>
		<li><a href="index.html">Startseite</a></li>
		<li><a href="rezepte_je_user.php">Rezeptübersicht je User</a></li>
		<li><a href="rezepte_und_zutaten.php">Zutaten und Rezepte</a></li>
	</ul>
	<h1>Rezeptdarstellung</h1>
	<ul>
	<?php
		$sql = "SELECT	 tbl_rezepte.idrezept
						,tbl_rezepte.titel
						,tbl_user.vorname
						,tbl_user.nachname
						,tbl_rezepte.beschreibung
						,tbl_rezepte.dauervorbereitung
						,tbl_rezepte.dauerzubereitung
						,tbl_rezepte.dauerruhen
						,tbl_rezepte.anzahlpersonen
						,COALESCE(tbl_schwierigkeitsgrade.titel,'n/a') AS gradtitel
						,COALESCE(tbl_schwierigkeitsgrade.beschreibung,'n/a') AS gradbeschreibung
				FROM tbl_rezepte 
				INNER JOIN tbl_user ON tbl_user.iduser = tbl_rezepte.fiduser
				LEFT OUTER JOIN tbl_schwierigkeitsgrade ON tbl_schwierigkeitsgrade.idschwierigkeitsgrad = tbl_rezepte.fidschwierigkeitsgrad
				ORDER BY tbl_rezepte.titel ASC"
		;
		$rezeptliste = $conn->query($sql) or die("DB-Fehler: ".$conn->error."<br>".$sql);
		while($rezept = $rezeptliste->fetch_object()) {
			echo("<li> 
					<h2>".$rezept->titel."</h2>
					(von ".$rezept->vorname." ".$rezept->nachname.")
					<p>".$rezept->beschreibung."</p>
					Zeiten:
					<ul><li>Vorbereitungszeit ~ ".$rezept->dauervorbereitung." min</li>
						<li>Zubereitungszeit ~ ".$rezept->dauerzubereitung." min</li>
						<li>Ruhezeit ~ ".$rezept->dauerruhen." min</li>
					</ul>
					<br>Für ".$rezept->anzahlpersonen." Personen
					<br><br>Schwierigkeitsgrad: ".$rezept->gradtitel." - ".$rezept->gradbeschreibung
			);
			// List-Item wird erst vor dem Ende der Schleife geschlossen
			$sql = "SELECT	 tbl_rezepte_zutaten.anzahl
							,COALESCE(tbl_einheiten.bezeichnung,'') AS einheit
							,tbl_zutaten.bezeichnung
					FROM tbl_rezepte_zutaten
					INNER JOIN tbl_zutaten ON tbl_zutaten.idzutat = tbl_rezepte_zutaten.fidzutat
					LEFT OUTER JOIN tbl_einheiten ON tbl_einheiten.ideinheit = tbl_rezepte_zutaten.fideinheit
					WHERE tbl_rezepte_zutaten.fidrezept = ".$rezept->idrezept."
					ORDER BY tbl_zutaten.bezeichnung ASC"
			;
			$zutatenliste = $conn->query($sql) or die("DB-Fehler: ".$conn->error."<br>".$sql);
			echo("<h3>Zutaten:</h3>
				  <ul>"
			);
			while($zutat = $zutatenliste->fetch_object()) {
				echo("<li>
						".$zutat->anzahl." ".$zutat->einheit." ".$zutat->bezeichnung."
					 </li>"
				);
			}
			echo("</ul>");
			$sql = "SELECT beschreibung	
					FROM tbl_zubereitungsschritte
					WHERE fidrezept = ".$rezept->idrezept."
					ORDER BY reihenfolge"
			;
			$zubereitungsschritte = $conn->query($sql) or die("DB-Fehler: ".$conn->error."<br>".$sql);
			echo("<h3>Zubereitungsschritte:</h3>
				  <ol>"
			);
			while($schritt = $zubereitungsschritte->fetch_object()) {
				echo("<li>
						".$schritt->beschreibung."
					 </li>"
				);
			}
			echo("</ol>
				  </li><br><br>"
			);
		}
	?>
	</ul>
</body>
</html>