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












/**********************************************************************/
/*                              USERS                                 */
/**********************************************************************/


// Récupérer les utilisateur 
$query     = "SELECT * FROM Utilisateur";
$statement = $pdo->query($query);
$users     = $statement->fetchAll(PDO::FETCH_ASSOC);


// Générer le JSON
$jsonData = json_encode($users, JSON_PRETTY_PRINT);
// Écrire le JSON dans un fichier
file_put_contents( 'users.json', $jsonData);













/**********************************************************************/
/*                       COMPETENCES-MODULES                          */
/**********************************************************************/


// Récupérer les utilisateur 
$query       = "SELECT * FROM Competence ORDER BY compId";
$statement   = $pdo->query($query);
$competences = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($competences as &$competence)
{
    $competence['modules'] = [];

    $query       = "SELECT * FROM CompMod c JOIN Module m ON c.modId = m.modId WHERE compId =" . $competence['compid'];
    $statement   = $pdo->query($query);
    $modules     = $statement->fetchAll(PDO::FETCH_ASSOC);

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
file_put_contents( 'comp.json', $jsonData);












/**********************************************************************/
/*                            STUDENTS                                */
/**********************************************************************/



// Récupérer les données de la table Annee
$query     = "SELECT * FROM Annee";
$statement = $pdo->query($query);
$years     = $statement->fetchAll(PDO::FETCH_ASSOC);

$query     = "SELECT * FROM Semestre";
$statement = $pdo->query($query);
$semesters = $statement->fetchAll(PDO::FETCH_ASSOC);


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


// Générer le JSON
$jsonData = json_encode($years, JSON_PRETTY_PRINT);
// Écrire le JSON dans un fichier
file_put_contents( 'donnees.json', $jsonData);

echo "Les fichiers JSON a été créé avec succès.";

?>
