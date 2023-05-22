<?php 
require_once('../config/header.php');
headersAPI ('GET');

// on vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    require_once('../models/Menus.php');
    // instanciation base de donnée
    $database = new Database();
    $db = $database->getConnexion();
    // instanciation menu
    $menu = new Menus($db);
    // recuperation statement
    $stmt = $menu->Read();
    // vérification réponse
    if($stmt->rowCount() > 0){
        // création tableau php et initialisation tableau associatif
        $tableauMenu = [];
        $tableauMenu['menu'] = [];
        // parcours des lignes du tableaux
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $menuAssos = [
                "id" => $id,
                "titre" => html_entity_decode($titre)
            ];
            //ajout dans le tableau
            $tableauMenu['menu'][] = $menuAssos;
            
        }
        http_response_code(200);
        echo json_encode($tableauMenu);
    }
} else {
    http_response_code(405);
    echo json_encode(['message'=>"La méthode n'est pas autorisée"] ) ;
}

?>
