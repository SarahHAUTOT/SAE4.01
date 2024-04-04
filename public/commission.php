<?php

//Pour la structure
include 'background.php';
include '../src/app/DB.inc.php'; // si decommenter ça fait bugger

// Démarrer la session
session_start();

// Vérifier si la session est ouverte
if (!isset($_SESSION['role'])) {
    // Rediriger vers la page de connexion si la session n'est pas ouverte
    header('Location: connexion.php');
    exit;
}

// Vérifier les droits de l'utilisateur
if ($_SESSION['role'] != 2) {
    // Rediriger vers une page d'erreur si l'utilisateur n'a pas les droits nécessaires
    header('Location: accueilUtilisateur.php');
    exit;
}


function contenu()
{
	$year     = $_SESSION['anneLib'];

	var_dump($year);
	echo '
	<div>
		<h1>Commission d\'études</h1>
		<div class="container">
			<h2>Année '. $year .' / Semestre '.$_SESSION['semCom'].'</h2>
				


	';
	$tableHTML = '';

	generationCommission($_SESSION['year'], $_SESSION['semCom']);
	
	echo '
				<div class="gridRessource">
					<label id="modification">*Modification non sauvegardée*</label>
					<button class="validateButtonStyle" type="import" name="signCachet" value="">Prévisualiser</button>
					<button class="validateButtonStyle" type="import" name="signCachet" value="">Sauvegarder les modification</button>
					<a href="generation.php"><button class="validateButtonStyle" type="import" name="signCachet" value="">Générer</button></a>
				</div>
		</div>
	</div>';


}

head('css/commissionEtFicheAvis.css');

contenu();

foot();






function generationCommission($anneeId, $semestre) {

	$table = '<table class="block" id="tableCom">';

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



	$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

	// Getting all of the student for a specified year and semester
	$query     = 'SELECT distinct(e.etdId) as "etdid", etdNom as "nom", etdPrenom as "prenom", etdGroupeTP as "tp", etdGroupeTD as "td" 
				FROM  Etudiant e JOIN AdmComp  admc ON e.etdId=admc.etdId 
				JOIN  Competence c ON c.compId=admc.compId 
				WHERE anneeId = '.$anneeId.' AND semId = '.$semestre;
	$students  = $db->execQuery($query);

	foreach($students as $student)
	{
		$row = "<tr>";
		$row .= "<td>". $student['etdid'] ."</td>";
		$row .= "<td>". $student['nom'] ."</td>";
		$row .= "<td>". $student['prenom'] ."</td>";


		// Recup moyenne Semestre
        $query = "SELECT * FROM getSemMoy(".$semestre.", ".$student['etdid'].", ".$anneeId.") as \"moysem\"";
        $moy = $db->execQuery($query);
        $row .= "<td>". round($moy[0]['moysem'],2) ."</td>";

		$row .= "<td>". $student['tp'] ."</td>";
		$row .= "<td>". $student['td'] ."</td>";


		for($i = 0; $i < count($liens); $i++ )
		{
            $query = "SELECT * FROM getCompMoy(".$semestre.($i+1).", ".$student['etdid'].", ".$anneeId.") as \"moycomp\"";
            $moy = $db->execQuery($query);

			$row .= "<td>". round($moy[0]['moycomp'], 2)."</td>";
		}


		$table .= $row . "</tr> \n";
	}

	echo $table . '</table>';



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

function generationCommissionComp($table, $anneeId, $semestre, $competence)
{
    $db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");
    
    $countModule = 0;
    $idModule = [];
    $tHeadElem = "<thead></thead>";
    $tBodyElem = "<tbody></tbody>";

    $firstRow = "<tr>";
    $row = "<tr>";
    $headers = ["NIP", "Nom", "Prénom", 'Moy', "TP", "TD"];

    foreach ($headers as $headerText)
        $firstRow .= "<th rowspan='2'>".$headerText."</th>";




    $query = "SELECT modLib, modId 
    FROM CompMod cm JOIN Competence c on c.compId=cm.compId 
    JOIN Module m on m.modId=cm.modId 
    WHERE c.compId = ".$semestre.$competence;
    $modules = $db->execQuery($query);


    $query = "SELECT compLib FROM Competence WHERE c.compId = ".$semestre.$competence;
    $compLib = $db->execQuery($query);

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


    foreach ($modules as $module)
    {
        $countModule++;
        $idModule[] = $module['modid'];
    }

    $lienA = "<a href='commission.php'>{".$compLib."}</a>";
    $firstRow .= "<th colspan='".$countModule."' rowspan='1'>".$lienA."</th>";

    // foreach ($compModData as $comp) {
    //     if ($comp['compid'] == $semestre * 10 + $competence) {
    //         foreach ($comp['modules'] as $module) {
    //             $row .= "<th>{$module['modLib']}</th>";
    //         }
    //     }
    // }

    foreach ($modules as $module)
        $row .= "<th>{".$module['modlib']."}</th>";

    $tHeadElem = "<thead>".$firstRow."</thead><thead>".$row."</thead>";
    $table .= $tHeadElem;
    $table .= $tBodyElem;

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
	
    $query = 'SELECT distinct(e.etdId) as "etdid", etdNom as "nom", etdPrenom as "prenom", etdGroupeTP as "tp", etdGroupeTD as "td" 
			  FROM  Etudiant e JOIN AdmComp  admc ON e.etdId=admc.etdId 
			  JOIN  Competence c ON c.compId=admc.compId 
			  WHERE anneeId = '.$anneeId.' AND semId = '.$semestre;
	$students  = $db->execQuery($query);

	foreach($students as $student)
	{
		$row = "<tr>";
		$row .= "<td>". $student['etdid'] ."</td>";
		$row .= "<td>". $student['nom'] ."</td>";
		$row .= "<td>". $student['prenom'] ."</td>";



		// Recup moyenne Semestre
        $query = "SELECT * FROM getSemMoy(".$semestre.", ".$student['etdid'].", ".$anneeId.")";
        $moy = $db->execQuery($query);
        $row .= "<td>". $moy[0]['moysem'] ."</td>";

		$row .= "<td>". $student['tp'] ."</td>";
		$row .= "<td>". $student['td'] ."</td>";


        foreach($modules as $module)
        {
            $query = "SELECT noteVal FROM CompMod WHERE compId = ".$semestre.$competence." AND modId =".$module['noteval'];
            $noteVal = $db->execQuery($query);
            
            $row .= "<td>". $noteVal ."</td>";
        }
        

		$table .= $row . "</tr> \n";
	}
}

?>