<?php 
require_once('../config/header.php');
headersAPI ('POST');

// on vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // on inclut le fichier de configuration de la base de données et le modèle pour les hours
    // require_once('../config/database.php');
    require_once('../models/Hours.php');

    // instanciation de la base de données et récupération de la connexion
    $database = new Database();
    $db = $database->getConnexion();

    // instanciation du modèle des hours avec la connexion à la base de données
    $hours = new Hours($db);

    // récupération des données postées - php://input est un fichier virtuel
    $donnees = json_decode(file_get_contents("php://input"));
    // vérification des données postées
    if (!empty($donnees->jour_de_la_semaine)) {
        // affecter les données de la requête aux propriétés de la classe hours
        $hours->jour_de_la_semaine = $donnees->jour_de_la_semaine;
        $hours->heure_midi_ouverture = !empty($donnees->heure_midi_ouverture) ?  $donnees->heure_midi_ouverture : NULL ;
        $hours->heure_midi_fermeture = !empty($donnees->heure_midi_fermeture) ?  $donnees->heure_midi_fermeture : NULL ;
        $hours->heure_soir_ouverture = !empty($donnees->heure_soir_ouverture) ?  $donnees->heure_soir_ouverture : NULL ;
        $hours->heure_soir_fermeture = !empty($donnees->heure_soir_fermeture) ?  $donnees->heure_soir_fermeture : NULL ;
        // // créer le horaire
        if ($hours->Create()) {
            // réponse HTTP - 201 created
            http_response_code(201);
            echo json_encode(array("message" => "L'horaire a été créé avec succès."));
        } else {
            // réponse HTTP - 503 service unavailable
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de créer le horaire."));
        }
    } else {
        // réponse HTTP - 400 bad request
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de créer le horaire. Données incomplètes."));
    }
} else {
    http_response_code(405);
    echo json_encode(['message'=>"La méthode n'est pas autorisée"] ) ;
}