<?php

// Connexion à la base de données
require 'DB.inc.php';

// generateUsers();
// generateCompMod();
// generateYears();
// generateExport();
// 
// echo "pute";


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
					$query = 'SELECT getCompMoy('.$student['etdid'].', '.$year['anneeid'].') as "moy" FROM AdmComp'; 
					$moySem = $db->execQuery($query);
					
					$student['competences'][] = [
						'moySem'   => $moySem[0]['moy'],
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

function generateStudentsCsv(int $yearId, int $semesterId)
{
	$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

	// Getting all of the student for a specified year and semester
	$query     = "SELECT e.etdId, etdNom, etdPrenom, etdCursus, etdBonus 
				FROM  Etudiant e JOIN AdmComp  admc ON e.etdId=admc.etdId 
				JOIN  Competence c ON c.compId=admc.compId 
				WHERE anneeId = ".$yearId." AND semId = ".$semesterId;
	$students  = $db->execQuery($query);

	// For each student
	foreach ($students as &$student) 
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
		$lastSemester = $semesterId-1;
		$query = "SELECT * FROM Competence WHERE semId =".$lastSemester;
		$lastSemComps = $db->execQuery($query);

		foreach ($lastSemComps as &$comp) 
		{
			$compNb = str_replace($lastSemester, "", $comp['compid']);
			$compId1 = $compNb.''.$lastSemester; 
			$compId2 = $compNb.''.($lastSemester-1);

            $query = 'SELECT getRCUE('.$compId1.', '.$compId2.', '.$student['etdid'].', '.$yearId.') FROM AdmComp';
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
			$query = "SELECT compId, getCompMoy(".$semesterId.", ".$comp['compid'].", ".$student['etdid'].", ".$yearId.") AS 'moyUe' FROM Moyenne";
			$compInfo = $db->execQuery($query);

			$student['competences'][] = 
			[
				'compCode'=> $comp['compcode'],
				'moy'     => $compInfo[0]['moyUe']
			];

			$query = "SELECT modCode, noteVal 
					  FROM  Module m JOIN CompMod cm  ON m.modId=cm.modId 
					  				 JOIN Moyenne moy ON m.modId=moy.modId 
					  WHERE compId = ".$compInfo[0]['compid'];
			
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




/**********************************************************************/
/*                   POURSUITE D'ETUDE CREATION                       */
/**********************************************************************/

function generateStudents(int $yearId)
{
	$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

	// Getting all of the student for a specified year and semester
	$query     = "SELECT e.etdId, etdNom, etdPrenom 
				  FROM  Etudiant e JOIN AdmComp admc ON e.etdId=admc.etdId 
				  WHERE anneeId = ".$yearId." AND CAST (compId as varchar) LIKE '5_'";
	$students  = $db->execQuery($query);

	// For each student
	$i = 0;
	foreach ($students as &$student) 
	{
		// For BUT 1 and BUT 2 (the first 4 semester)
		for ($i = 1; $i <= 4; $i++)
		{
			$query = "SELECT compId, compLib  FROM Competence c WHERE CAST (compId as varchar) LIKE '".$i."_'";
			$competences = $db->execQuery($query);

			// For BUT 1 and BUT 2 => we will determine the moy and rank of each students for every comp
			for ($j = 0; $j < count($competences) -2; $j = $j +2)
			{
				$query = "SELECT getCompMoy(".$competences[$j]['compid'].", ".$student['etdid'].", ".$yearId.") as \"compmoy\" FROM AdmComp";
				$moyComp1 = $db->execQuery($query);

				$query = "SELECT getCompMoy(".$competences[$j+1]['compid'].", ".$student['etdid'].", ".$yearId.") as \"compmoy\" FROM AdmComp";
				$moyComp2 = $db->execQuery($query);
                
				$moyBUT = ($moyComp1[0]['compmoy'] + $moyComp2[0]['compmoy']) /2;

				$UEid  = "UE ". str_replace("5", "", $competences[$j]['compid']."");
				$compLib = $competences[$j]['complib'] .'';

				$student['nbStud'] = $i;

				if ($i <= 2)
				{
					$student['BUT 1'][$UEid]['moy'] = $moyBUT;
					$student['BUT 1'][$UEid]['lib'] = $compLib;
				}
				else
				{
					$student['BUT 2'][$UEid]['moy'] = $moyBUT;
					$student['BUT 2'][$UEid]['lib'] = $compLib;
				}
			}

			// calculer moy math + anglais du BUT 1 et 2

			// For BUT 3 => get the moy and rank of each students for the comp 51, 52 and 56
			$compIds = [51, 52, 56];
			for ($i = 0; $i < count($compIds); $i++)
			{
				$query = "SELECT compLib, getCompMoy(".$compIds[$i].", ".$student['etdid'].", ".$yearId.")
                FROM AdmComp admc JOIN Competence c on c.compId=admc.compId WHERE c.compId = ".$compIds[$i];
				$competences = $db->execQuery($query);

				$compid  = "UE ". str_replace("".$i, "", $compIds[$i]."");
				$compLib = $competences[$j]['complib'] .'';

				$student['BUT 3'][$compid]['moy'] = $moyBUT;
				$student['BUT 3'][$compid]['lib'] = $compLib;
			}

			// calculer moy math + anglais du BUT 3

		}

		$i++;
	}

	// JSON Generation
	$jsonData = json_encode($students, JSON_PRETTY_PRINT);
	file_put_contents( '../../data/etudiants.json', $jsonData);

	echo "Le fichier etudiants.json a été créé avec succès.<br>";
}
?>