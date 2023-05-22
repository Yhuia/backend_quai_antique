<?php 

require_once('../config/header.php');
headersAPI('PUT');

// Vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // On inclut le fichier de configuration de la base de données et le modèle pour les images
    require_once('../config/database.php');
    require_once('../models/Galerie.php');

    // Instanciation de la base de données et récupération de la connexion
    $database = new Database();
    $db = $database->getConnexion();

    // Instanciation du modèle des images avec la connexion à la base de données
    $image = new Image($db);

    // Récupération des données postées - php://input est un fichier virtuel
    $data = json_decode(file_get_contents("php://input"));
    // Vérification des données postées
    if (!empty($data->id) && !empty($data->titre) && !empty($data->url)) {
        // Affecte les données de la requête aux propriétés de la classe image
        $image->id = $data->id;
        $image->titre = $data->titre;
        $image->url = $data->url;
        // Met à jour l'image
        if ($image->Update()) {
            // Réponse HTTP - 200 OK
            http_response_code(200);
            echo json_encode(array("message" => "L'image a été mise à jour avec succès."));
        } else {
            // Réponse HTTP - 503 service unavailable
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de mettre à jour l'image."));
        }
    } else {
        // Réponse HTTP - 400 bad request
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de mettre à jour l'image. Données incomplètes."));
    }
} else {
    http_response_code(405);
    echo json_encode(['message'=>"La méthode n'est pas autorisée"] ) ;
}
