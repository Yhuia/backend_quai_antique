<?php
require_once('../config/header.php');
headersAPI('DELETE');

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    require_once('../models/Galerie.php');
    $database = new Database();
    $db = $database->getConnexion();
    $image = new Image($db);

    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->id)) {
        $image->id = $data->id;
        if ($image->Delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "L'image a été supprimée avec succès."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de supprimer l'image."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Impossible de supprimer l'image. Données incomplètes."));
    }
} else {
    http_response_code(405);
    echo json_encode(['message'=>"La méthode n'est pas autorisée"] ) ;
}
?>
