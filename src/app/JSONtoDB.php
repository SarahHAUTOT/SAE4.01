<?php

// Connexion à la base de données
$dsn = 'pgsql:host=127.0.0.1;port=5432;dbname=hs220880';
$username = 'hs220880';
$password = 'SAHAU2004';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des données JSON depuis le fichier
$jsonData = file_get_contents('donnees.json');

// Conversion des données JSON en tableau associatif
$data = json_decode($jsonData, true);


foreach ($data as $anneeData) 
    foreach ($anneeData['semesters'] as $semestre) 
		foreach ($semestre['etd'] as $student) 
			{
				foreach ($student['competences'] as $comp)
				{
					$stmt = $pdo->prepare("UPDATE AdmComp SET admi = ? WHERE etdId = ? AND compId = ? AND anneeId = ?");
    			    $stmt->execute([$comp['admi'], $student['etdid'],$comp['compId'], $anneeData['anneeid']]);
				}

				foreach ($student['modules'] as $mod)
				{
					$stmt = $pdo->prepare("UPDATE Moyenne SET noteVal = ? WHERE etdId = ? AND modId = ? AND anneeId = ?");
    			    $stmt->execute([$mod['noteVal'], $student['etdid'],$mod['modId'], $anneeData['anneeid']]);
				}

			}


echo 'Hey, ca fonctionne !';




?>