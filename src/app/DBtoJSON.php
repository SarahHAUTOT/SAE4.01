<?php

// Connexion à la base de données
require 'DB.inc.php';

generateUsers();
generateCompMod();
generateYears();
generateExport();

echo "pute";


/**********************************************************************/
/*                              USERS                                 */
/**********************************************************************/

function generateUsers()
{
	$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");
	
	// Récupérer les utilisateur 
	$query     = "SELECT * FROM Utilisateur";
	$users     = $db->execQuery($query);


	// Générer le JSON
	$jsonData = json_encode($users, JSON_PRETTY_PRINT);
	// Écrire le JSON dans un fichier
	file_put_contents( '../../data/users.json', $jsonData);
	echo "Le fichier users.json a été créé avec succès.<br>";
}


/**********************************************************************/
/*                              FILES                                 */
/**********************************************************************/

function generateExport()
{
	$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");
	
	// Récupérer les utilisateur 
	$query     = "SELECT * FROM Export";
	$users     = $db->execQuery($query);


	// Générer le JSON
	$jsonData = json_encode($users, JSON_PRETTY_PRINT);
	// Écrire le JSON dans un fichier
	file_put_contents( '../../data/export.json', $jsonData);
	echo "Le fichier users.json a été créé avec succès.<br>";
}





/**********************************************************************/
/*                       COMPETENCES-MODULES                          */
/**********************************************************************/

function generateCompMod()
{
	$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

	// Récupérer les utilisateur 
	$query       = "SELECT * FROM Competence ORDER BY compId";
	$competences = $db->execQuery($query);

	foreach ($competences as &$competence)
	{
		$competence['modules'] = [];

		$query       = "SELECT * FROM CompMod c JOIN Module m ON c.modId = m.modId WHERE compId =" . $competence['compid'];
		$modules     = $db->execQuery($query);

		foreach ($modules as $module) {
			$competence['modules'][] = [
				'modId'   => $module['modid'],
				'modLib'  => $module['modlib'],
				'coef'    => $module['modcoef']
			];
		}
	}

	// Générer le JSON
	$jsonData = json_encode($competences, JSON_PRETTY_PRINT);
	// Écrire le JSON dans un fichier
	file_put_contents( '../../data/compMod.json', $jsonData);
	echo "Le fichier compMod.json a été créé avec succès.<br>";
}


/**********************************************************************/
/*                            STUDENTS                                */
/**********************************************************************/

function generateYears()
{
	
	$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

	// Récupérer les données de la table Annee
	$query     = "SELECT * FROM Annee";
	$years     = $db->execQuery($query);

	$query     = "SELECT * FROM Semestre";
	$semesters = $db->execQuery($query);


	//For each year 
	foreach ($years as &$year) {

		$year["semesters"] = [];

		foreach ($semesters as &$semester) 
		{
			// We get the students from this year and semester
			$query = "SELECT * FROM Etudiant WHERE etdId IN (SELECT etdId FROM AdmComp a JOIN Competence c ON a.compId = c.compId WHERE a.anneeId = ".$year["anneeid"] . " AND semId = ".$semester["semid"] . ")";
			$students = $db->execQuery($query);


			$semesterStudents = []; // Array to hold students for this semester

			// For each student
			foreach ($students as &$student) 
			{
				// Get rank of the student for the current semester and year
				$query = 'SELECT getRankSem('.$semester['semid'].', '.$student['etdid'].', '.$year['anneeid'].') as "rank" FROM AdmComp'; 
				$rankSem = $db->execQuery($query);
				$student['rank'] = $rankSem[0]['rank'];

				/* MODULES */
				// We get the grades from this year, semester, and student
				$query = "SELECT * FROM Moyenne m  JOIN Module mo ON mo.modId = m.modId WHERE m.anneeId = " . $year['anneeid'] . " AND etdId = " . $student["etdid"] . " AND m.modId IN (SELECT modId FROM CompMod a JOIN Competence c ON a.compId = c.compId WHERE semId = ".$semester["semid"] . ")";
				$grades = $db->execQuery($query);


				// Put them in student
				$student['modules'] = [];
				foreach ($grades as $grade) {
					$student['modules'][] = [
						'modId'   => $grade['modid'],
						'modLib'  => $grade['modlib'],
						'noteVal' => $grade['noteval']
					];
				}

				/* COMPETENCES */
				// We get the competences from this year, semester, and student
				$query = "SELECT * FROM AdmComp a JOIN Competence c ON a.compId = a.compId WHERE anneeId = " . $year['anneeid'] . " AND semId = ".$semester["semid"]. " AND etdId = ".$student["etdid"];
				$competences = $db->execQuery($query);

				// Put them in student
				$student['competences'] = [];
				foreach ($competences as $competence) {
					$student['competences'][] = [
						'compId'   => $competence['compid'],
						'compLib'  => $competence['complib'],
						'admi'     => $competence['admi']
					];
				}

				/* YEAR */
				$semesterStudents[] = $student; // Add the student to the array of students for this semester
			}

			$semester['etd'] = $semesterStudents; // Assign the array of students to the semester directly
			$year['semesters'][] = $semester; // Add the semester to the array of semesters for this year
		}
	}


	// Générer le JSON
	$jsonData = json_encode($years, JSON_PRETTY_PRINT);
	// Écrire le JSON dans un fichier
	file_put_contents( '../../data/donnees.json', $jsonData);

	echo "Le fichier donnees.json a été créé avec succès.<br>";
}



/**********************************************************************/
/*                          CSV CREATION                              */
/**********************************************************************/

function generateStudents(int $yearId, int $semesterId)
{
	
	$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

	// Getting all of the student for a specified year and semester
	$query     = "SELECT etdId, etdNom, etdPrenom, etdCursus, etdBonus 
				FROM  Etudiant e JOIN AdmComp  admc ON e.etdId=admc.etdId 
				JOIN  Competence c ON c.compId=admc.compId 
				WHERE anneId = ".$yearId." AND semId = ".$semesterId;
	$students  = $db->execQuery($query);

	// For each studient
	foreach ($students as &$studient) 
	{
		$query = 'SELECT getRankSem('.$semesterId.', '.$student['etdid'].', '.$yearId.') 
				  FROM AdmComp'; 
		$rank = $db->execQuery($query);
		$student['rank'] = $rank;

		$query = "SELECT getNbAdmiUE(".$semesterId.", ".$student['etdid'].", ".$yearId.") 
				FROM AdmComp";
		$nbAdmiUE = $db->execQuery($query);

		$students['admiUEs'] = $nbAdmiUE; // UEs that are passed

		// For each competences of the last semester
		$query = "SELECT * FROM Competence WHERE semId =".$semesterId -1;
		$lastSemComps = $db->execQuery($query);

		foreach ($lastSemComps as &$comp) 
		{
            $query = 'SELECT getRCUE('.($semesterId-1).', '.$comp['compid'].', '.$student['etdid'].', '.$yearId.') FROM AdmComp';
			$admiRCUE = $db->execQuery($query);

			$student['RCUE'][] = 
			[
				'compId'=> $comp['compid'],
				'admi'  => $admiRCUE
			];
		}

		$query = 'SELECT getSemMoy('.$semesterId.', '.$student['etdid'].', '.$yearId.') FROM AdmComp'; 
		$moySem = $db->execQuery($query);
		$student['moySem'] = $moySem;

		$query = "SELECT * FROM Competence WHERE semId =".$semesterId;
		$competences = $db->execQuery($query);

		// For each competences of the semester
		foreach ($competences as &$comp) 
		{
			$query = "SELECT compId, getCompMoy(".$semesterId.", ".$comp['compid'].", ".$student['etdid'].", ".$yearId.") AS moyUe FROM Moyenne";
			$compInfo = $db->execQuery($query);

			$student['competences'][] = 
			[
				'compCode'=> $comp['compcode'],
				'moy'     => $compInfo['moyUe']
			];

			$query = "SELECT modCode, noteVal 
					  FROM  Module m JOIN CompMod cm  ON m.modId=cm.modId 
					  				 JOIN Moyenne moy ON m.modId=moy.modId 
					  WHERE compId = ".$compInfo['compid']."";
			
			$modules = $db->execQuery($query);

			foreach ($modules as &$mod) 
			{
				$student['competences']['modules'][] = 
				[
					'modCode' => $mod['modcode'],	
					'noteVal' => $mod['noteval']
				];
			}
		}
	}

	// JSON Generation
	$jsonData = json_encode($students, JSON_PRETTY_PRINT);
	file_put_contents( '../../data/csv.json', $jsonData);

	echo "Le fichier csv.json a été créé avec succès.<br>";
}
?>