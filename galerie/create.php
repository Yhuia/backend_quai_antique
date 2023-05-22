<?php
 
require_once('../config/header.php');
require_once('./method.php');
headersAPI('POST');

// on vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // on inclut le fichier de configuration de la base de données et le modèle pour les images
    // require_once('../config/database.php');
    require_once('../models/Galerie.php');

    // instanciation de la base de données et récupération de la connexion
    $database = new Database();
    $db = $database->getConnexion();

    // instanciation du modèle des images avec la connexion à la base de données
    $image = new Image($db);

    // récupération des données postées - php://input est un fichier virtuel
    $donnees = json_decode(file_get_contents("php://input"));
    // vérification des données postées
    if (!empty($donnees->titre) && !empty($donnees->url)) {
        $filename = $_FILES['url']['name'];
        // Emplacement temporaire du fichier sur le serveur
        $tmp_name = $_FILES['url']['tmp_name'];
        // Destination du fichier
        $destination = 'public/' . $filename;
        // affecter les données de la requête aux propriétés de la classe image
        if (move_uploaded_file($tmp_name, $destination)) {
            $image->titre = $donnees->titre;
            $image->url = $donnees->url;
        }


        // créer l'image
        if ($image->Create()) {
            // réponse HTTP - 201 created
            http_response_code(201);
            echo json_encode(array("message" => "L'image a été créée avec succès."));
        } else {
            // réponse HTTP - 503 service unavailable
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de créer l'image."));
        }
    } else {
        // réponse HTTP - 400 bad request
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de créer l'image. Données incomplètes."));
    }
} else {
    http_response_code(405);
    echo json_encode(['message'=>"La méthode n'est pas autorisée"] ) ;
}


   


   
