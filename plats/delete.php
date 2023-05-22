<?php
require_once('../config/header.php');
headersAPI ('DELETE');

// on vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    require_once('../models/Plats.php');

    $database = new Database();
    $db = $database->getConnexion();

    // instanciation du modèle des plats avec la connexion à la base de données
    $plats = new Plats($db);

    // récupération de l'id du plat à supprimer
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $plats->id = $data->id;

        // supprimer le plat
        if ($plats->Delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Le plat a été supprimé avec succès."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de supprimer le plat."));
        }
    } else {
        // réponse HTTP - 400 bad request
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de supprimer le plat. Données incomplètes."));
    }
} else {
    echo json_encode(array("message" => "Methode non autorisée"));
}

?>