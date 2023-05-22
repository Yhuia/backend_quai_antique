<?php 
require_once('../config/header.php');
headersAPI ('GET');

// Vérification de la méthode HTTP utilisée
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    require_once('../models/Galerie.php');
    // Instanciation de la base de données
    $database = new Database();
    $db = $database->getConnexion();
    // Instanciation de la classe Image
    $image = new Image($db);
    // Récupération de la requête SQL
    $stmt = $image->Read();
    // Vérification de la réponse
    if($stmt->rowCount() > 0){
        // Création d'un tableau PHP et initialisation d'un tableau associatif
        $tableauImage = [];
        $tableauImage['images'] = [];
        // Parcours des lignes de la réponse
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $imageAssoc = [
                "id" => $id,
                "titre" => html_entity_decode($titre),
                "url" => html_entity_decode($url)
            ];
            // Ajout dans le tableau
            $tableauImage['images'][] = $imageAssoc;
        }
        http_response_code(200);
        echo json_encode($tableauImage);
    }
} else {
    // Méthode HTTP non autorisée
    http_response_code(405);
    echo json_encode(['message'=>"La méthode n'est pas autorisée"]);
}

?>
