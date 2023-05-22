<?php 
require_once('../config/header.php');
headersAPI ('GET');

// on vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    require_once('../models/Hours.php');
    // instanciation base de donnée
    $database = new Database();
    $db = $database->getConnexion();
    // instanciation horaires
    $horaire = new Hours($db);
    // récupération statement
    $stmt = $horaire->Read();
    // vérification réponse
    if($stmt->rowCount() > 0){
        // création tableau php et initialisation tableau associatif
        $tableauHoraires = [];
        $tableauHoraires['horaires'] = [];
        // parcours des lignes du tableau
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $horaireAssos = [
                "id" => $id,
                "jour_de_la_semaine" => $jour_de_la_semaine,
                "heure_midi_ouverture" => $heure_midi_ouverture,
                "heure_midi_fermeture" => $heure_midi_fermeture,
                "heure_soir_ouverture" => $heure_soir_ouverture,
                "heure_soir_fermeture" => $heure_soir_fermeture
            ];
            // ajout dans le tableau
            $tableauHoraires['horaires'][] = $horaireAssos;
        }
        http_response_code(200);
        echo json_encode($tableauHoraires);
    }
} else {
    http_response_code(405);
    echo json_encode(['message'=>"La méthode n'est pas autorisée"] ) ;
}

?>
