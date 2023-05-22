<?php

require_once('../config/header.php');
headersAPI('DELETE');

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    require_once('../models/Hours.php');
    $database = new Database();
    $db = $database->getConnexion();
    $hours = new Hours($db);

    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->id)) {
        $hours->id = $data->id;
        if ($hours->Delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "L'horaire a été supprimé avec succès."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de supprimer l'horaire."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de supprimer l'horaire. Données incomplètes."));
    }
} else {
    http_response_code(405);
    echo json_encode(['message'=>"La méthode n'est pas autorisée"] ) ;
}

?>