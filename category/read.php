<?php 
require_once('../config/header.php');
headersAPI ('GET');

// on vérifie que la méthod utilisé est correcte
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    require_once('../models\Category.php');
    // instanciation base de donnée
    $database = new Database();
    $db = $database->getConnexion();
    // instanciation category
    $category = new Category($db);
    // recuperation statement
    $stmt = $category->Read();
    // vérification réponse
    if($stmt->rowCount() > 0){
        // création tableau php et initialisation tableau associatif
        $tableaucategory = [];
        $tableaucategory['category'] = [];
        // parcours des lignes du tableaux
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $categoryAssos = [
                "id" => $id,
                "nom" => html_entity_decode($nom)
            ];
            //ajout dans le tableau
            $tableaucategory['category'][] = $categoryAssos;
            
        }
        http_response_code(200);
        echo json_encode($tableaucategory);
    }
} else {
    http_response_code(405);
    echo json_encode(['message'=>"La méthode n'est pas autorisé"] ) ;
}

?>
