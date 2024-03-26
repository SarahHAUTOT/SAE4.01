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

// Fonction pour récupérer les données de chaque table
function fetchTableData($pdo, $tableName) {
    $query = "SELECT * FROM $tableName";
    $statement = $pdo->query($query);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// Tables à inclure dans le fichier JSON avec leurs clés étrangères
$tables = [
    'Module' => ['compId'],
    'Semestre' => ['semId'],
    'Etudiant' => [],
    'Utilisateur' => [],
    'Competence' => ['semId'],
    'Moyenne' => ['etdId', 'modId'],
    'Annee' => [],
    'CompMod' => ['compId', 'modId'],
    'AdmComp' => ['etdId', 'compId', 'anneId'],
    'AdmAnnee' => ['etdId', 'anneId']
];

// Tableaux pour stocker les données de chaque table
$tablesData = [];

// Récupération des données de chaque table
foreach ($tables as $tableName => $foreignKeys) {
    $tableData = fetchTableData($pdo, $tableName);

    // Vérifier les clés étrangères et remplacer les identifiants par les données correspondantes
    foreach ($tableData as &$row) {
        foreach ($foreignKeys as $foreignKey) {
            $foreignTableName = substr($foreignKey, 0, -2); // Enlever le suffixe "Id"
            if (array_key_exists($foreignKey, $row)) {
                $foreignKeyValue = $row[$foreignKey];
                $relatedRow = findRelatedRow($tablesData[$foreignTableName], $foreignKeyValue);
                if ($relatedRow !== null) {
                    $row[$foreignTableName] = $relatedRow;
                }
            }
        }
    }

    $tablesData[$tableName] = $tableData;
}

// Fonction pour trouver la ligne liée dans une table
function findRelatedRow($tableData, $id) {
    foreach ($tableData as $row) {
        if ($row['id'] == $id) {
            return $row;
        }
    }
    return null;
}

// Écriture des données dans un fichier JSON
$jsonFileName = 'donnees.json';
$jsonData = json_encode($tablesData, JSON_PRETTY_PRINT);

if (file_put_contents($jsonFileName, $jsonData)) {
    echo "Le fichier JSON a été créé avec succès.";
} else {
    echo "Erreur lors de la création du fichier JSON.";
}

?>
