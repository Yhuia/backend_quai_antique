<?php 
require_once('../config/header.php');
headersAPI ('POST');

// Vérifie la méthode utilisée
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Inclut le fichier de configuration de la base de données et le modèle pour les menus
    require_once('../config/database.php');
    require_once('../models/Menus.php');

    // Instancie la base de données et récupère la connexion
    $database = new Database();
    $db = $database->getConnexion();

    // Instancie le modèle des menus avec la connexion à la base de données
    $menus = new Menus($db);

    // Récupère les données postées
    $data = json_decode(file_get_contents("php://input"));

    // Vérifie les données postées
    if (!empty($data->titre)) {
        // Affecte les données de la requête aux propriétés de la classe Menus
        $menus->titre = $data->titre;

        // Crée le menu
        if ($menus->Create()) {
            // Réponse HTTP - 201 created
            http_response_code(201);
            echo json_encode(array("message" => "Le menu a été créé avec succès."));
        } else {
            // Réponse HTTP - 503 service unavailable
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de créer le menu."));
        }
    } else {
        // Réponse HTTP - 400 bad request
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de créer le menu. Données incomplètes."));
    }
}
