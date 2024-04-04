<?php

include '../../src/app/DB.inc.php'; // si decommenter ça fait bugger



$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");


function generationCommission($table, $annee, $semestre) {
    // Transformation des balises HTML et du code DOM
    while ($table->hasChildNodes()) {
        $table->removeChild($table->firstChild);
    }

    $tHeadElem = "<thead></thead>";
    $tBodyElem = "<tbody></tbody>";

    $firstRow = "<tr>";
    $headers = ["NIP", "Nom", "Prénom", 'Moy', "TP", "TD"];
    $liens   = ["C1", "C2"];

    if ($semestre != 5) {
        $liens[] = "C3";
        $liens[] = "C4";
        $liens[] = "C5";
    }
    $liens[] = "C6";

    foreach ($headers as $headerText) {
        $firstRow .= "<th>$headerText</th>";
    }

    foreach ($liens as $lienLib) {
        $semNumber = array_search($lienLib, $liens) + 1;
        $lienA = "<a href='commissionComp.php?sem=$semNumber'>$lienLib</a>";
        $firstRow .= "<th>$lienA</th>";
    }

    $tHeadElem = "<thead>$firstRow</thead>";
    $table .= $tHeadElem;
    $table .= $tBodyElem;


	global $db;

	// Getting all of the student for a specified year and semester
	$query     = 'SELECT e.etdId as "etdid", etdNom as "nom", etdPrenom as "prenom", etdGroupeTP as "tp", etdGroupeTD as "td" 
				FROM  Etudiant e JOIN AdmComp  admc ON e.etdId=admc.etdId 
				JOIN  Competence c ON c.compId=admc.compId 
				WHERE anneeId = '.$annee.' AND semId = '.$semestre;
	$students  = $db->execQuery($query);

	foreach($students as $student)
	{
		$row = "<tr>";
		$row .= "<td>". $student['etdid'] ."</td>";
		$row .= "<td>". $student['nom'] ."</td>";
		$row .= "<td>". $student['prenom'] ."</td>";



		//Recup moyenne 
		// $query     = 'SELECT e.etdId as "etdid", etdNom as "nom", etdPrenom as "prenom", etdGroupeTP as "tp", etdGroupeTD as "td" 
		// 		FROM  Etudiant e JOIN AdmComp  admc ON e.etdId=admc.etdId 
		// 		JOIN  Competence c ON c.compId=admc.compId 
		// 		WHERE anneeId = '.$annee.' AND semId = '.$semestre;
		// $students  = $db->execQuery($query);


		$row .= "<td>Moyenne</td>";

		$row .= "<td>". $student['tp'] ."</td>";
		$row .= "<td>". $student['td'] ."</td>";


		for($i = 0; $i < count($liens); $i++ )
		{
			$row .= "<td>C".($i+1)."</td>";
		}

		$table .= "$row</tr>";
	}





    // // Gestion des requêtes HTTP et des réponses
    // $jsonData = file_get_contents('http://localhost/SAE4.01/data/donnees.json');
    // $jsonData = json_decode($jsonData, true);

    // // Traitement des données
    // foreach ($jsonData as $anneeData) {
    //     if ($anneeData['annelib'] === $annee) {
    //         foreach ($anneeData['semesters'] as $semesterData) {
    //             if ($semesterData['semid'] === $semestre) {
    //                 foreach ($semesterData['etd'] as $etudiantData) {
    //                     $row = "<tr>";

    //                     foreach (['etdid', 'etdnom', 'etdprenom'] as $key) {
    //                         $row .= "<td>{$etudiantData[$key]}</td>";
    //                     }

    //                     $row .= "<td>Moyenne</td>";

    //                     foreach (['etdgroupetp', 'etdgroupetd'] as $key) {
    //                         $row .= "<td>{$etudiantData[$key]}</td>";
    //                     }

    //                     foreach ($etudiantData['competences'] as $comp) {
    //                         $moyenneFloat = floatval($comp['moySem']);
    //                         $formattedMoyenne = number_format($moyenneFloat, 2);
    //                         $row .= "<td>$formattedMoyenne</td>";
    //                     }

    //                     $table .= "$row</tr>";
    //                 }
    //             }
    //         }
    //     }
    // }
}

function generationCommissionComp($table, $annee, $semestre, $competence) {
    // Transformation des balises HTML et du code DOM
    while ($table->hasChildNodes()) {
        $table->removeChild($table->firstChild);
    }

    $countModule = 0;
    $idModule = [];
    $tHeadElem = "<thead></thead>";
    $tBodyElem = "<tbody></tbody>";

    $firstRow = "<tr>";
    $row = "<tr>";
    $headers = ["NIP", "Nom", "Prénom", 'Moy', "TP", "TD"];

    foreach ($headers as $headerText) {
        $firstRow .= "<th rowspan='2'>$headerText</th>";
    }







    // $compModData = file_get_contents('http://localhost/SAE4.01/data/compMod.json');
    // $compModData = json_decode($compModData, true);

    // foreach ($compModData as $bin) {
    //     if ($bin['compid'] == $semestre * 10 + $competence) {
    //         foreach ($bin['modules'] as $module) {
    //             $countModule++;
    //             $idModule[] = $module['modId'];
    //         }

    //         $lienA = "<a href='commission.php'>{$bin['complib']}</a>";
    //         $firstRow .= "<th colspan='$countModule' rowspan='1'>$lienA</th>";
    //     }
    // }

    // foreach ($compModData as $comp) {
    //     if ($comp['compid'] == $semestre * 10 + $competence) {
    //         foreach ($comp['modules'] as $module) {
    //             $row .= "<th>{$module['modLib']}</th>";
    //         }
    //     }
    // }

    // $tHeadElem = "<thead>$firstRow</thead><thead>$row</thead>";
    // $table .= $tHeadElem;
    // $table .= $tBodyElem;

    // // Gestion des requêtes HTTP et des réponses
    // $jsonData = file_get_contents('http://localhost/SAE4.01/data/donnees.json');
    // $jsonData = json_decode($jsonData, true);

    // // Traitement des données
    // foreach ($jsonData as $anneeData) {
    //     if ($anneeData['annelib'] === $annee) {
    //         foreach ($anneeData['semesters'] as $semesterData) {
    //             if ($semesterData['semid'] === $semestre) {
    //                 foreach ($semesterData['etd'] as $etudiantData) {
    //                     $row = "<tr>";

    //                     foreach (['etdid', 'etdnom', 'etdprenom'] as $key) {
    //                         $row .= "<td>{$etudiantData[$key]}</td>";
    //                     }

    //                     $row .= "<td>Moyenne</td>";

    //                     foreach (['etdgroupetp', 'etdgroupetd'] as $key) {
    //                         $row .= "<td>{$etudiantData[$key]}</td>";
    //                     }

    //                     foreach ($etudiantData['modules'] as $module) {
    //                         if (in_array($module['modId'], $idModule)) {
    //                             $row .= "<td>{$module['noteVal']}</td>";
    //                         }
    //                     }

    //                     $table .= "$row</tr>";
    //                 }
    //             }
    //         }
    //     }
    // }
}
?>
