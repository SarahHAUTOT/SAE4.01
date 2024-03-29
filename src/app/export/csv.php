<?php

function export(String $year, String $type, String $semester)
{
	header('Content-type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=PV '.$type.' '.$semester.' '.$year);

	$header = '"Rg","Nom","Prénom","Cursus","Ues","Moy"';
	$rCompetence = "SELECT compId, compCode FROM Competence WHERE semId = ?";
	
	for ($competences as $comp)
	{
		$header .= ', "'. $comp['compCode']. '", "Bonus '. $comp['compCode'].'"';

		$rModule = "SELECT c.modCode, modCoef, modId FROM Module m JOIN CompMod c ON m.modId=c.modId WHERE compId = ?";
		
		$competences['modules'] = $modules;
		
		// Iterating through the modules of the comp
		for ($modules as $mod)
		{
			$header .= ', "'. $mod['modCode']. '"';
		}
	}


		// DB requests
		$rStudents   = "SELECT nom, prenom, parcours FROM Etudiant WHERE annee = ?";

		// TODO request students 
		for ($i = 0; i < count($students); $i++)
		{
			$studentInfo = .', "'.
				($i+1)              .'", "'.
				$student['nom']     .'", "'.
				$student['prenom']  .'", "'.
				$student['parcours'].'"';

			$rMoy   = "SELECT getMoyenne(?, ?, ?) FROM Moyenne"; //compId, studentId, yearId
			$rBonus = "SELECT etdBonus FROM Etudiant WHERE etdId = ?";

			$studentInfo .= ', "'.$moy.'" ,"'.$bonus.'"';

			for ($competences['modules'] as $modComp)
			{
				$rMoy = "SELECT noteVal FROM Moyenne WHERE anneId = ? AND modId = ? AND etdId = ?";

				if (is_null($rMoy)) $moy = 'NR';
				$studentInfo .= ', "'.$moy.'"';
			}
		}
	
}

?>
