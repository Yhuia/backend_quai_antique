<?php
require_once('../config/header.php');
headersAPI ('PUT');


if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // on inclut le fichier de configuration de la base de données et le modèle pour les plats
    require_once('../models/Plats.php');

    // instanciation de la base de données et récupération de la connexion
    $database = new Database();
    $db = $database->getConnexion();

    // instanciation du modèle des plats avec la connexion à la base de données
    $plats = new Plats($db);

    // récupération des données postées - php://input est un fichier virtuel
    $data = json_decode(file_get_contents("php://input"));
    // vérification des données postées
    if (!empty($data->id) && !empty($data->titre) && !empty($data->description) && !empty($data->prix) && !empty($data->id_categorie)) {
        // affecter les données de la requête aux propriétés de la classe Plats
        $plats->id = $data->id;
        $plats->titre = $data->titre;
        $plats->description = $data->description;
        $plats->prix = $data->prix;
        $plats->id_categorie = $data->id_categorie;
    
        // créer le plat
        if ($plats->Update()) {
            // réponse HTTP - 201 created
            http_response_code(201);
            echo json_encode(array("message" => "Le plat a été mis à jour avec succès."));
        } else {
            // réponse HTTP - 503 service unavailable
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de modifier le plat."));
        }
    } else {
        // réponse HTTP - 400 bad request
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de modifier le plat. Données incomplètes."));
    }
} else{
    echo json_encode(array("message" => "Methode non autorisée"));
}
?>