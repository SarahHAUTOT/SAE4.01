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

    $year["semesters : "] = [];

    foreach ($semesters as &$semester) 
    {
        
        //We get the students from this year and semesters
        $query = "SELECT * FROM Etudiant WHERE etdId IN (SELECT etdId FROM AdmComp a JOIN Competence c ON a.compId = c.compId WHERE anneeId = ".$year["anneid"] . " AND semId = ".$semester["semid"] . ")";
        $statement = $pdo->query($query);
        $students = $statement->fetchAll(PDO::FETCH_ASSOC);

        $semester ["etd"] = [];
        //For each students
        foreach ($students as &$student) {

            /*                          */
            /*          GRADES          */
            /*                          */

            //We get the grades from this year and semester
            $query = "SELECT * FROM Etudiant e JOIN Moyenne m ON e.etdId = m.etdId WHERE m.anneId = " . $year['anneId'] . " AND etdId = " . $student;
            $statement = $pdo->query($query);
            $grades = $statement->fetchAll(PDO::FETCH_ASSOC);


            //Put them 
            $student['ressources'] = [];
            foreach ($grades as $grade) {
                if ($grade['etdid'] == $student['etdid']) {
                    $student['moy'][] = [
                        'noteval' => $grade['noteval'],
                        'modid' => $grade['modid']
                    ];
                }
            }

            //Admissions by competences
            $student['admComp'] = [];
            foreach ($admissionsByCompetence as $admComp) {
                
                echo $admComp['etdid']  . "<br>";

                if (isset($admComp['etdid']) && $admComp['etdid'] == $student['etdid']) {
                    $student['admComp'][] = [
                        'compId' => isset($admComp['compid']) ? $admComp['compid'] : null,
                        'anneId' => isset($admComp['anneid']) ? $admComp['anneid'] : null,
                        'admi' => isset($admComp['admi']) ? $admComp['admi'] : null
                    ];
                }
            }

            //Admissions by year
            $student['admYear'] = [];
            foreach ($admissionsByYear as $admYear) {
                
                echo $admYear['etdid']  . "<br>";

                if (isset($admYear['etdid']) && $admYear['etdid'] == $student['etdid']) {
                    $student['admYear'][] = [
                        'compId' => isset($admYear['compid']) ? $admYear['compid'] : null,
                        'anneId' => isset($admYear['anneid']) ? $admYear['anneid'] : null,
                        'admi' => isset($admYear['admi']) ? $admYear['admi'] : null
                    ];
                }
            } 


        }
    }


}



















// Créer un tableau final avec toutes les données
$data = [
    'Etudiants' => $students,
    // 'Competences' => $competences
];

// Générer le JSON
$jsonData = json_encode($data, JSON_PRETTY_PRINT);

// Écrire le JSON dans un fichier
$file = 'donnees.json';
file_put_contents($file, $jsonData);

echo "Le fichier JSON a été créé avec succès.";

?>
