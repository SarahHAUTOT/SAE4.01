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
	echo '
	<div>
		<h1>Commission d\'études</h1>
		<div class="container">
			<h2 id="anneeSemestre">%%ETUDIANT SPECIFIQUE%%</h1>
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
			const titre2 = document.getElementById("anneeSemestre")
			titre2.textContent = "Annee 2021-2022 / Semestre 1"
			const table = document.getElementById("tableCom")
			generationCommission(table,"année 2020",1)
		});
	</script>';
}

head('css/commissionEtFicheAvis.css');

contenu();

foot();





?>