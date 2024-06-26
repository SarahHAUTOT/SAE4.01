<?php

//Pour la structure
include 'background.php';
include '../src/app/export/Export.php';

// Creating global data
global $db;
$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

$query = "SELECT DISTINCT (a.anneeId), annelib
FROM Annee a JOIN AdmComp admc ON a.anneeId=admc.anneeId
WHERE CAST (compId as varchar) LIKE '5_' AND a.anneeId > 2";
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

global $annee;
$query = "SELECT anneeId, annelib FROM Annee";
$years = $db->execQuery($query);
foreach ($years as $year) 
{
    $annee[] = 
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
            if (isset($_POST['yearPE'])) {
                global $anneePE;

                $_SESSION['year'] = $_POST['yearPE'];
                $_SESSION['annelib'] = $anneePE[ $_SESSION['year'] -1 ]['annelib'];
                header("Location: generationPoursuite.php");
            }
            else
            {
                alert("Veuillez selectionner une année");
            }
            break;

        case 'comm':

            if (isset($_POST['yearCom']))
            {
                if (isset($_POST['semCom']))
                {
                    $query = "SELECT DISTINCT(semId)
                            FROM Competence c JOIN CompMod cm ON c.compId = cm.compId JOIN Moyenne m ON m.modId = cm.modId WHERE anneeid = ". $_POST['yearCom'] . " AND semId = " . $_POST['semCom'];
                    $sems = $db->execQuery($query);

                    if (count($sems) == 0)
                    {
                        alert("Pas de donnée");
                    }
                    else
                    {
                        global $annee;

                    $_SESSION['year'    ] = $_POST['yearCom'];
                    $_SESSION['annelib' ] = $annee[ $_POST['yearCom'] -1]['annelib'];
                    $_SESSION['semCom'  ] = $_POST['semCom'];

                        // if ($_SESSION['semCom'  ] >= 2) generateCSV(intval($_POST['yearCom']), 'Commission', intval($_SESSION['semCom']));
                        header("Location: commission.php");

                    }
                }
                else
                {
                    alert("Veuillez selectionner un semestre");
                }
            }
            else
            {
                alert("Veuillez selectionner une année");
            }
            break;



        case 'jury':

            if (isset($_POST['yearCom'])) {

                if (isset($_POST['semCom']))
                {
                    // TODO
                }
                else
                {
                    alert("Veuillez selectionner un semestre");
                }
            }
            else
            {
                alert("Veuillez selectionner une année");
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
    global $semestres;
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

    $query = "SELECT anneeId, annelib FROM Annee";
    $anneeData = $db->execQuery($query);

	foreach ($anneeData as $year)
        echo '<option value="'.$year['anneeid'].'">'. $year['annelib'] .'</option>';

	echo    '</select>

			<span>Choix semestre</span>
			<select id="selectSemester" name="semCom">';
	
    for ($i = 1; $i<=5 ;$i++)
        echo '<option value="'.$i.'">S'.$i.'</option>';

	echo '</select>
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