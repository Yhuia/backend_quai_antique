<?php
require_once('../config/header.php');
headersAPI('GET');

// Vérification de la méthode utilisée
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once('../config/database.php');
    require_once('../models/Users.php');
    $database = new Database();
    $db = $database->getConnexion();
    
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['message' => "L'identifiant de l'utilisateur est requis."]);
        exit;
    }
    
    $user = new Users($db);
    // Récupération de l'identifiant de l'utilisateur
    $user->id = htmlspecialchars($_GET['id']);

    // Lecture des informations de l'utilisateur
    $userData = $user->ReadOne();

    // Vérification si l'utilisateur existe
    if ($userData) {
        $user_arr = array();
        $user_arr["users"] = array();
        
        // Récupération des informations de l'utilisateur
        $user_item = array(
            "id" => $userData['id'],
            "email" => $userData['email'],
            "mot_de_passe" => $userData['mot_de_passe'],
            "est_administrateur" => $userData['est_administrateur'],
            "nombre_convives_par_defaut" => $userData['nombre_convives_par_defaut'],
            "allergies" => $userData['allergies']
        );

        array_push($user_arr["users"], $user_item);

        // Envoi des informations de l'utilisateur en JSON
        http_response_code(200);
        echo json_encode($user_arr);
    } else {
        // L'utilisateur n'existe pas
        http_response_code(404);
        echo json_encode(['message' => "L'utilisateur n'existe pas."]);
    }
} else {
    // Méthode non autorisée
    http_response_code(405);
    echo json_encode(['message' => 'Méthode non autorisée.']);
}
