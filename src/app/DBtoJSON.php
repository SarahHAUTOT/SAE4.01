<?php

// Connexion à la base de données
$dsn = 'pgsql:host=127.0.0.1;port=5432;dbname=hs220880';
$username = 'hs220880';
$password = 'SAHAU2004';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Récupérer les données de la table Annee
$query     = "SELECT * FROM Annee";
$statement = $pdo->query($query);
$years     = $statement->fetchAll(PDO::FETCH_ASSOC);

$query     = "SELECT * FROM Semestre";
$statement = $pdo->query($query);
$semesters = $statement->fetchAll(PDO::FETCH_ASSOC);


/**********************************************************************/
/*                           ETUDIANTES                               */
/**********************************************************************/

//For each year 
foreach ($years as &$year) {

    $year["semesters"] = [];

    foreach ($semesters as &$semester) 
    {
        
        //We get the students from this year and semesters
        $query = "SELECT * FROM Etudiant WHERE etdId IN (SELECT etdId FROM AdmComp a JOIN Competence c ON a.compId = c.compId WHERE a.anneeId = ".$year["anneeid"] . " AND semId = ".$semester["semid"] . ")";
        $statement = $pdo->query($query);
        $students = $statement->fetchAll(PDO::FETCH_ASSOC);

        $semester ["etd"] = [];

        //For each students
        foreach ($students as &$student) 
        {

            /*                          */
            /*         MODULES          */
            /*                          */

            //We get the grades from this year and semester and student
            $query = "SELECT * FROM Moyenne m  JOIN Module mo ON mo.modId = m.modId WHERE m.anneeId = " . $year['anneeid'] . " AND etdId = " . $student["etdid"] . " AND m.modId IN (SELECT modId FROM CompMod a JOIN Competence c ON a.compId = c.compId WHERE semId = ".$semester["semid"] . ")";
            $statement = $pdo->query($query);
            $grades = $statement->fetchAll(PDO::FETCH_ASSOC);


            //Put them in student
            $student['modules'] = [];
            foreach ($grades as $grade) {
                $student['modules'][] = [
                    'modId'   => $grade['modid'],
                    'modLib'  => $grade['modlib'],
                    'noteVal' => $grade['noteval']
                ];
            }




            /*                          */
            /*       COMPETENCES        */
            /*                          */

            //We get the competence from this year and semester and student
            $query = "SELECT * FROM AdmComp a JOIN Competence c ON a.compId = a.compId WHERE anneeId = " . $year['anneeid'] . " AND semId = ".$semester["semid"]. " AND etdId = ".$student["etdid"];
            $statement = $pdo->query($query);
            $competences = $statement->fetchAll(PDO::FETCH_ASSOC);


            //Put them in student
            $student['competences'] = [];
            foreach ($competences as $competence) {
                $student['competences'][] = [
                    'compId'   => $competence['compid'],
                    'compLib'  => $competence['complib'],
                    'admi'     => $competence['admi']
                ];
            }




            /*                   */
            /*       YEAR        */
            /*                   */

            //We get the admission by year
            $query = "SELECT * FROM AdmAnnee WHERE anneeId = " . $year['anneeid'] . " AND etdId = ".$student["etdid"];
            $statement = $pdo->query($query);
            $admYear = $statement->fetchAll(PDO::FETCH_ASSOC);
            $admYear = $admYear[0];

            //Put them in student
            $student['admission'] = [
                    'admi'     => $admYear['admi']
                ];

                
            $semester['etd'][] = [$student];
        }

            
        $year['semesters'][] = [$semester];


    }
}






















// Créer un tableau final avec toutes les données
$data = [
    'Years' => $years,
];

// Générer le JSON
$jsonData = json_encode($data, JSON_PRETTY_PRINT);

// Écrire le JSON dans un fichier
$file = 'donnees.json';
file_put_contents($file, $jsonData);

echo "Le fichier JSON a été créé avec succès.";

?>
