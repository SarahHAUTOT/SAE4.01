<?php

function export(String $year, String $type, String $semester)
{
	header('Content-type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=PV '.$type.' '.$semester.' '.$year);

	$header = '"Rg","Nom","Prénom","Cursus","Ues","Moy"';
	$rCompetence = "SELECT compId, compCode FROM Competence WHERE semId = ?"; //TODO DB request
	
	for ($competences as $comp)
	{
		$header .= ', "'. $comp['compCode']. '", "Bonus '. $comp['compCode'].'"';

		$rModule = "SELECT c.modCode, modCoef, modId FROM Module m 
					JOIN CompMod c ON m.modId=c.modId
					WHERE compId = ?"; //TODO DB request
		
		$competences['modules'] = $modules;
		
		// Iterating through the modules of the comp
		for ($compMods as $compMod)
		{
			$header .= ', "'. $compMod['modCode']. '"';
		}
	}

	$rStudents = "SELECT nom, prenom, parcours FROM Etudiant WHERE anneeId = ?"; //TODO DB request

	// Iterating through the students of the specified year
	for ($i = 0; i < count($students); $i++)
	{
		$studentInfo =
			($i+1)              .'", "'.
			$student['nom']     .'", "'.
			$student['prenom']  .'", "'.
			$student['parcours'].'"';

		$rBonus = "SELECT etdBonus FROM Etudiant WHERE etdId = ?";

		for ($competences as $comp)
		{
			//TODO DB requests
			$rAdmUEs = "SELECT getNbAdminComp(?, ?, ?) FROM AdmComp"; //studentId, yearId, semId
			$rMoyUe  = "SELECT getCompMoy    (?, ?, ?) FROM Moyenne"; //compId, studentId, yearId
			$studentInfo .= ', "'.$admUEs.'" ,"'.', "'.$moyUe.'" ,"'.$bonus.'"'; // TODO change color of the moyUe cell

			for ($comp['modules'] as $modComp)
			{
				$rMoy = "SELECT noteVal FROM Moyenne WHERE anneId = ? AND modId = ? AND etdId = ?"; //TODO DB request

				if (is_null($rMoy)) $moy = 'NR';
				$studentInfo .= ', "'.$moy.'"';
			}
		}

		echo $studentInfo;
		print($studentInfo);
	}
	
}

?>
