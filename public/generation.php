<?php

//Pour la structure
include 'background.php';
include '../src/app/export/Export.php';



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


function findYear($year)
{
    // Récupération des données JSON depuis le fichier
	$jsonData = file_get_contents('../data/donnees.json');
	$data = json_decode($jsonData, true);

    $serializedObject = serialize($data[$year - 1]);
    $_SESSION['year'] = $serializedObject;
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action)
    {
        case 'pe':
            //génération des poursuite d'études
            if (isset($_POST['yearPE']) && !empty($_POST['yearPE'])) {
                findYear($_POST['yearPE']);

                header("Location: generationPoursuite.php");
            }
            else
            {
                alert("Veuillez selectionner une année");
            }
            break;

        case 'comm':

            if (isset($_POST['yearCom']) && !empty($_POST['yearCom'])) {

                if (isset($_POST['semCom']) && !empty($_POST['semCom']))
                {
                    findYear($_POST['yearPE']);
                    $_SESSION['semCom'] = $_POST['semCom'];

                    // header("Location: commission.php");
                    generateCSV(intval($_POST['yearCom']), 'Commission', intval($_POST['semCom']));
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

            if (isset($_POST['yearCom']) && !empty($_POST['yearCom'])) {

                if (isset($_POST['semCom']) && !empty($_POST['semCom']))
                {
                    findYear($_POST['yearPE']);

                    // header("Location: commission.php");
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
	$jsonData = file_get_contents('../data/donnees.json');
	$data = json_decode($jsonData, true);



	echo '
	<h1> Génération </h1>
	<div class="container">
	<form action="generation.php" method="post">
		<div class="generationSection">
			<h2>Avis de poursuite d\'études</h2>
			<div class="gridLibImport">

				<span>Choix Année</span>
				<select id="selectYear" name="yearPE" ">';
	
	foreach ($data as $anneeData) 
		// if ($anneeData['semesters'][4] != null && count($anneeData['semesters'][4]['etd']) > 0) // We check if the fifth semester exist  
            echo '<option value="'.$anneeData['anneeid'].'">'. $anneeData['annelib'] .'</option>';


		echo '</select>
		</div>
        	<button  type="submit" name="action" value="pe" class="validateButtonStyle">Continuer vers export d\'avis de poursuite d\'étude</button>
		</div>

		<div class="generationSection">
			<h2>Préparation aux commissions/jurys</h2>
			<div class="gridLibImport" >
				<span>Choix Année</span>
				<select id="selectYear" name="yearCom" >';

	
	foreach ($data as $anneeData)
        echo '<option value="'.$anneeData['anneeid'].'">'. $anneeData['annelib'] .'</option>';

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