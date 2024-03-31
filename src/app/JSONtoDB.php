<?php
require 'DB.inc.php';

decodeDonnee();


function decodeDonnee()
{
	// Récupération des données JSON depuis le fichier
	$jsonData = file_get_contents('../../data/donnees.json');

	// Conversion des données JSON en tableau associatif
	$data = json_decode($jsonData, true);


    $db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

	foreach ($data as $anneeData) 
		foreach ($anneeData['semesters'] as $semestre) 
			foreach ($semestre['etd'] as $student) 
				{
					foreach ($student['competences'] as $comp)
					{
						$sql = "UPDATE AdmComp SET admi = ? WHERE etdId = ? AND compId = ? AND anneeId = ?";
						$db->execMaj($sql, [$comp['admi'], $student['etdid'],$comp['compId'], $anneeData['anneeid']]);
					}

					foreach ($student['modules'] as $mod)
					{
						$sql = "UPDATE Moyenne SET noteVal = ? WHERE etdId = ? AND modId = ? AND anneeId = ?";
						$db->execMaj($sql, [$mod['noteVal'], $student['etdid'],$mod['modId'], $anneeData['anneeid']]);
					}

				}
}




?>