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


function contenue()
{
	echo '
	<div>
		<h1>Génération avis de poursuite d\'études</h1>
		<div class="container">
			<h2>%%ETUDIANT SPECIFIQUE%%</h1>
				<table class="block">
					<thead>
						<tr>
							<th>NIP</th>
							<th>nom</th>
							<th>moy</th>
							<th>TP </th>
							<th>TD </th>
							<th><a href="commissionComp.html">C1</a></th>
							<th><a href="commissionComp.html">C2</a></th>
							<th><a href="commissionComp.html">C3</a></th>
							<th><a href="commissionComp.html">C4</a></th>
							<th><a href="commissionComp.html">C5</a></th>
							<th><a href="commissionComp.html">C6</a></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>

						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>

						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>

						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>

				<div class="gridRessource">
					<label id="modification">*Modification non sauvegardée*</label>
					<button class="validateButtonStyle" type="import" name="signCachet"
						value="">Prévisualiser</button>
					<button class="validateButtonStyle" type="import" name="signCachet" value="">Sauvegarder les
						modification</button>
					<button class="validateButtonStyle" type="import" name="signCachet" value="">Générer</button>
				</div>
		</div>
	</div>';
}

head('css/commissionEtFicheAvis.css');

contenue();

foot();





?>