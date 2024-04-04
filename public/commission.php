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


function contenu()
{
	$year     = unserialize($_SESSION['year']);
	echo '
	<div>
		<h1>Commission d\'études</h1>
		<div class="container">
			<h2>Année '. $year['annelib'] .' / Semestre '.intval($_POST['semCom']).'</h2>
				<table class="block" id="tableCom">
				</table>
				<div class="gridRessource">
					<label id="modification">*Modification non sauvegardée*</label>
					<button class="validateButtonStyle" type="import" name="signCachet" value="">Prévisualiser</button>
					<button class="validateButtonStyle" type="import" name="signCachet" value="">Sauvegarder les modification</button>
					<a href="generation.php"><button class="validateButtonStyle" type="import" name="signCachet" value="">Générer</button></a>
				</div>
		</div>
	</div>
	<script src = "js/commission.js"></script>
	<script>
		window.addEventListener("load", (event) =>{
			const table = document.getElementById("tableCom")
			generationCommission(table,'.json_encode($year['annelib']).',1)
		});
	</script>';
}

head('css/commissionEtFicheAvis.css');

contenu();

foot();

?>