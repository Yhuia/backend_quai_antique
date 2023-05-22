<?php
require_once('../config/header.php');
headersAPI ('PUT');

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // on inclut le fichier de configuration de la base de données et le modèle pour les menus
    require_once('../models/Menus.php');

    // instanciation de la base de données et récupération de la connexion
    $database = new Database();
    $db = $database->getConnexion();

    // instanciation du modèle des menus avec la connexion à la base de données
    $menus = new Menus($db);

    // récupération des données postées - php://input est un fichier virtuel
    $data = json_decode(file_get_contents("php://input"));

    // vérification des données postées
    if (!empty($data->id) && !empty($data->titre)) {
        // affecter les données de la requête aux propriétés de la classe Menus
        $menus->id = $data->id;
        $menus->titre = $data->titre;

        // mettre à jour le menu
        if ($menus->Update()) {
            // réponse HTTP - 201 created
            http_response_code(201);
            echo json_encode(array("message" => "Le menu a été mis à jour avec succès."));
        } else {
            // réponse HTTP - 503 service unavailable
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de modifier le menu."));
        }
    } else {
        // réponse HTTP - 400 bad request
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de modifier le menu. Données incomplètes."));
    }
} else{
    echo json_encode(array("message" => "Methode non autorisée"));
}
