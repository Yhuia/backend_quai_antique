<?php
require_once('../config/header.php');
headersAPI ('POST');

// on vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // on inclut le fichier de configuration de la base de données et le modèle pour les utilisateurs
    require_once('../config/database.php');
    require_once('../models/Users.php');

    // instanciation de la base de données et récupération de la connexion
    $database = new Database();
    $db = $database->getConnexion();

    // instanciation du modèle des utilisateurs avec la connexion à la base de données
    $utilisateurs = new Users($db);

    // on récupère les données envoyées en POST
    $donnees = json_decode(file_get_contents("php://input"));

    // on vérifie que les données sont valides
    if (!empty($donnees->email) && !empty($donnees->mot_de_passe)) {
        // on attribue les valeurs des propriétés de l'utilisateur
        $utilisateurs->email = $donnees->email;
        // sécurisation du mot de passe
        $hash = password_hash($donnees->mot_de_passe, PASSWORD_DEFAULT);
        $utilisateurs->mot_de_passe = $hash ;
        $utilisateurs->est_administrateur = !empty($donnees->est_administrateur) ? $donnees->est_administrateur : 0; 
        $utilisateurs->nombre_convives_par_defaut = !empty($donnees->nombre_convives_par_defaut) ? $donnees->nombre_convives_par_defaut : null;
        $utilisateurs->allergies = !empty($donnees->allergies) ? $donnees->allergies : null;
        if ($utilisateurs->emailExists($utilisateurs->email)) {
            // on envoie une réponse HTTP 400 (Bad Request) pour indiquer que l'e-mail existe déjà
            http_response_code(400);
            echo json_encode(array("message" => "Impossible de créer l'utilisateur. L'e-mail existe déjà en base de données."));
        } 
        // on crée l'utilisateur dans la base de données
        else if ($utilisateurs->Create()) {
            // on envoie une réponse HTTP 201 (Created) pour indiquer que la ressource a été créée avec succès
            http_response_code(201);
            echo json_encode(array("message" => "L'utilisateur a été créé."));
        } else {
            // on envoie une réponse HTTP 503 (Service Unavailable) en cas d'erreur lors de la création de l'utilisateur
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de créer l'utilisateur."));
        }
    } else {
        // on envoie une réponse HTTP 400 (Bad Request) si les données envoyées ne sont pas valides
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de créer l'utilisateur. Les données sont incomplètes."));
    }
} else{
    echo json_encode(array("message" => "Methode non autorisée"));
}

?>