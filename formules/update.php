<?php
require_once('../config/header.php');
headersAPI('PUT');

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // on inclut le fichier de configuration de la base de données et le modèle pour les formules
    require_once('../models/Formules.php');

    // instanciation de la base de données et récupération de la connexion
    $database = new Database();
    $db = $database->getConnexion();

    // instanciation du modèle des formules avec la connexion à la base de données
    $formules = new Formules($db);

    // récupération des données postées - php://input est un fichier virtuel
    $data = json_decode(file_get_contents("php://input"));
    // vérification des données postées
    if (!empty($data->id) && !empty($data->description) && !empty($data->prix)) {
        // affecter les données de la requête aux propriétés de la classe Formules
        $formules->id = $data->id;
        $formules->description = $data->description;
        $formules->prix = $data->prix;
        
    
        // mettre à jour la formule
        if ($formules->Update()) {
            // réponse HTTP - 201 created
            http_response_code(201);
            echo json_encode(array("message" => "La formule a été mise à jour avec succès."));
        } else {
            // réponse HTTP - 503 service unavailable
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de modifier la formule."));
        }
    } else {
        // réponse HTTP - 400 bad request
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de modifier la formule. Données incomplètes."));
    }
} else{
    echo json_encode(array("message" => "Méthode non autorisée"));
}
?>
