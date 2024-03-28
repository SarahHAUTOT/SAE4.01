<?php

// Connexion à la base de données
require 'DB.inc.php';

generateUsers();
generateCompMod();
generateYears();


/**********************************************************************/
/*                              USERS                                 */
/**********************************************************************/

function generateUsers()
{
    $db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");
    
    // Récupérer les utilisateur 
    $query     = "SELECT * FROM Utilisateur";
    $users     = $db->execQuery($query);


    // Générer le JSON
    $jsonData = json_encode($users, JSON_PRETTY_PRINT);
    // Écrire le JSON dans un fichier
    file_put_contents( '../../data/users.json', $jsonData);
    echo "Le fichier users.json a été créé avec succès.<br>";
}





/**********************************************************************/
/*                       COMPETENCES-MODULES                          */
/**********************************************************************/

function generateCompMod()
{
    $db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

    // Récupérer les utilisateur 
    $query       = "SELECT * FROM Competence ORDER BY compId";
    $competences = $db->execQuery($query);

    foreach ($competences as &$competence)
    {
        $competence['modules'] = [];

        $query       = "SELECT * FROM CompMod c JOIN Module m ON c.modId = m.modId WHERE compId =" . $competence['compid'];
        $modules     = $db->execQuery($query);

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
    file_put_contents( '../../data/comp.json', $jsonData);
    echo "Le fichier comp.json a été créé avec succès.<br>";
}





/**********************************************************************/
/*                            STUDENTS                                */
/**********************************************************************/

function generateYears()
{
    
    $db = DB::getInstance("hs220880", "hs220880", "SAHAU2004");

    // Récupérer les données de la table Annee
    $query     = "SELECT * FROM Annee";
    $years     = $db->execQuery($query);

    $query     = "SELECT * FROM Semestre";
    $semesters = $db->execQuery($query);


    //For each year 
    foreach ($years as &$year) {

        $year["semesters"] = [];

        foreach ($semesters as &$semester) 
        {
            // We get the students from this year and semester
            $query = "SELECT * FROM Etudiant WHERE etdId IN (SELECT etdId FROM AdmComp a JOIN Competence c ON a.compId = c.compId WHERE a.anneeId = ".$year["anneeid"] . " AND semId = ".$semester["semid"] . ")";
            $students = $db->execQuery($query);


            $semesterStudents = []; // Array to hold students for this semester

            // For each student
            foreach ($students as &$student) 
            {
                /* MODULES */
                // We get the grades from this year, semester, and student
                $query = "SELECT * FROM Moyenne m  JOIN Module mo ON mo.modId = m.modId WHERE m.anneeId = " . $year['anneeid'] . " AND etdId = " . $student["etdid"] . " AND m.modId IN (SELECT modId FROM CompMod a JOIN Competence c ON a.compId = c.compId WHERE semId = ".$semester["semid"] . ")";
                $grades = $db->execQuery($query);

                // Put them in student
                $student['modules'] = [];
                foreach ($grades as $grade) {
                    $student['modules'][] = [
                        'modId'   => $grade['modid'],
                        'modLib'  => $grade['modlib'],
                        'noteVal' => $grade['noteval']
                    ];
                }

                /* COMPETENCES */
                // We get the competences from this year, semester, and student
                $query = "SELECT * FROM AdmComp a JOIN Competence c ON a.compId = a.compId WHERE anneeId = " . $year['anneeid'] . " AND semId = ".$semester["semid"]. " AND etdId = ".$student["etdid"];
                $competences = $db->execQuery($query);

                // Put them in student
                $student['competences'] = [];
                foreach ($competences as $competence) {
                    $student['competences'][] = [
                        'compId'   => $competence['compid'],
                        'compLib'  => $competence['complib'],
                        'admi'     => $competence['admi']
                    ];
                }

                /* YEAR */
                // We get the admission by year
                $query = "SELECT * FROM AdmAnnee WHERE anneeId = " . $year['anneeid'] . " AND etdId = ".$student["etdid"];
                $admYear = $db->execQuery($query); // Assuming only one row is returned

                // Put it in student
                $student['admi'] = $admYear[0]['admi'];

                $semesterStudents[] = $student; // Add the student to the array of students for this semester
            }

            $semester['etd'] = $semesterStudents; // Assign the array of students to the semester directly
            $year['semesters'][] = $semester; // Add the semester to the array of semesters for this year
        }
    }


    // Générer le JSON
    $jsonData = json_encode($years, JSON_PRETTY_PRINT);
    // Écrire le JSON dans un fichier
    file_put_contents( '../../data/donnees.json', $jsonData);

    echo "Le fichier donnees.json a été créé avec succès.<br>";
}
?>
