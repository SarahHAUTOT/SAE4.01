<?php

class DB
{
	private static $instance = null; // to make sure that only one DB Object exist
	private $connect=null; // database connexion variable

	private function __construct(String $dbName, String $identifier, String $password)
	{
		$connStr = 'pgsql:host=127.0.0.1 port=5432 dbname='.$dbName; 
		try 
		{
			// Connection with the base
			$this->connect = new PDO($connStr, $identifier, $password);
		
			$this->connect->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); 
			$this->connect->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION); 
		}
		catch (PDOException $e)
		{
			echo "probleme de connexion :".$e->getMessage();
			return null;    
		}
	}

	
	
	public static function getInstance(String $dbName, String $identifier, String $password)
	{
		if (is_null(self::$instance))
		{
			try
			{ 
				self::$instance = new DB($dbName, $identifier, $password); 
			} 
			catch (PDOException $e) { echo $e; }
		}

		$obj = self::$instance;

		if (($obj->connect) == null)
			self::$instance=null;

		return self::$instance;
	}

	
	
	public function close()
	{
		$this->connect = null;
	}



	public function execQuery($query)
	{
		$statement   = $this->connect->query($query);

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}


	public function execMaj($sqlRequest,$tparam) 
	{
		$stmt = $this->connect->prepare($sqlRequest);
		$res = $stmt->execute($tparam);
		return $stmt->rowCount();
	}



	public function insertStudents($students)
	{
		$postData = json_decode(file_get_contents("php://input"), true);


		// Check if data exists and contains students
		if (!empty($students)) {
			// For each student in data
			foreach ($students as $student) {

				$sql = "SELECT * FROM Etudiant WHERE etdId = ?";
				$param = array ($student['id']);

				$stmt = $this->connect->prepare($sql);
				$res = $stmt->execute($param);
				

				//The data is already in the database
				if ($stmt->rowCount() > 0) {

					$sql = "UPDATE Etudiant SET etdciv = ?, etdabs = ?, etdnom = ?, etdgroupetd = ?, etdgroupetp = ?, etdbac = ?, etdbonus = ?, etdprenom = ?, etdCursus = ? WHERE etdId = ?";

					// Bind parameters
					$params = array(
						$student['civ'],
						$student['abs'],
						$student['nom'],
						$student['td'],
						$student['tp'],
						$student['bac'],
						$student['bonus'],
						$student['prenom'],
						$student['cursus'],
						$student['id']
					);					

				} else {
					
					// Prepare the SQL statement
					$sql = "INSERT INTO Etudiant (etdid, etdciv, etdabs, etdnom, etdgroupetd, etdgroupetp, etdbac, etdbonus, etdprenom, etdCursus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

					// Bind parameters
					$params = array(
						$student['id'],
						$student['civ'],
						$student['abs'],
						$student['nom'],
						$student['td'],
						$student['tp'],
						$student['bac'],
						$student['bonus'],
						$student['prenom'],
						$student['cursus']
					);

					// Execute the query
				}



				$stmt = $this->connect->prepare($sql);
				$result = $stmt->execute($params);
				if ($result === false) {
					return "Error inserting student: " . $db->getError();
				}
			}

		} else {
		}
	}	


	public function insertMoyennes($moyennes)
	{
		$postData = json_decode(file_get_contents("php://input"), true);

		$sql = "SELECT MAX(anneeId) AS max_annee_id FROM Annee";

		$stmt = $this->connect->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		$max_annee_id = $result['max_annee_id'];

		// Check if data exists and contains students
		if (!empty($moyennes)) {
			// For each student in data
			foreach ($moyennes as $moyenne) 
			{
				$sql = "SELECT * FROM Moyenne WHERE etdId = ? AND modId = ? AND anneeId = ?";
				$param = array ($moyenne['etdId'],$moyenne['modId'],$max_annee_id);

				$stmt = $this->connect->prepare($sql);
				$res = $stmt->execute($param);

				//The data is already in the database
				if ($stmt->rowCount() > 0) {
					// Prepare the SQL statement
					$sql = "UPDATE Moyenne SET noteVal = ? WHERE etdId = ? AND modId = ? AND anneeId = ?";

					// Bind parameters
					$params = array(
						$moyenne['moy'],
						$moyenne['etdId'],
						$moyenne['modId'],
						$max_annee_id
					);
					
				}
				else
				{
					// Prepare the SQL statement
					$sql = "INSERT INTO Moyenne (noteVal, etdid, modid, anneeid) VALUES (?, ?, ?, ?)";

					// Bind parameters
					$params = array(
						$moyenne['moy'],
						$moyenne['etdId'],
						$moyenne['modId'],
						$max_annee_id
					);
				}

				$stmt = $this->connect->prepare($sql);
				$result = $stmt->execute($params);
				if ($result === false) {
					return "Error updating moyennes: " . $db->getError();
				}
			}
		} else {
		}
	}


	public function insertCompetences($competences)
	{
		$postData = json_decode(file_get_contents("php://input"), true);

		foreach ($competences as $comp) 
		{
			$sql = "SELECT * FROM Competence WHERE compId = ?";
			$param = array ($comp['compId']);

			$stmt = $this->connect->prepare($sql);
			$res = $stmt->execute($param);

			//The data is already in the database
			if ($stmt->rowCount() > 0) {
				// Prepare the SQL statement
				$sql = "UPDATE Competence SET compCode = ?, compLib = ?, semId = ? WHERE compId = ?";

				// Bind parameters
				$params = array(
					$comp['compCode'],
					$comp['compLib'],
					$comp['semId'],
					$comp['compId']
				);
			}
			else
			{
				// Prepare the SQL statement
				$sql = "INSERT INTO Competence (compId, compCode, compLib, semId) VALUES (?, ?, ?, ?)";

				// Bind parameters
				$params = array(
					$comp['compId'],
					$comp['compCode'],
					$comp['compLib'],
					$comp['semId']
				);
			}

			$stmt = $this->connect->prepare($sql);
			$result = $stmt->execute($params);
			
			if ($result === false) return "Error inserting Competence: " . $db->getError();
		}
	}


	public function insertModules($modules)
	{
		$postData = json_decode(file_get_contents("php://input"), true);

		foreach ($modules as $mod) 
		{

			$sql = "SELECT * FROM Module WHERE modId = ?";
			$param = array ($mod['modId']);

			$stmt = $this->connect->prepare($sql);
			$res = $stmt->execute($param);

			//The data is already in the database
			if ($stmt->rowCount() > 0) {
				// Prepare the SQL statement
				$sql = "UPDATE Module SET modCode = ?, modCat = ?, modLib = ? WHERE modId = ?";

				// Bind parameters
				$params = array(
					$mod['modCode'],
					$mod['modCat'],
					$mod['modLib'],
					$mod['modId']
				);
			}
			else
			{
				// Prepare the SQL statement
				$sql = "INSERT INTO Module (modId, modCode, modCat, modLib) VALUES (?, ?, ?, ?)";

				// Bind parameters
				$params = array(
					$mod['modId'],
					$mod['modCode'],
					$mod['modCat'],
					$mod['modLib']
				);
			}

			$stmt = $this->connect->prepare($sql);
			$result = $stmt->execute($params);
			
			if ($result === false) return "Error inserting Modules: " . $db->getError();
		}
	}

	


	public function insertAnnee($anneLib)
	{
		$postData = json_decode(file_get_contents("php://input"), true);

		foreach ($anneLib as $anneLibMaisMieuxApparamentPutainDeMerde) 
		{
			
				// Prepare the SQL statement
				$sql = "INSERT INTO Annee (annelib) VALUES (?)";

				// Bind parameters
				$params = array(
					$anneLibMaisMieuxApparamentPutainDeMerde
				);

			$stmt = $this->connect->prepare($sql);
			$result = $stmt->execute($params);
		}

	}
	
	public function insertAdmComps($admComps)
	{
		$postData = json_decode(file_get_contents("php://input"), true);

		foreach ($$admComps as $admc) 
		{
			$sql = "SELECT * FROM AdmComp WHERE anneeId = ? AND compId = ? AND etdId = ?";
			$param = array ($admc['anneeId'],$admc['compId'], $admc['etdId']);

			$stmt = $this->connect->prepare($sql);
			$res = $stmt->execute($param);

			//The data is already in the database
			if ($stmt->rowCount() > 0)
			{
				// Prepare the SQL statement
				$sql = "UPDATE AdmComp SET admi = ? WHERE etdId = ? AND compId = ? AND anneeId = ?";

				// Bind parameters
				$params = array(
					$admc['admi'],
					$admc['etdId'],
					$admc['compId'],
					$admc['anneeId']
				);
			}
			else
			{
				// Prepare the SQL statement
				$sql = "INSERT INTO AdmComp (etdId, compId, anneeId, admi) VALUES (?, ?, ?, ?)";

				// Bind parameters
				$params = array(
					$admc['etdId'],
					$admc['compId'],
					$admc['anneeId'],
					$admc['admi']
				);
			}

			$stmt = $this->connect->prepare($sql);
			$result = $stmt->execute($params);
			
			if ($result === false) return "Error inserting CompMod: " . $db->getError();
		}
	}

	public function insertCompMods($compMods)
	{
		$postData = json_decode(file_get_contents("php://input"), true);

		foreach ($compMods as $compMod) 
		{
			$sql = "SELECT * FROM CompMod WHERE modId = ? AND compId = ?";
			$param = array ($compMod['modId'],$compMod['compId']);

			$stmt = $this->connect->prepare($sql);
			$res = $stmt->execute($param);

			//The data is already in the database
			if ($stmt->rowCount() > 0) {
				// Prepare the SQL statement
				$sql = "UPDATE CompMod SET modCoef = ? WHERE modId = ? AND compId = ?";

				// Bind parameters
				$params = array(
					$compMod['modCoef'],
					$compMod['modId'],
					$compMod['compId']
				);
			}
			else
			{
				// Prepare the SQL statement
				$sql = "INSERT INTO CompMod (compId, modId, modCoef) VALUES (?, ?, ?)";

				// Bind parameters
				$params = array(
					$compMod['compId'],
					$compMod['modId'],
					$compMod['modCoef']
				);
			}

			$stmt = $this->connect->prepare($sql);
			$result = $stmt->execute($params);
			
			if ($result === false) return "Error inserting CompMod: " . $db->getError();
		}
	}
}


$postData = json_decode(file_get_contents("php://input"), true);

// Vérifiez si une action est spécifiée
if (!empty($postData['action'])) {
	$action = $postData['action'];

	// Créer une nouvelle instance de la classe DB
	$db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

	// Gérer les différentes actions
	switch ($action) {
		case 'insertStudents':
			// Appeler la méthode insertStudents avec les données d'étudiants
			$db->insertStudents($postData['datas']);
			break;
		
		case 'insertAnnee':
			// Appeler la méthode insertAnnee avec les données d'étudiants
			$db->insertAnnee($postData['datas']);
			break;
		
		case 'insertMoyennes':
			// Appeler la méthode insertMoyennes avec les données des moyennes
			$db->insertMoyennes($postData['datas']);
			break;

		case 'insertCompetences':
			// Appeler la méthode insertCompetences avec les données des moyennes
			$db->insertCompetences($postData['datas']);
			break;

		case 'insertModules':
			// Appeler la méthode insertModules avec les données des moyennes
			$db->insertModules($postData['datas']);
			break;

		case 'insertCompMods':
			// Appeler la méthode insertCompMods avec les données des moyennes
			$db->insertCompMods($postData['datas']);
			break;

		case 'insertAdmComps':
			// Appeler la méthode insertAdmComps avec les données des moyennes
			$db->insertAdmComps($postData['datas']);
		break;

		default:
			break;
	}
} else {
	// Répondre au client avec un message d'erreur
}
?>
