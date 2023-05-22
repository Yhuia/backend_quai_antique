<?php 
require_once('../config/header.php');
headersAPI('GET');

// on vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once('../models/Users.php');
    // instanciation base de donnée
    $database = new Database();
    $db = $database->getConnexion();
    // instanciation utilisateur
    $users = new Users($db);
    // recuperation statement
    $stmt = $users->Read();
    // vérification réponse
    if($stmt->rowCount() > 0) {
        // création tableau php et initialisation tableau associatif
        $tableauUsers = [];
        $tableauUsers['users'] = [];
        // parcours des lignes du tableau
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $userAssos = [
                "id" => $id,
                "email" => html_entity_decode($email),
                "mot_de_passe" => $mot_de_passe,
                "est_administrateur" => $est_administrateur,
                "nombre_convives_par_defaut" => $nombre_convives_par_defaut,
                "allergies" => $allergies
            ];
            //ajout dans le tableau
            $tableauUsers['users'][] = $userAssos;
        }
        http_response_code(200);
        echo json_encode($tableauUsers);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Aucun utilisateur trouvé.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => "La méthode n'est pas autorisée"]);
}
?>
