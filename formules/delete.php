<?php
require_once('../config/header.php');
headersAPI ('DELETE');

// on vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    require_once('../models/Formules.php');

    $database = new Database();
    $db = $database->getConnexion();

    // instanciation du modèle des formules avec la connexion à la base de données
    $formules = new Formules($db);

    // récupération de l'id de la formule à supprimer
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $formules->id = $data->id;

        // supprimer la formule
        if ($formules->Delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "La formule a été supprimée avec succès."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de supprimer la formule."));
        }
    } else {
        // réponse HTTP - 400 bad request
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de supprimer la formule. Données incomplètes."));
    }
} else {
    echo json_encode(array("message" => "Methode non autorisée"));
}
