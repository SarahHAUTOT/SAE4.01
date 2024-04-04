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

// Vérifier si on a appuyer sur le btn suivant
if (isset($_POST['idStudent'])) {
	// Récupération des données JSON depuis le fichier
	$jsonData = file_get_contents('../../data/pe.json');
	$students = json_decode($jsonData, true);

	contenu($students[ $_POST['idStudent'] ]);
}
else
{
	// Récupération des données JSON depuis le fichier
	$jsonData = file_get_contents('../../data/pe.json');
	$students = json_decode($jsonData, true);
	contenu($students[0]);
}


function contenu($etd)
{
	echo '
	<div>
		<h1>Génération avis de poursuite d\'études</h1>
		<div class="container">
			<h2 id="etudiant">
				</h1>
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
					<tbody id="BUT12">
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
					<tbody id="BUT3">
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
							<td><input type="radio" id="favorable" name="avisInge"></td>
							<td><input type="radio" id="assezFavorable" name="avisInge"></td>
							<td><input type="radio" id="sansAvis" name="avisInge"></td>
							<td><input type="radio" id="reserve" name="avisInge"></td>
						</tr>
						<tr>
							<th>En Master</th>
							<td><input type="radio" id="tresFavorableIngé" name="avisMaster"></td>
							<td><input type="radio" id="favorable" name="avisMaster"></td>
							<td><input type="radio" id="assezFavorable" name="avisMaster"></td>
							<td><input type="radio" id="sansAvis" name="avisMaster"></td>
							<td><input type="radio" id="reserve" name="avisMaster"></td>
						</tr>
						<tr>
							<th>Commentaire</th>
							<td colspan="6"><textarea></textarea></td>
						</tr>
					</tbody>
				</table>
				<div class="gridRessource">
					<label id="modification">*Modification non sauvegardée*</label>
					<button class="validateButtonStyle" type="import" name="signCachet" value="">Générer</button>
					<button class="validateButtonStyle" type="import" name="signCachet" value="">Sauvegarder les modification</button>
					<button class="validateButtonStyle" type="import" name="signCachet" value="">Suivant</button>
				</div>
		</div>
	</div>

	<script>

		// Charger le fichier JSON
		fetch(\'../data/etudiants.json\')
			.then(response => response.json())
			.then(data => {

				// Obtenir les informations de l\'étudiant
				const etudiant = data['.$etd.'];

				//prenom et nom de l\'étudiant
				const etud = document.getElementById(\'etudiant\');
				etud.innerHTML = `${etudiant.etdprenom} ${etudiant.etdnom}`;

				//BUT 1 et 2
				const but12 = document.getElementById(\'BUT12\');

				const but121 = but12.insertRow();
				but121.innerHTML = `
					<th>UE1 – Réaliser des applications</th>
					<td>${etudiant.but1[0].rang}</td>
					<td>${etudiant.but1[0].moy}</td>
					<td>${etudiant.but2[0].rang}</td>
					<td>${etudiant.but2[0].moy}</td>`;

				const but122 = but12.insertRow();
				but122.innerHTML = `
					<th>UE2 – Optimiser des applicationsns</th>
					<td>${etudiant.but1[1].rang}</td>
					<td>${etudiant.but1[1].moy}</td>
					<td>${etudiant.but2[1].rang}</td>
					<td>${etudiant.but2[1].moy}</td>`;

				const but123 = but12.insertRow();
				but123.innerHTML = `
					<th>UE3 – Administrer des systèmes</th>
					<td>${etudiant.but1[2].rang}</td>
					<td>${etudiant.but1[2].moy}</td>
					<td>${etudiant.but2[2].rang}</td>
					<td>${etudiant.but2[2].moy}</td>`;

				const but124 = but12.insertRow();
				but124.innerHTML = `
					<th>UE4 – Gérer des données</th>
					<td>${etudiant.but1[3].rang}</td>
					<td>${etudiant.but1[3].moy}</td>
					<td>${etudiant.but2[3].rang}</td>
					<td>${etudiant.but2[3].moy}</td>`;

				const but125 = but12.insertRow();
				but125.innerHTML = `
					<th>UE5 – Conduire des projets</th>
					<td>${etudiant.but1[4].rang}</td>
					<td>${etudiant.but1[4].moy}</td>
					<td>${etudiant.but2[4].rang}</td>
					<td>${etudiant.but2[4].moy}</td>`;

				const but126 = but12.insertRow();
				but126.innerHTML = `
					<th>UE6 – Collaborer</th>
					<td>${etudiant.but1[5].rang}</td>
					<td>${etudiant.but1[5].moy}</td>
					<td>${etudiant.but2[5].rang}</td>
					<td>${etudiant.but2[5].moy}</td>`;

				const but12math = but12.insertRow();
				but12math.innerHTML = `
					<th>Maths</th>
					<td>${etudiant.but1[6].rang}</td>
					<td>${etudiant.but1[6].moy}</td>
					<td>${etudiant.but2[6].rang}</td>
					<td>${etudiant.but2[6].moy}</td>`;

				const but12anglais = but12.insertRow();
				but12anglais.innerHTML = `
					<th>Anglais</th>
					<td>${etudiant.but1[7].rang}</td>
					<td>${etudiant.but1[7].moy}</td>
					<td>${etudiant.but2[7].rang}</td>
					<td>${etudiant.but2[7].moy}</td>`;

				const absences12 = but12.insertRow();
				absences12.innerHTML = `
				<th>Nombre d\'absences injustifiées</th>
						<td colspan="2"></td>
						<td colspan="2"></td>`;

				//BUT 3
				const but3 = document.getElementById(\'BUT3\');

				const but31 = but3.insertRow();
				but31.innerHTML = `
					<th>UE1 – Réaliser des applications</th>
					<td>${etudiant.but3[0].rang}</td>
					<td>${etudiant.but3[0].moy}</td>`;

				const but32 = but3.insertRow();
				but32.innerHTML = `
					<th>UE2 – Optimiser des applicationsns</th>
					<td>${etudiant.but3[0].rang}</td>
					<td>${etudiant.but3[0].moy}</td>`;

				const but36 = but3.insertRow();
				but36.innerHTML = `
					<th>UE6 – Collaborer</th>
					<td>${etudiant.but3[0].rang}</td>
					<td>${etudiant.but3[0].moy}</td>`;

				const but3math = but3.insertRow();
				but3math.innerHTML = `
					<th>Maths</th>
					<td>${etudiant.but3[0].rang}</td>
					<td>${etudiant.but3[0].moy}</td>`;

				const but3anglais = but3.insertRow();
				but3anglais.innerHTML = `
					<th>Anglais</th>
					<td>${etudiant.but3[0].rang}</td>
					<td>${etudiant.but3[0].moy}</td>`;

				const absences3 = but3.insertRow();
				absences3.innerHTML = `
				<th>Nombre d\'absences injustifiées</th>
				<td colspan="2"></td>`;
			})
			.catch(error => console.error(\'Erreur lors du chargement du fichier JSON :\', error));
	</script>';
}

head('css/commissionEtFicheAvis.css');

contenu();

foot();





?>