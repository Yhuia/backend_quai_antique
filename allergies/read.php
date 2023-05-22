<?php
require_once('../config/header.php');
headersAPI('GET');

// Vérifier que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once('../models/Allergies.php');
    // Instanciation de la base de données
    $database = new Database();
    $db = $database->getConnexion();
    // Instanciation de la classe Allergies
    $allergies = new Allergies($db);
    // Récupération du résultat
    $stmt = $allergies->Read();
    // Vérification du résultat
    if ($stmt->rowCount() > 0) {
        // Création d'un tableau PHP et initialisation d'un tableau associatif
        $tableauAllergies = [];
        $tableauAllergies['allergies'] = [];
        // Parcours des lignes du tableau
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $allergieAssoc = [
                "id" => $id,
                "titre" => html_entity_decode($titre)
            ];
            // Ajout dans le tableau
            $tableauAllergies['allergies'][] = $allergieAssoc;
        }
        http_response_code(200);
        echo json_encode($tableauAllergies);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => "La méthode n'est pas autorisée"]);
}
?>
