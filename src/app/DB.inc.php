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
				

				//The student is already in the database
				if ($stmt->rowCount() > 0) {

					$sql = "UPDATE Etudiant SET etdciv = ?, etdabs = ?, etdnom = ?, etdgroupetd = ?, etdgroupetp = ?, etdbac = ?, etdbonus = ?, etdprenom = ?, etdparcours = ? WHERE etdId = ?";

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
						$student['parcours'],
						$student['id']
					);					

				} else {
					
					// Prepare the SQL statement
					$sql = "INSERT INTO Etudiant (etdid, etdciv, etdabs, etdnom, etdgroupetd, etdgroupetp, etdbac, etdbonus, etdprenom, etdparcours) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

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
						$student['parcours']
					);

					// Execute the query
				}



				$stmt = $this->connect->prepare($sql);
				$result = $stmt->execute($params);
				if ($result === false) {
					return "Error inserting student: " . $db->getError();
				}
			}

			// Respond to the client with a success message
			echo "Students inserted successfully";
		} else {
			// Respond to the client with an error message
			echo "No student data received";
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
				// Prepare the SQL statement
				$sql = "INSERT INTO Moyenne (noteVal, etdid, modid, anneeid) VALUES (?, ?, ?, ?)";

				// Bind parameters
				$params = array(
					$moyenne['moy'],
					$moyenne['etdId'],
					$moyenne['modId'],
					$max_annee_id
				);

				$stmt = $this->connect->prepare($sql);
				$result = $stmt->execute($params);
				if ($result === false) {
					return "Error inserting moyennes: " . $db->getError();
				}
			}

			// Respond to the client with a success message
			echo "Students inserted successfully";
		} else {
			// Respond to the client with an error message
			echo "No student data received";
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
            echo $db->insertStudents($postData['datas']);
            break;
        case 'insertMoyennes':
            // Appeler la méthode insertMoyennes avec les données des moyennes
            echo $db->insertMoyennes($postData['datas']);
            break;
        default:
            echo "Action invalide";
            break;
    }
} else {
    // Répondre au client avec un message d'erreur
    echo "Aucune action spécifiée";
}
?>
