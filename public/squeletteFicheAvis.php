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
			<table>
				<thead>
					<tr>
					  <th>Apprentissage</th>
					  <th>BUT 1</th>
					  <th>BUT 2</th>
					  <th>BUT 3</th>
					</tr>
				  </thead>
				  <tbody>
					<tr>
						<th>Parcours d\'études</th>
						<td>N-2</td>
						<td>N-1</td>
						<td>N</td>
					</tr>
					<tr>
						<th>Parcours BUT</th>
						<td colspan="3">A "Réalisation d\'application: conception...</td>
					</tr>
					<tr>
						<th>Si mobilité à létranger (lieu,durée)</th>
						<td colspan="3"></td>
					</tr>
				  </tbody>
				</table>
				<hr>
				<h3>Résultat des compétences</h3>
				<table>
					<thead>
						<tr>
							<th rowspan="2"></th>
							<th colspan="2">BUT 1</th>
							<th colspan="2">BUT 2</th>
						</tr>
						<tr>
							<th>Rang</th>
							<th>Moy.</th>
							<th>Rang</th>
							<th>Moy.</th>
						</tr>
					  </thead>
					  <tbody>
						<tr>
							<th>UE1-Réaliser des applications</th>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>UE2-Optimiser des applications</th>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>UE3-Administrer des systèmes</th>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>UE4-Gérer des données</th>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>UE5-Conduire des projets</th>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>UE5-Conduire des projets</th>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>UE6-Collaborer</th>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>Maths</th>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>Anglais</th>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>Nombre d\'absences injustifiées</th>
							<td colspan="2"></td>
							<td colspan="2"></td>
						</tr>
					</tbody>
				</table>
				<table>
					<thead>
						<tr>
							<th rowspan="2"></th>
							<th colspan="2">BUT 3</th>
						</tr>
						<tr>
							<th>Rang</th>
							<th>Moy.</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<th>UE1-Réaliser des applications</th>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>UE2-Optimiser des applications</th>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>UE6-Collaborer</th>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>Maths</th>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>Anglais</th>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<th>Nombre d\'absences injustifiées</th>
							<td colspan="2"></td>
						</tr>
					</tbody>
				</table>
				<hr>
				<h3>Avis de l’équipe pédagogique pour la poursuite d’études après le BUT 3</h3>
				<table>
					<thead>
						<tr>
							<th colspan="2"></th>
							<th>Très Favorable</th>
							<th>Favorable</th>
							<th>Assez Favorable</th>
							<th>Sans Avis</th>
							<th>Réservé</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<th rowspan="2">Pour l\'étudiant</th>
							<th>En école d\'ingénieurs</th>
							<td><input type="radio" id="tresFavorableIngé" name="avisInge"></td>
							<td><input type="radio" id="favorable"         name="avisInge"></td>
							<td><input type="radio" id="assezFavorable"    name="avisInge"></td>
							<td><input type="radio" id="sansAvis"          name="avisInge"></td>
							<td><input type="radio" id="reserve"           name="avisInge"></td>
						</tr>
						<tr>
							<th>En Master</th>
							<td><input type="radio" id="tresFavorableIngé" name="avisMaster"></td>
							<td><input type="radio" id="favorable"         name="avisMaster"></td>
							<td><input type="radio" id="assezFavorable"    name="avisMaster"></td>
							<td><input type="radio" id="sansAvis"          name="avisMaster"></td>
							<td><input type="radio" id="reserve"           name="avisMaster"></td>
						</tr>
						<tr>
							<th>Commentaire</th>
							<td colspan="6"><textarea></textarea></td>
						</tr>
					</tbody>
				</table>
			<div class="gridRessource">
				<label id="modification">*Modification non sauvegardée*</label>
				<button class="validateButtonStyle" type="import" name="signCachet" value="">Prévisualiser</button>
				<button class="validateButtonStyle" type="import" name="signCachet" value="">Sauvegarder les modification</button>
				<button class="validateButtonStyle" type="import" name="signCachet" value="">Générer</button>
			</div>
		</div>
	</div>';
}

head('css/generation.css');

contenue();

foot();





?>