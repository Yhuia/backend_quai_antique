<?php 
require_once('../config/header.php');
headersAPI ('GET');

// on vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    require_once('../models/Formules.php');
    // instanciation de la base de données
    $database = new Database();
    $db = $database->getConnexion();
    // instanciation de la classe Formules
    $formules = new Formules($db);
    // récupération des données
    $stmt = $formules->Read();
    // vérification de la réponse
    if($stmt->rowCount() > 0){
        // création d'un tableau PHP et initialisation d'un tableau associatif
        $tableauFormules = [];
        $tableauFormules['formules'] = [];
        // parcours des lignes du tableau
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $formuleAssoc = [
                "id" => $id,
                "titre" => $titre,
                "description" => html_entity_decode($description),
                "prix" => $prix,
                "menus_id" => $menus_id
            ];
            // ajout dans le tableau
            $tableauFormules['formules'][] = $formuleAssoc;
        }
        http_response_code(200);
        echo json_encode($tableauFormules);
    }
} else {
    http_response_code(405);
    echo json_encode(['message'=>"La méthode n'est pas autorisée"]);
}
?>
