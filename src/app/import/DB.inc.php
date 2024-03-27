<?php
class DB
{

	private static $instance = null; //mémorisation de l'instance de DB pour appliquer le pattern Singleton
	private $connect=null; //connexion PDO à la base

	/************************************************************************/
	//	Constructeur gerant  la connexion à la base via PDO
	//	NB : il est non utilisable a l'exterieur de la classe DB
	/************************************************************************/	
	private function __construct(String $dbName, String $identifier, String $password)
	{
		$connStr = 'pgsql:host=woody port=5432 dbname='.$dbName; 
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

	/************************************************************************/
	//	Methode permettant d'obtenir un objet instance de DB
	//	NB : cet objet est unique pour l'exécution d'un même script PHP
	//	NB2: c'est une methode de classe.
	/************************************************************************/
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

	/************************************************************************/
	//	Methode permettant de fermer la connexion a la base de données
	/************************************************************************/


	/************************************************************************/
	//	Methode uniquement utilisable dans les méthodes de la class DB 
	//	permettant d'exécuter n'importe quelle requête SQL
	//	et renvoyant en résultat les tuples renvoyés par la requête
	//	sous forme d'un tableau d'objets
	//	param1 : texte de la requête à exécuter (éventuellement paramétrée)
	//	param2 : tableau des valeurs permettant d'instancier les paramètres de la requête
	//	NB : si la requête n'est pas paramétrée alors ce paramètre doit valoir null.
	//	param3 : nom de la classe devant être utilisée pour créer les objets qui vont
	//	représenter les différents tuples.
	//	NB : cette classe doit avoir des attributs qui portent le même que les attributs
	//	de la requête exécutée.
	//	ATTENTION : il doit y avoir autant de ? dans le texte de la requête
	//	que d'éléments dans le tableau passé en second paramètre.
	//	NB : si la requête ne renvoie aucun tuple alors la fonction renvoie un tableau vide
	/************************************************************************/
	private function execQuery($request,$tparam,$className)
	{
		$stmt = $this->connect->prepare($request);

		// Indicating the class type of the data collected from the request
		$stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $className); 
		//on exécute la requête
		if ($tparam != null)
			$stmt->execute($tparam);
		else
			$stmt->execute();

		$tab = array();
		$tuple = $stmt->fetch(); // getting the first tuple of the indicated Object type ($className)
		if ($tuple)
			while ($tuple != false)
			{
				$tab[]=$tuple;
				$tuple = $stmt->fetch();
			}
			
		return $tab;
	}

	/************************************************************************/
	//	Methode utilisable uniquement dans les méthodes de la classe DB
	//	permettant d'exécuter n'importe quel ordre SQL (update, delete ou insert)
	//	autre qu'une requête.
	//	Résultat : nombre de tuples affectés par l'exécution de l'ordre SQL
	//	param1 : texte de l'ordre SQL à exécuter (éventuellement paramétré)
	//	param2 : tableau des valeurs permettant d'instancier les paramètres de l'ordre SQL
	//	ATTENTION : il doit y avoir autant de ? dans le texte de la requête
	//	que d'éléments dans le tableau passé en second paramètre.
	/************************************************************************/
	private function execMaj($sqlRequest,$tparam) 
	{
		$stmt = $this->connect->prepare($sqlRequest);
		$res = $stmt->execute($tparam);
		return $stmt->rowCount();
	}

	/*****************************************************
	 * Functions that can be used in other PHP scripts
	 *****************************************************/
	
	// Selections
	public function getEtudiants()
	{
		$request = 'select * from Etudiants';
		return $this->execQuery($request,null,'Etudiant');
	}

	// Insertions
	public function insertSemestre(int $idSem)
	{
		$requete = 'insert into Semestre values(?)';
		$tparam = array($idSem);
		return $this->execMaj($requete,$tparam);
	}

	public function insertModule(array $tparam)
	{
		$requete = 'insert into Module values(?,?)';
		return $this->execMaj($requete,$tparam);
	}

	public function insertEtudiant(array $tparam)
	{
		$requete = 'insert into Etudiant values(?,?,?,?,?,?,?,?,?)';
		return $this->execMaj($requete,$tparam);
	}
	
	public function insertUtilisateur(array $tparam)
	{
		$requete = 'insert into Utilisateur values(?,?,?)';
		return $this->execMaj($requete,$tparam);
	}
		
	public function insertAnnee(array $tparam)
	{
		$requete = 'insert into Annee values(?,?)';
		return $this->execMaj($requete,$tparam);
	}


	
	public function insertCompetence(array $tparam)
	{
		$requete = 'insert into Competence values(?,?,?)';
		return $this->execMaj($requete,$tparam);
	}
		
	public function insertMoyenne(array $tparam)
	{
		$requete = 'insert into Moyenne values(?,?,?,?)';
		return $this->execMaj($requete,$tparam);
	}


		
	public function insertCompMod(array $tparam)
	{
		$requete = 'insert into CompMod values(?,?,?)';
		return $this->execMaj($requete,$tparam);
	}
		
	public function insertAdmComp(array $tparam)
	{
		$requete = 'insert into AdmComp values(?,?,?,?)';
		return $this->execMaj($requete,$tparam);
	}

	public function insertAdmAnne(array $tparam)
	{
		$requete = 'insert into AdmAnnee values(?,?,?)';
		return $this->execMaj($requete,$tparam);
	}
			
}
?>
