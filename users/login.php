<?php 
require_once('../config/header.php');
require_once('../config/security.php');
require_once('../classes/JWT.php');
headersAPI('POST');

// on vérifie que la méthode utilisée est correcte
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('../models/Users.php');
    // instanciation base de donnée
    $database = new Database();
    $db = $database->getConnexion();
    // instanciation utilisateur
    $users = new Users($db);
    // récupération des données
    $data = json_decode(file_get_contents("php://input"));
    // vérification des données
    if (!empty($data->email) && !empty($data->mot_de_passe)) {
        // attribution des données à l'utilisateur
        $users->email = $data->email;
        $users->mot_de_passe = $data->mot_de_passe;
        // vérification des informations de connexion
        if ($users->login($users->email,$users->mot_de_passe)) {
            http_response_code(200);
            // creation header token
            $header = [
                'alg' => "JWT",
                'typ' => "HS256",
            ];
            // création contenu token (payload)
            $payload = [
                'user' => $users->id,
                'role' => $users->est_administrateur,
                'email' => $users->email,
            ];
            $admin = $users->est_administrateur;
            $user = $users->id;
            $jwt = new JWT();
            $token = $jwt->generate($header,$payload,SECRETKEY);
            echo json_encode(['tokenConnect' => $token, 'admin' => $admin,'user' => $user,
        ]);

        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Connexion échouée.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Impossible de se connecter. Veuillez fournir un email et un mot de passe valides.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => "La méthode n'est pas autorisée"]);
}
?>
