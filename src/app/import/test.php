
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