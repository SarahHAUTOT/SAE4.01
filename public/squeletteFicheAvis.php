<?php

//Pour la structure
include 'background.php';
include '../src/app/DB.inc.php';

// Démarrer la session
session_start();

$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");
$year = $_SESSION['year'];
$query = "SELECT DISTINCT COUNT(e.etdid) 
		  FROM Etudiant e JOIN admcomp admc ON e.etdid=admc.etdid 
		  WHERE anneeid = ".$year." and cast(compid as varchar) like '5_';";
$countStud = $db->execQuery($query);

global $nbStudents;
$nbStudents = $countStud[0]['count'];

if ($nbStudents == 0) header("Location : generationPoursuite.php");


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

head('css/commissionEtFicheAvis.css');

// Vérifier si on a appuyer sur le btn suivant
if (isset($_POST['idStudent'])) {
	global $nbStudents;
	$lastEtd = $nbStudents >= ($_POST['idStudent'] +1);

	$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

	$query = "SELECT *
			  FROM ( SELECT ROW_NUMBER() OVER(ORDER BY e.etdid) AS row_num, e.etdid, etdprenom, etdnom
    				 FROM Etudiant e JOIN AdmComp admc ON e.etdid=admc.etdid
    				 WHERE anneeid = ".$year." AND cast(compid as varchar) LIKE '5_'
					)
			  WHERE row_num =".($_POST['idStudent'] +1);
	$etd = $db->execQuery($query);

	contenue($etd, $lastEtd);
}
else
{
	$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

	$query = "SELECT *
			  FROM ( SELECT ROW_NUMBER() OVER(ORDER BY e.etdid) AS row_num, e.etdid, etdprenom, etdnom
    				 FROM Etudiant e JOIN AdmComp admc ON e.etdid=admc.etdid
    				 WHERE anneeid = ".$year." AND cast(compid as varchar) LIKE '5_'
					)
			  WHERE row_num =1";
	$etd = $db->execQuery($query);
	contenue($etd);
}

function contenue($etd, $suivant = true)
{
	$year = $_SESSION['year'];
	$structureEtd = [];


	echo '
	<div>
	<form action="squeletteFicheAvis.php" method="post">
		<h1>Génération avis de poursuite d\'études</h1>
		<div class="container">
			<input type="numbre" name="idStudent" value="'.$etd[0]['row_num'].'" />
			<h2>'.$etd[0]['etdprenom'].' '.$etd[0]['etdnom'].'</h1>
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
					<tbody>';

	

	$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

	$query = "SELECT complib FROM Competence WHERE semId = 1";
	$competencesNom = $db->execQuery($query);


	//Pour les 6 compétences
	for ($i = 0; $i < count($competencesNom); $i++)
	{

		echo '<tr>';
		echo '<th>'. $competencesNom[$i]['complib'].'</th>';


		//Moyenne BUT1
		$moy11 = $db->execQuery("SELECT * FROM getCompMoy(1".($i+1).",". $etd[0]['etdid'] . ",". $year-2 . ")");
		$moy12 = $db->execQuery("SELECT * FROM getCompMoy(2".($i+1).",". $etd[0]['etdid'] . ",". $year-2 . ")");
		$moy1 = ($moy11[0]['getcompmoy'] + $moy12[0]['getcompmoy']) / 2;

		//Moyenne BUT2
		$moy21 = $db->execQuery("SELECT * FROM getCompMoy(3".($i+1).",". $etd[0]['etdid'] . ",". $year-1 . ")");
		$moy22 = $db->execQuery("SELECT * FROM getCompMoy(4".($i+1).",". $etd[0]['etdid'] . ",". $year-1 . ")");
		$moy2 = ($moy21[0]['getcompmoy'] + $moy22[0]['getcompmoy']) / 2;

		
		//Rang BUT1
		$rank1 = $db->execQuery("SELECT * FROM getRankComp(2".($i+1).",". $etd[0]['etdid'] . ",". $year . ")");

		//Rank BUT2
		$rank2 = $db->execQuery("SELECT * FROM getRankComp(4".($i+1).",". $etd[0]['etdid'] . ",". $year . ")");

		echo '
		<td>'.$rank1[0]['getrankcomp'].'</td>
		<td>'.round($moy1 , 2).'</td>

		<td>'.$rank2[0]['getrankcomp'].'</td>
		<td>'.round($moy2, 2).'</td>';

		echo '</tr>';
	}


	//Math
	echo '<tr>';
	echo '<th> Maths </th>';


	//Moyenne BUT
	$moyMathBut1 = $db->execQuery("SELECT AVG(noteval) FROM Module m JOIN Moyenne mo ON m.modId = mo.modId WHERE modcat = 'Math' AND etdId = ".$etd[0]['etdid']. " AND anneeId = ". ($year - 2));
	$moyMathBut2 = $db->execQuery("SELECT AVG(noteval) FROM Module m JOIN Moyenne mo ON m.modId = mo.modId WHERE modcat = 'Math' AND etdId = ".$etd[0]['etdid']. " AND anneeId = ". ($year - 1));
	
	//Rang 
	$rank1 = $db->execQuery("SELECT * FROM getRankSem(2,". $etd[0]['etdid'] . ",". $year . ")");
	$rank2 = $db->execQuery("SELECT * FROM getRankSem(4,". $etd[0]['etdid'] . ",". $year . ")");

	echo '
	<td>'.$rank1[0]['getranksem'].'</td>
	<td>'.round($moyMathBut1[0]['avg'] , 2).'</td>

	<td>'.$rank2[0]['getranksem'].'</td>
	<td>'.round($moyMathBut2[0]['avg'], 2).'</td>';

	echo '</tr>';


	//Anglais
	echo '<tr>';
	echo '<th> Anglais </th>';


	//Moyenne BUT
	$moyAnglaisBut1 = $db->execQuery("SELECT AVG(noteval) FROM Module m JOIN Moyenne mo ON m.modId = mo.modId WHERE m.modId IN ('R110', 'R212') AND etdId = ".$etd[0]['etdid']. " AND anneeId = ". ($year - 2));
	$moyAnglaisBut2 = $db->execQuery("SELECT AVG(noteval) FROM Module m JOIN Moyenne mo ON m.modId = mo.modId WHERE m.modId IN ('R312', 'R405') AND etdId = ".$etd[0]['etdid']. " AND anneeId = ". ($year - 1));
	
	//Rang 
	$rank1 = $db->execQuery("SELECT * FROM getRankSem(2,". $etd[0]['etdid'] . ",". $year . ")");
	$rank2 = $db->execQuery("SELECT * FROM getRankSem(4,". $etd[0]['etdid'] . ",". $year . ")");

	echo '
	<td>'.$rank1[0]['getranksem'].'</td>
	<td>'.round($moyAnglaisBut1[0]['avg'] , 2).'</td>

	<td>'.$rank2[0]['getranksem'].'</td>
	<td>'.round($moyAnglaisBut2[0]['avg'], 2).'</td>';


	$abs = $db->execQuery("SELECT etdAbs FROM Etudiant WHERE etdId = " . $etd[0]['etdid']);

	echo '</tr>
			<tr>
				<th>Nombre d\'absences injustifiées</th>
				<td colspan="4">'. $abs[0]['etdabs'] .'</td>
			</tr>';












	// BUT 3

	echo '
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
					<tbody> ';

					
	$query = "SELECT complib FROM Competence WHERE semId = 5 AND compId IN (51,52,56)";
	$competencesNom = $db->execQuery($query);


	//Pour les 3 compétences
	for ($ind = 0; $ind < count($competencesNom); $ind++)
	{

		if ($ind == 2) $i = 5;
		else $i = $ind; 


		echo '<tr>';
		echo '<th>'. $competencesNom[$ind]['complib'].'</th>';


		//Moyenne BUT1
		$moy = $db->execQuery("SELECT * FROM getCompMoy(5".($i+1).",". $etd[0]['etdid'] . ",". $year . ")");
		

		//Rang BUT1
		$rank = $db->execQuery("SELECT * FROM getRankComp(5".($i+1).",". $etd[0]['etdid'] . ",". $year . ")");

		echo '
		<td>'.$rank[0]['getrankcomp'].'</td>
		<td>'.round($moy[0]['getcompmoy'] , 2).'</td>';

		echo '</tr>';
	}


	//Math
	echo '<tr>';
	echo '<th> Maths </th>';


	//Moyenne BUT
	$moyMathBut1 = $db->execQuery("SELECT AVG(noteval) FROM Module m JOIN Moyenne mo ON m.modId = mo.modId WHERE modcat = 'Math' AND etdId = ".$etd[0]['etdid']. " AND anneeId = ". ($year - 2));
	$moyMathBut2 = $db->execQuery("SELECT AVG(noteval) FROM Module m JOIN Moyenne mo ON m.modId = mo.modId WHERE modcat = 'Math' AND etdId = ".$etd[0]['etdid']. " AND anneeId = ". ($year - 1));
	
	//Rang 
	$rank1 = $db->execQuery("SELECT * FROM getRankSem(2,". $etd[0]['etdid'] . ",". $year . ")");
	$rank2 = $db->execQuery("SELECT * FROM getRankSem(4,". $etd[0]['etdid'] . ",". $year . ")");

	echo '
	<td>'.$rank1[0]['getranksem'].'</td>
	<td>'.round($moyMathBut1[0]['avg'] , 2).'</td>';

	echo '</tr>';


	//Anglais
	$abs = $db->execQuery("SELECT etdAbs FROM Etudiant WHERE etdId = " . $etd[0]['etdid']);

	echo '</tr>
			<tr>
				<th>Nombre d\'absences injustifiées</th>
				<td colspan="2">'. $abs[0]['etdabs'] .'</td>
			</tr>';




	// echo '
	// 					</tr>
	// 					<tr>
	// 						<th>UE2-Optimiser des applications</th>
	// 						<td>'.$etd['BUT 1']['UE 2']['rank'].'</td>
	// 						<td>'.$etd['BUT 1']['UE 2']['moy' ].'</td>
	// 						<td>'.$etd['BUT 2']['UE 2']['rank'].'</td>
	// 						<td>'.$etd['BUT 2']['UE 2']['moy'].'</td>
	// 					</tr>
	// 					<tr>
	// 						<th>UE3-Administrer des systèmes</th>
	// 						<td>'.$etd['BUT 1']['UE 3']['rank'].'</td>
	// 						<td>'.$etd['BUT 1']['UE 3']['moy' ].'</td>
	// 						<td>'.$etd['BUT 2']['UE 3']['rank'].'</td>
	// 						<td>'.$etd['BUT 2']['UE 3']['moy'].'</td>
	// 					</tr>
	// 					<tr>
	// 						<th>UE4-Gérer des données</th>
	// 						<td>'.$etd['BUT 1']['UE 4']['rank'].'</td>
	// 						<td>'.$etd['BUT 1']['UE 4']['moy' ].'</td>
	// 						<td>'.$etd['BUT 2']['UE 4']['rank'].'</td>
	// 						<td>'.$etd['BUT 2']['UE 4']['moy'].'</td>
	// 					</tr>
	// 					<tr>
	// 						<th>UE5-Conduire des projets</th>
	// 						<td>'.$etd['BUT 1']['UE 5']['rank'].'</td>
	// 						<td>'.$etd['BUT 1']['UE 5']['moy' ].'</td>
	// 						<td>'.$etd['BUT 2']['UE 5']['rank'].'</td>
	// 						<td>'.$etd['BUT 2']['UE 5']['moy'].'</td>
	// 					</tr>
	// 					<tr>
	// 						<th>UE6-Collaborer</th>
	// 						<td>'.$etd['BUT 1']['UE 6']['rank'].'</td>
	// 						<td>'.$etd['BUT 1']['UE 6']['moy' ].'</td>
	// 						<td>'.$etd['BUT 2']['UE 6']['rank'].'</td>
	// 						<td>'.$etd['BUT 2']['UE 6']['moy'].'</td>
	// 					</tr>
	// 					<tr>
	// 						<th>Maths</th>
	// 						<td>'.$etd['BUT 1']['Maths']['rank'].'</td>
	// 						<td>'.$etd['BUT 1']['Maths']['moy' ].'</td>
	// 						<td>'.$etd['BUT 2']['Maths']['rank'].'</td>
	// 						<td>'.$etd['BUT 2']['Maths']['moy'].'</td>
	// 					</tr>
	// 					<tr>
	// 						<th>Anglais</th>
	// 						<td>'.$etd['BUT 1']['Anglais']['rank'].'</td>
	// 						<td>'.$etd['BUT 1']['Anglais']['moy' ].'</td>
	// 						<td>'.$etd['BUT 2']['Anglais']['rank'].'</td>
	// 						<td>'.$etd['BUT 2']['Anglais']['moy'].'</td>
	// 					</tr>
	// 					<tr>
	// 						<th>Nombre d\'absences injustifiées</th>
	// 						<td colspan="2"></td>
	// 						<td colspan="2"></td>
	// 					</tr>

	echo '
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
				<BUTton class="validateButtonStyle" type="import" name="signCachet" value="">Prévisualiser</BUTton>
				<BUTton class="validateButtonStyle" type="import" name="signCachet" value="">Sauvegarder les modification</BUTton>
				<BUTton class="validateButtonStyle" type="import" name="signCachet" value="">Générer</BUTton>
			</div>
		</div>';

	if ($suivant)
		echo '<button type="submit" name="action" value="etdSuiv" class="validateButtonStyle">Suivant</button>';
	else 
		echo '<button type="submit" name="action" value="generatePDF" class="validateButtonStyle">Génerer</button>';

	echo '</form>
	</div>';
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
				<tbody>
					<tr>
						<th>Apprentissage</th>
						<td>BUT 1</td>
						<td><input id="app1" type="checkbox" checked="non" /></td>
						<td>BUT 2</td>
						<td><input id="app2" type="checkbox" checked="non" /></td>
						<td>BUT 3</td>
						<td><input id="app3" type="checkbox" checked="non" /></td>
					</tr>
					<tr>
						<th>Parcours d’études</th>
						<td>n-2</td>
						<td><input type="number" id="pde1" min="0"></td>
						<td>n-1</td>
						<td><input type="number" id="pde2" min="0"></td>
						<td>n</td>
						<td><input type="number" id="pde3" min="0"></td>
					</tr>
					<tr>
						<th>Parcours BUT</th>
						<td colspan="6">A "Réalisation d\'application: conception...</td>
					</tr>
					<tr>
						<th>Si mobilité à létranger (lieu,durée)</th>
						<td contenteditable="true" colspan="6"><textarea id="mob"></textarea></td>
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
					<tr>
						<th>Nombre d\'absences injustifiées</th>
						<td colspan="2"><input type="number" id="abs1" min="0"></td>
						<td colspan="2"><input type="number" id="abs2" min="0"></td>
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
				<tbody id="BUT3">
					<tr>
						<th>Nombre d\'absences injustifiées</th>
						<td colspan="2"><input type="number" id="abs3" min="0"></td>
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
						<td><input type="radio" id="5ingé" name="avisInge" checked></td>
						<td><input type="radio" id="4ingé" name="avisInge"></td>
						<td><input type="radio" id="3ingé" name="avisInge"></td>
						<td><input type="radio" id="2ingé" name="avisInge"></td>
						<td><input type="radio" id="1ingé" name="avisInge"></td>
					</tr>
					<tr>
						<th>En Master</th>
						<td><input type="radio" id="1mast" name="avisMaster" checked></td>
						<td><input type="radio" id="2mast" name="avisMaster"></td>
						<td><input type="radio" id="3mast" name="avisMaster"></td>
						<td><input type="radio" id="4mast" name="avisMaster"></td>
						<td><input type="radio" id="5mast" name="avisMaster"></td>
					</tr>
					<tr>
						<th>Commentaire</th>
						<td colspan="6"><textarea id="comm"></textarea></td>
					</tr>
				</tbody>
			</table>
			<div class="gridRessource">
				<button id="suivant" class="validateButtonStyle" type="import" name="Suivant"
					value="">Suivant</button>
			</div>
	</div>
</div>

<script>
	const avi = {};
	// Charger le fichier JSON
	fetch(\'../data/etudiants.json\')
		.then(response => response.json())
		.then(data => {

			// Obtenir les informations de l\'étudiant
			const etudiant = data[0];

			//prenom et nom de l\'étudiant
			const etud = document.getElementById(\'etudiant\');
			etud.innerHTML = `${etudiant.etdprenom} ${etudiant.etdnom}`;
			avi.nom = etudiant.etdnom;
			avi.prenom = etudiant.etdprenom;

			avi.annee = [
				{ "C1": {}, "C2": {}, "C3": {}, "C4": {}, "C5": {}, "C6": {}, "Maths": {}, "Anglais": {} },
				{ "C1": {}, "C2": {}, "C3": {}, "C4": {}, "C5": {}, "C6": {}, "Maths": {}, "Anglais": {} },
				{ "C1": {}, "C2": {}, "C6": {}, "Maths": {}, "Anglais": {} }];

			//BUT 1 et 2
			const but12 = document.getElementById(\'BUT12\');

			const but121 = but12.insertRow(0);
			but121.innerHTML = `
				<th>UE1 – Réaliser des applications</th>
				<td>${etudiant.but1[0].rang}</td>
				<td>${etudiant.but1[0].moy}</td>
				<td>${etudiant.but2[0].rang}</td>
				<td>${etudiant.but2[0].moy}</td>`;

			avi.annee[0].C1.rang = etudiant.but1[0].rang;
			avi.annee[0].C1.moy = etudiant.but1[0].moy;
			avi.annee[1].C1.rang = etudiant.but2[0].rang;
			avi.annee[1].C1.moy = etudiant.but2[0].moy;

			const but122 = but12.insertRow(1);
			but122.innerHTML = `
				<th>UE2 – Optimiser des applicationsns</th>
				<td>${etudiant.but1[1].rang}</td>
				<td>${etudiant.but1[1].moy}</td>
				<td>${etudiant.but2[1].rang}</td>
				<td>${etudiant.but2[1].moy}</td>`;

			avi.annee[0].C2.rang = etudiant.but1[1].rang;
			avi.annee[0].C2.moy = etudiant.but1[1].moy;
			avi.annee[1].C2.rang = etudiant.but2[1].rang;
			avi.annee[1].C2.moy = etudiant.but2[1].moy;

			const but123 = but12.insertRow(2);
			but123.innerHTML = `
				<th>UE3 – Administrer des systèmes</th>
				<td>${etudiant.but1[2].rang}</td>
				<td>${etudiant.but1[2].moy}</td>
				<td>${etudiant.but2[2].rang}</td>
				<td>${etudiant.but2[2].moy}</td>`;

			avi.annee[0].C3.rang = etudiant.but1[2].rang;
			avi.annee[0].C3.moy = etudiant.but1[2].moy;
			avi.annee[1].C3.rang = etudiant.but2[2].rang;
			avi.annee[1].C3.moy = etudiant.but2[2].moy;

			const but124 = but12.insertRow(3);
			but124.innerHTML = `
				<th>UE4 – Gérer des données</th>
				<td>${etudiant.but1[3].rang}</td>
				<td>${etudiant.but1[3].moy}</td>
				<td>${etudiant.but2[3].rang}</td>
				<td>${etudiant.but2[3].moy}</td>`;

			avi.annee[0].C4.rang = etudiant.but1[3].rang;
			avi.annee[0].C4.moy = etudiant.but1[3].moy;
			avi.annee[1].C4.rang = etudiant.but2[3].rang;
			avi.annee[1].C4.moy = etudiant.but2[3].moy;

			const but125 = but12.insertRow(4);
			but125.innerHTML = `
				<th>UE5 – Conduire des projets</th>
				<td>${etudiant.but1[4].rang}</td>
				<td>${etudiant.but1[4].moy}</td>
				<td>${etudiant.but2[4].rang}</td>
				<td>${etudiant.but2[4].moy}</td>`;

			avi.annee[0].C5.rang = etudiant.but1[4].rang;
			avi.annee[0].C5.moy = etudiant.but1[4].moy;
			avi.annee[1].C5.rang = etudiant.but2[4].rang;
			avi.annee[1].C5.moy = etudiant.but2[4].moy;

			const but126 = but12.insertRow(5);
			but126.innerHTML = `
				<th>UE6 – Collaborer</th>
				<td>${etudiant.but1[5].rang}</td>
				<td>${etudiant.but1[5].moy}</td>
				<td>${etudiant.but2[5].rang}</td>
				<td>${etudiant.but2[5].moy}</td>`;

			avi.annee[0].C6.rang = etudiant.but1[5].rang;
			avi.annee[0].C6.moy = etudiant.but1[5].moy;
			avi.annee[1].C6.rang = etudiant.but2[5].rang;
			avi.annee[1].C6.moy = etudiant.but2[5].moy;

			const but12math = but12.insertRow(6);
			but12math.innerHTML = `
				<th>Maths</th>
				<td>${etudiant.but1[6].rang}</td>
				<td>${etudiant.but1[6].moy}</td>
				<td>${etudiant.but2[6].rang}</td>
				<td>${etudiant.but2[6].moy}</td>`;

			avi.annee[0].Maths.rang = etudiant.but1[6].rang;
			avi.annee[0].Maths.moy = etudiant.but1[6].moy;
			avi.annee[1].Maths.rang = etudiant.but2[6].rang;
			avi.annee[1].Maths.moy = etudiant.but2[6].moy;

			const but12anglais = but12.insertRow(7);
			but12anglais.innerHTML = `
				<th>Anglais</th>
				<td>${etudiant.but1[7].rang}</td>
				<td>${etudiant.but1[7].moy}</td>
				<td>${etudiant.but2[7].rang}</td>
				<td>${etudiant.but2[7].moy}</td>`;

			avi.annee[0].Anglais.rang = etudiant.but1[7].rang;
			avi.annee[0].Anglais.moy = etudiant.but1[7].moy;
			avi.annee[1].Anglais.rang = etudiant.but2[7].rang;
			avi.annee[1].Anglais.moy = etudiant.but2[7].moy;

			//BUT 3
			const but3 = document.getElementById(\'BUT3\');

			const but31 = but3.insertRow(0);
			but31.innerHTML = `
				<th>UE1 – Réaliser des applications</th>
				<td>${etudiant.but3[0].rang}</td>
				<td>${etudiant.but3[0].moy}</td>`;

			avi.annee[2].C1.rang = etudiant.but3[0].rang;
			avi.annee[2].C1.moy = etudiant.but3[0].moy;

			const but32 = but3.insertRow(1);
			but32.innerHTML = `
				<th>UE2 – Optimiser des applicationsns</th>
				<td>${etudiant.but3[1].rang}</td>
				<td>${etudiant.but3[1].moy}</td>`;

			avi.annee[2].C2.rang = etudiant.but3[1].rang;
			avi.annee[2].C2.moy = etudiant.but3[1].moy;

			const but36 = but3.insertRow(2);
			but36.innerHTML = `
				<th>UE6 – Collaborer</th>
				<td>${etudiant.but3[2].rang}</td>
				<td>${etudiant.but3[2].moy}</td>`;

			avi.annee[2].C6.rang = etudiant.but3[2].rang;
			avi.annee[2].C6.moy = etudiant.but3[2].moy;

			const but3math = but3.insertRow(3);
			but3math.innerHTML = `
				<th>Maths</th>
				<td>${etudiant.but3[3].rang}</td>
				<td>${etudiant.but3[3].moy}</td>`;

			avi.annee[2].Maths.rang = etudiant.but3[3].rang;
			avi.annee[2].Maths.moy = etudiant.but3[3].moy;

			const but3anglais = but3.insertRow(4);
			but3anglais.innerHTML = `
				<th>Anglais</th>
				<td>${etudiant.but3[4].rang}</td>
				<td>${etudiant.but3[4].moy}</td>`;

			avi.annee[2].Anglais.rang = etudiant.but3[4].rang;
			avi.annee[2].Anglais.moy = etudiant.but3[4].moy;
		})
		.catch(error => console.error(\'Erreur lors du chargement du fichier JSON :\', error));

	//commentaire
	const commentaire = document.getElementById(\'comm\');
	avi.comm = commentaire.value;
	commentaire.addEventListener(\'input\', function () {
		avi.comm = commentaire.value;
	});

	//mobilité
	const mobil = document.getElementById(\'mob\');
	avi.mobilite = mobil.value;
	mobil.addEventListener(\'input\', function () {
		avi.mobilite = mobil.value;
	});

	//apprentissage
	avi.apprenti = ["non", "non", "non"];

	const apprenti1 = document.getElementById(\'app1\');
	apprenti1.addEventListener(\'change\', function () {
		if (apprenti1.checked) {
			avi.apprenti[0] = "oui";
		} else {
			avi.apprenti[0] = "non";
		}
	})

	const apprenti2 = document.getElementById(\'app2\');
	apprenti2.addEventListener(\'change\', function () {
		if (apprenti2.checked) {
			avi.apprenti[1] = "oui";
		} else {
			avi.apprenti[1] = "non";
		}
	})

	const apprenti3 = document.getElementById(\'app3\');
	apprenti3.addEventListener(\'change\', function () {
		if (apprenti3.checked) {
			avi.apprenti[2] = "oui";
		} else {
			avi.apprenti[2] = "non";
		}
	})

	// avis ingenieur
	avi.avisIngenieur = 5;

	const ingenieurs = document.querySelectorAll(\'input[type="radio"][name="avisInge"]\');
	ingenieurs.forEach(ingenieur => {
		ingenieur.addEventListener(\'change\', function () {
			// Vérifier quel bouton radio est sélectionné
			if (this.checked) {
				switch (this.id) {
					case "1ingé":
						avi.avisIngenieur = 1;
						break;
					case "2ingé":
						avi.avisIngenieur = 2;
						break;
					case "3ingé":
						avi.avisIngenieur = 3;
						break;
					case "4ingé":
						avi.avisIngenieur = 4;
						break;
					case "5ingé":
						avi.avisIngenieur = 5;
						break;
					default:
						break;
				};
			}
		});
	});


	// avis ingenieur
	avi.avisMaster = 5;

	const masters = document.querySelectorAll(\'input[type="radio"][name="avisMaster"]\');
	masters.forEach(master => {
		master.addEventListener(\'change\', function () {
			// Vérifier quel bouton radio est sélectionné
			if (this.checked) {
				switch (this.id) {
					case "1mast":
						avi.avisMaster = 1;
						break;
					case "2mast":
						avi.avisMaster = 2;
						break;
					case "3mast":
						avi.avisMaster = 3;
						break;
					case "4mast":
						avi.avisMaster = 4;
						break;
					case "5mast":
						avi.avisMaster = 5;
						break;
					default:
						break;
				};
			}
		});
	});

	const suivant = document.getElementById(\'suivant\');
	suivant.addEventListener(\'click\', function () {
		callPHP("../src/app/export/export.php","avi etudiant", avi);
	});


	function callPHP(file, action, datas) {
		return new Promise((resolve, reject) => {
			fetch(file, {
				method: \'POST\',
				headers: {
					\'Content-Type\': \'application/json\',
				},
				body: JSON.stringify({ action: action, datas }),
			})
				.then(response => {
					if (!response.ok) {
						throw new Error(\'Network response was not ok\');
					}
					return response.text();
				})
				.then(data => {
					resolve(data); // Renvoie le message de succès du script PHP
				})
				.catch(error => {
					reject(error);
				});
		});
	}

</script>

<script>
	const abs1 = document.getElementById(\'abs1\');
	abs1.addEventListener(\'input\', function () {
		let valeur = abs1.value;
		valeur = valeur.replace(/[^0-9]/g, \'\');
		abs1.value = valeur;
	});

	const abs2 = document.getElementById(\'abs2\');
	abs2.addEventListener(\'input\', function () {
		let valeur = abs2.value;
		valeur = valeur.replace(/[^0-9]/g, \'\');
		abs2.value = valeur;
	});

	const abs3 = document.getElementById(\'abs3\');
	abs3.addEventListener(\'input\', function () {
		let valeur = abs3.value;
		valeur = valeur.replace(/[^0-9]/g, \'\');
		abs3.value = valeur;
	});


	const pde1 = document.getElementById(\'pde1\');
	pde1.addEventListener(\'input\', function () {
		let valeur = pde1.value;
		valeur = valeur.replace(/[^0-9]/g, \'\');
		pde1.value = valeur;
	});

	const pde2 = document.getElementById(\'pde2\');
	pde2.addEventListener(\'input\', function () {
		let valeur = pde2.value;
		valeur = valeur.replace(/[^0-9]/g, \'\');
		pde2.value = valeur;
	});

	const pde3 = document.getElementById(\'pde3\');
	pde3.addEventListener(\'input\', function () {
		let valeur = pde3.value;
		valeur = valeur.replace(/[^0-9]/g, \'\');
		abs3.value = valeur;
	});
</script>
';
}

foot();





?>