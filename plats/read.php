<?php 
require_once('../config/header.php');
headersAPI ('GET');

// on vérifie que la méthod utilisé est correcte
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    require_once('../models\Plats.php');
    // instanciation base de donnée
    $database = new Database();
    $db = $database->getConnexion();
    // instanciation plats
    $plats = new Plats($db);
    // recuperation statement
    $stmt = $plats->Read();
    // vérification réponse
    if($stmt->rowCount() > 0){
        // création tableau php et initialisation tableau associatif
        $tableauPlats = [];
        $tableauPlats['plats'] = [];
        // parcours des lignes du tableaux
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $platsAssos = [
                "id" => $id,
                "titre" => html_entity_decode($titre),
                "description" => html_entity_decode($description),
                "prix" => $prix ,
                "id_categorie" => $id_categorie,
            ];
            //ajout dans le tableau
            $tableauPlats['plats'][] = $platsAssos;
            
        }
        http_response_code(200);
        echo json_encode($tableauPlats);
    }
} else {
    http_response_code(405);
    echo json_encode(['message'=>"La méthode n'est pas autorisé"] ) ;
}

?>
