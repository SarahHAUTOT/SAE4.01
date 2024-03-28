<?php

class DB
{

	private static $instance = null; // to make sure that only one DB Object exist
	private $connect=null; // database connexion variable

	/************************************************************************/
	//	Constructor that manage the connection to the database
	//	NB : You cannot use it outside of this class
	/************************************************************************/	
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

	/************************************/
	// Close the connection to database
	/*************************************/
	public function close()
	{
		$this->connect = null;
	}

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
	public function execQuery($query)
	{
		$statement   = $this->connect->query($query);

		return $statement->fetchAll(PDO::FETCH_ASSOC);
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
			
}
?>
