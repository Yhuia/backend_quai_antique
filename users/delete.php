<?php
require_once('../config/header.php');
headersAPI ('DELETE');

// Vérification de la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Inclusion des fichiers de configuration et de modèle pour la base de données et les utilisateurs
    require_once('../config/database.php');
    require_once('../models/Users.php');

    // Instanciation de la base de données et récupération de la connexion
    $database = new Database();
    $db = $database->getConnexion();

    // Instanciation du modèle des utilisateurs avec la connexion à la base de données
    $users = new Users($db);

    // Récupération de l'ID de l'utilisateur à supprimer depuis les données envoyées en DELETE
    $data = json_decode(file_get_contents("php://input"));
    $users->id = $data->id;

    // Vérification que l'ID de l'utilisateur est présent
    if (!empty($users->id)) {
        // Suppression de l'utilisateur dans la base de données
        if ($users->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "L'utilisateur a été supprimé avec succès."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de supprimer l'utilisateur."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de supprimer l'utilisateur. L'identifiant est manquant."));
    }
}
?>
