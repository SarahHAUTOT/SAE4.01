<?php

//Pour la structure
include 'background.php';
include '../src/app/export/Export.php';
include '../src/app/DB.inc.php';

// Creating global data
global $db;
$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

$query = "SELECT a.anneeId, anneLib
FROM Annee a JOIN AdmComp admc ON a.anneeId=admc.anneeId
WHERE CAST (compId as varchar) LIKE '5_'";
$years = $db->execQuery($query);

global $anneePE;

foreach ($years as $year) 
{
    $anneePE[] = 
        [
            'anneeid' => $year['anneeid'],
            'annelib' => $year['annelib'],  
        ];
}


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

function alert($message) 
{
    echo "<script>alert('$message');</script>";
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action)
    {
        case 'pe':
            //générationd des poursuite d'études
            if (isset($_POST['yearPE']) && isset($_POST['yearPE'])) {

                $_SESSION['year'] = $_POST['yearPE'];
                generateStudents($_SESSION['year']);
                header("Location: generationPoursuite.php");
            }
            else
            {
                alert("Veuillez selectionné une année");
            }
            break;

        case 'comm':

            if (isset($_POST['yearCom']))
            {

                if (isset($_POST['semCom']))
                {
                    global $anneePE;

                    // header("Location: commission.php");
                    $_SESSION['year'    ] = $_POST['yearPE'];
                    $_SESSION['anneeLib'] = $anneePE[ $_POST['yearPE'] ];
                    $_SESSION['semCom'  ] = $_POST['semCom'];

                    generateCSV(intval($_POST['yearCom']), 'Commission', intval($_SESSION['semCom'] +1));
                }
                else
                {
                    alert("Veuillez selectionnée un semestre");
                }
            }
            else
            {
                alert("Veuillez selectionné une année");
            }
            break;



        case 'jury':

            if (isset($_POST['yearCom']) && !empty($_POST['yearCom'])) {

                if (isset($_POST['semCom']) && !empty($_POST['semCom']))
                {
                    $_SESSION['year'  ] = $_POST['yearPE'];
                    $_SESSION['semCom'] = $_POST['semCom'];
                    // header("Location: commission.php");
                }
                else
                {
                    alert("Veuillez selectionnée un semestre");
                }
            }
            else
            {
                alert("Veuillez selectionné une année");
            }
            break;



        default:
            echo "Action non reconnue";
            break;
    }
}


function contenu()
{
    global $anneePE;
    global $db;

	echo '
	<h1> Génération </h1>
	<div class="container">
	<form action="generation.php" method="post">
		<div class="generationSection">
			<h2>Avis de poursuite d\'études</h2>
			<div class="gridLibImport">

				<span>Choix Année</span>
				<select id="selectYear" name="yearPE" ">';
	
	foreach ($anneePE as $year) 
        echo '<option value="'.$year['anneeid'].'">'. $year['annelib'] .'</option>';


		echo '</select>
		</div>
        	<button  type="submit" name="action" value="pe" class="validateButtonStyle">Continuer vers export d\'avis de poursuite d\'étude</button>
		</div>

		<div class="generationSection">
			<h2>Préparation aux commissions/jurys</h2>
			<div class="gridLibImport" >
				<span>Choix Année</span>
				<select id="selectYear" name="yearCom" >';

    $query = "SELECT anneeId, anneLib FROM Annee";
    $anneeData = $db->execQuery($query);

	foreach ($anneeData as $year)
        echo '<option value="'.$year['anneeid'].'">'. $year['annelib'] .'</option>';

	echo    '</select>

			<span>Choix semestre</span>
			<select id="selectSemester" name="semCom">
				<option value="1">S1</option>
				<option value="2">S2</option>
				<option value="3">S3</option>
				<option value="4">S4</option>
				<option value="5">S5</option>
			</select>
		</div>
			
		<button type="submit" name="action" value="jury" class="validateButtonStyle">Générer Jury</button>
		<button type="submit" name="action" value="comm" class="validateButtonStyle">Générer Commission</button>
	</div>
	</form>
	</div>';
}

head('css/generation.css');
contenu();
echo '<script src="js/selectYear.js"></script>';
echo '<script src="js/selectSemester.js"></script>';
foot();

?>