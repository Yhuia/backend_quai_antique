<?php

require_once('../config/header.php');
headersAPI('PATCH');

if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    // On inclut le fichier de configuration de la base de données et le modèle pour les utilisateurs
    require_once('../config/database.php');
    require_once('../models/Users.php');

    // Instanciation de la base de données et récupération de la connexion
    $database = new Database();
    $db = $database->getConnexion();

    // Instanciation du modèle des utilisateurs avec la connexion à la base de données
    $users = new Users($db);

    // Récupération des données envoyées par l'utilisateur
    $data = json_decode(file_get_contents("php://input"));

    // Vérification que l'identifiant de l'utilisateur et les allergies sont présents
    if (!empty($data->id) && !empty($data->allergies)) {
        // Affectation de l'ID de l'utilisateur à l'objet utilisateur
        $users->id = $data->id;

        // Ajout des allergies pour l'utilisateur
        $users->allergies = $data->allergies;

        // Mise à jour de l'utilisateur dans la base de données
        if ($users->Update()) {
            http_response_code(200);
            echo json_encode(array("message" => "Les allergies ont été ajoutées avec succès à l'utilisateur."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible d'ajouter les allergies à l'utilisateur."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Impossible d'ajouter les allergies à l'utilisateur. L'identifiant de l'utilisateur ou les allergies sont manquants."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée"));
}
