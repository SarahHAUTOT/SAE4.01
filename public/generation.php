<?php

//Pour la structure
include 'background.php';



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

    if (!isset($_SESSION['year'])) 
    {
        alert("Veuillez sélectionner une année");
    }
    else
    {
        echo $_SESSION["year"] ." ". $_SESSION["semester"];
        switch ($action)
        {
            case 'pe':
                //générationd des poursuite d'études
                header("Location: generationPoursuite.php");
                break;
            case 'jury':
                if (!isset($_SESSION['semester'])) 
                {
                    alert("Veuillez sélectionner une semestre");
                }
                break;

            case 'comm':
                //Génération de commissions
                break;
            default:
                echo "Action non reconnue";
                break;
        }
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
				<select id="selectYear" onchange"saveSelectedYear()">';
	
    $i = 1;
	foreach ($data as $anneeData) 
		if ($anneeData['semesters'][4] != null && count($anneeData['semesters'][4]['etd']) > 0) // We check if the fifth semester exist  
		{
            echo '<option value="annee'.$i.'">'. $anneeData['annelib'] .'</option>';
            $i++;
        }


		echo '</select>
		</div>
        	<button  type="submit" name="action" value="pe" class="validateButtonStyle">Continuer vers export d\'avis de poursuite d\'étude</button>
		</div>

		<div class="generationSection">
			<h2>Préparation aux commissions/jurys</h2>
			<div class="gridLibImport" >
				<span>Choix Année</span>
				<select id="selectYear" onchange"saveSelectedYear()">';

	
	$i = 1;		
	foreach ($data as $anneeData)
    {
        echo'	<option value="annee'.$i.'">'. $anneeData['annelib'] .'</option>';
        $i++;
    }

	echo    '</select>

			<span>Choix semestre</span>
			<select id="selectSemester" onchange="saveSelectedSemester()">
				<option value="semestre1">S1</option>
				<option value="semestre2">S2</option>
				<option value="semestre3">S3</option>
				<option value="semestre4">S4</option>
				<option value="semestre4">S5</option>
			</select>
		</div>
			
		<button type="submit" name="action" value="jury" class="validateButtonStyle">Générer Jury</button>
		<button type="submit" name="action" value="comm" class="validateButtonStyle">Générer Commission</button>
	</div>
	</form>
	</div>';
}

head('css/generation.css');
echo '<script src="js/selectYear.js"></script>';
echo '<script src="js/selectSemester.js"></script>';
contenu();
foot();

?>