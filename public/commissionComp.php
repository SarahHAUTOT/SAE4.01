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
    $year       = $_SESSION['anneLib'];
    $competence = $_GET['sem'];
    echo '
	<div>
		<h1>Commission d\'études</h1>
		<div class="container">
		<h2>Année ' . $year . ' / Semestre ' . $_SESSION['semCom'] . '</h2>';


    $tableHTML = '';

    generationCommissionComp($_SESSION['year'], $_SESSION['semCom'], $competence);


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



function generationCommissionComp($anneeId, $semestre, $competence)
{

    $table = '<table class="block" id="tableCom">';


    $db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

    $countModule = 0;
    $idModule = [];
    $tHeadElem = "<thead></thead>";
    $tBodyElem = "<tbody></tbody>";

    $firstRow = "<tr>";
    $row = "<tr>";
    $headers = ["NIP", "Nom", "Prénom", 'Moy', "TP", "TD"];

    foreach ($headers as $headerText)
        $firstRow .= "<th rowspan='2'>" . $headerText . "</th>";




    $query = "SELECT modLib, m.modId FROM CompMod cm JOIN Competence c on c.compId=cm.compId JOIN Module m ON m.modId = cm.modId 
    WHERE c.compId = " . $semestre . $competence;
    $modules = $db->execQuery($query);


    $query = "SELECT compLib FROM Competence WHERE compId = " . $semestre . $competence;
    $compLib = $db->execQuery($query);
    $compLib = $compLib[0]['complib'];

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


    $countModule = 0;
    foreach ($modules as $module) {
        $countModule++;
        $idModule[] = $module['modid'];
    }

    $lienA = "<a href='commission.php'>" . $compLib . "</a>";
    $firstRow .= "<th colspan='" . $countModule . "' rowspan='1'>" . $lienA . "</th>";

    // foreach ($compModData as $comp) {
    //     if ($comp['compid'] == $semestre * 10 + $competence) {
    //         foreach ($comp['modules'] as $module) {
    //             $row .= "<th>{$module['modLib']}</th>";
    //         }
    //     }
    // }

    foreach ($modules as $module)
        $row .= "<th>" . $module['modlib'] . "</th>";

    $tHeadElem = "<thead>" . $firstRow . "" . $row . "</thead>";
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
			  WHERE anneeId = ' . $anneeId . ' AND semId = ' . $semestre;
    $students  = $db->execQuery($query);

    foreach ($students as $student) {
        $row = "<tr>";
        $row .= "<td>" . $student['etdid'] . "</td>";
        $row .= "<td>" . $student['nom'] . "</td>";
        $row .= "<td>" . $student['prenom'] . "</td>";



        // Recup moyenne Semestre
        $query = "SELECT * FROM getSemMoy(" . $semestre . ", " . $student['etdid'] . ", " . $anneeId . ")";
        $moy = $db->execQuery($query);

        $row .= "<td>" . round($moy[0]['getsemmoy'], 2) . "</td>";

        $row .= "<td>" . $student['tp'] . "</td>";
        $row .= "<td>" . $student['td'] . "</td>";


        foreach ($modules as $module) {

            $query = "SELECT noteVal FROM Moyenne m JOIN CompMod cm ON m.modid = cm.modid WHERE compId = " . $semestre . $competence . " AND cm.modId = '" . $module['modid'] . "'";
            $noteVal = $db->execQuery($query);

            $row .= "<td>" . $noteVal[0]['noteval'] . "</td>";
        }


        $table .= $row . "</tr> \n";
    }
    echo $table . '</table>';
}

head('css/commissionEtFicheAvis.css');

contenu();

foot();
