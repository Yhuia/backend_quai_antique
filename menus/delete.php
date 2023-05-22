<?php
require_once('../config/header.php');
headersAPI ('DELETE');

// on vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    require_once('../models/Menus.php');

    $database = new Database();
    $db = $database->getConnexion();

    // instanciation du modèle des menus avec la connexion à la base de données
    $menus = new Menus($db);

    // récupération de l'id du menu à supprimer
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $menus->id = $data->id;

        // supprimer le menu
        if ($menus->Delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Le menu a été supprimé avec succès."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de supprimer le menu."));
        }
    } else {
        // réponse HTTP - 400 bad request
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de supprimer le menu. Données incomplètes."));
    }
} else {
    echo json_encode(array("message" => "Methode non autorisée"));
}
