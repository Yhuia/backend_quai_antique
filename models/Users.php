<?php 
require_once('../config\database.php');

class Users {
    // Connexion à la table
    private $connexion;
    private $table = "utilisateurs";
    
    // Informations de la table utilisateurs
    public $id;
    public $email;
    public $mot_de_passe;
    // si 0 alors il n'est pas admin, si 1 alors il l'est
    public $est_administrateur = 0;
    public $nombre_convives_par_defaut;
    public $allergies = [];
    public function __construct($db) {
        $this->connexion = $db;
    }

    public function Read() {
        // Prépare la requête SQL
        $sql = "SELECT * FROM " . $this->table;
        // Prépare la requête pour exécution
        $query = $this->connexion->prepare($sql);
        // Exécute la requête
        $query->execute();
    
        return $query; 
    }

    public function ReadOne()
{
    // Prépare la requête SQL pour récupérer les informations utilisateur
    $sqlUser = "SELECT id, email, mot_de_passe, est_administrateur, nombre_convives_par_defaut FROM utilisateurs WHERE id = :id";
    $queryUser = $this->connexion->prepare($sqlUser);
    
    // Nettoie les données
    $this->id = htmlspecialchars(strip_tags($this->id));
    
    // Bind la valeur de l'id
    $queryUser->bindParam(":id", $this->id);
    
    // Exécute la requête pour récupérer les informations utilisateur
    $queryUser->execute();
    
    // Récupère le résultat sous forme de tableau associatif
    $userData = $queryUser->fetch(PDO::FETCH_ASSOC);
    
    // Vérifie si l'utilisateur existe
    if ($userData) {
        // Prépare la requête SQL pour récupérer les allergies de l'utilisateur
        $sqlAllergies = "SELECT a.titre FROM allergies_utilisateurs au INNER JOIN allergies a ON au.allergie_id = a.id WHERE au.utilisateur_id = :id";
        $queryAllergies = $this->connexion->prepare($sqlAllergies);
        
        // Bind la valeur de l'id
        $queryAllergies->bindParam(":id", $this->id);
        
        // Exécute la requête pour récupérer les allergies de l'utilisateur
        $queryAllergies->execute();
        
        // Récupère les allergies sous forme de tableau associatif
        $allergiesData = $queryAllergies->fetchAll(PDO::FETCH_ASSOC);
        
        // Crée un tableau pour stocker les titres d'allergies
        $allergies = [];
        
        // Boucle sur les données des allergies et ajoute les titres au tableau des allergies
        foreach ($allergiesData as $allergie) {
            $allergies[] = $allergie['titre'];
        }
        
        // Ajoute les allergies à l'objet utilisateur
        $userData['allergies'] = $allergies;
    }
    
    return $userData;
}


    
    
    public function Create() {
        // Prépare la requête SQL
        $sql = "INSERT INTO " . $this->table . " SET 
            id=:id, 
            email=:email, 
            mot_de_passe=:mot_de_passe, 
            est_administrateur=:est_administrateur, 
            nombre_convives_par_defaut=:nombre_convives_par_defaut";
        
        $query = $this->connexion->prepare($sql);
        
        // Nettoie les données
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->mot_de_passe = htmlspecialchars(strip_tags($this->mot_de_passe));
        $this->est_administrateur = htmlspecialchars(strip_tags($this->est_administrateur));
        $this->nombre_convives_par_defaut = htmlspecialchars(strip_tags($this->nombre_convives_par_defaut));
        
        
        // Bind les valeurs
        $query->bindParam(":id", $this->id);
        $query->bindParam(":email", $this->email);
        $query->bindParam(":mot_de_passe", $this->mot_de_passe);
        $query->bindParam(":est_administrateur", $this->est_administrateur);
        $query->bindParam(":nombre_convives_par_defaut", $this->nombre_convives_par_defaut);
        
        // Exécute la requête
        if($query->execute()){
            return true;
        }
        
        return false;
    }
    public function emailExists($email) {
        // Prépare la requête SQL
        $sql = "SELECT id FROM " . $this->table . " WHERE email=:email";
        
        // Prépare la requête pour exécution
        $query = $this->connexion->prepare($sql);
        
        // Nettoie les données
        $email = htmlspecialchars(strip_tags($email));
        
        // Bind la valeur de l'e-mail
        $query->bindParam(":email", $email);
        
        // Exécute la requête
        $query->execute();
        
        // Récupère le nombre de lignes affectées par la requête
        $num = $query->rowCount();
        
        // Si le nombre de lignes affectées est supérieur à 0, alors l'e-mail existe déjà en base de données
        if ($num > 0) {
            return true;
        }
        
        return false;
    }
    
    public function Delete() {
        $sql = "DELETE FROM " . $this->table . " WHERE id=:id";
        $query = $this->connexion->prepare($sql);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $query->bindParam(":id", $this->id);
    
        if($query->execute()){
            if($query->rowCount()>0){
                return true;
            }
            
        }

        return false;
    }
    // public function Update() {
    //     // Prépare la requête SQL pour mettre à jour les autres champs de l'utilisateur
    //     $sql = "UPDATE " . $this->table . " SET 
    //         email=:email, 
    //         mot_de_passe=:mot_de_passe, 
    //         est_administrateur=:est_administrateur, 
    //         nombre_convives_par_defaut=:nombre_convives_par_defaut
    //         WHERE id=:id";
            
    //     $query = $this->connexion->prepare($sql);
            
    //     // Nettoie les données
    //     $this->id = htmlspecialchars(strip_tags($this->id));
    //     $this->email = htmlspecialchars(strip_tags($this->email));
    //     $this->mot_de_passe = htmlspecialchars(strip_tags($this->mot_de_passe));
    //     $this->est_administrateur = htmlspecialchars(strip_tags($this->est_administrateur));
    //     $this->nombre_convives_par_defaut = htmlspecialchars(strip_tags($this->nombre_convives_par_defaut));
            
    //     // Bind les valeurs
    //     $query->bindParam(":id", $this->id);
    //     $query->bindParam(":email", $this->email);
    //     $query->bindParam(":mot_de_passe", $this->mot_de_passe);
    //     $query->bindParam(":est_administrateur", $this->est_administrateur);
    //     $query->bindParam(":nombre_convives_par_defaut", $this->nombre_convives_par_defaut);
            
    //     // Exécute la requête pour mettre à jour les autres champs de l'utilisateur
    //     if (!$query->execute()) {
    //         return false;
    //     }
            
    //     // Supprime toutes les allergies existantes pour l'utilisateur
    //     $sqlDeleteAllergies = "DELETE FROM allergies_utilisateurs WHERE utilisateur_id = :id";
    //     $queryDeleteAllergies = $this->connexion->prepare($sqlDeleteAllergies);
    //     $queryDeleteAllergies->bindParam(":id", $this->id);
    //     $queryDeleteAllergies->execute();
            
    //     // Insère les nouvelles allergies pour l'utilisateur
    //     $sqlInsertAllergies = "INSERT INTO allergies_utilisateurs (utilisateur_id, allergie_id) VALUES (:utilisateur_id, :allergie_id)";
    //     $queryInsertAllergies = $this->connexion->prepare($sqlInsertAllergies);
            
    //     // Boucle sur les nouvelles allergies et exécute les requêtes d'insertion
    //     // Boucle sur les nouvelles allergies et exécute les requêtes d'insertion
    //     foreach ($this->allergies as $allergie_id) {
    //         $queryInsertAllergies->bindParam(":utilisateur_id", $this->id);
    //         $queryInsertAllergies->bindParam(":allergie_id", $allergie_id);
    //         $queryInsertAllergies->execute();
    //     }

    // }
    public function Update()
    {
    
        // Supprime toutes les allergies existantes pour l'utilisateur
        $sqlDeleteAllergies = "DELETE FROM allergies_utilisateurs WHERE utilisateur_id = :utilisateur_id";
        $queryDeleteAllergies = $this->connexion->prepare($sqlDeleteAllergies);
        $queryDeleteAllergies->bindParam(":utilisateur_id", $this->id);
        $queryDeleteAllergies->execute();
        
        // Insère les nouvelles allergies pour l'utilisateur
        $sqlInsertAllergies = "INSERT INTO allergies_utilisateurs (utilisateur_id, allergie_id) VALUES (:utilisateur_id, :allergie_id)";
        $queryInsertAllergies = $this->connexion->prepare($sqlInsertAllergies);
    
        // Boucle sur les nouvelles allergies et exécute les requêtes d'insertion
        foreach ($this->allergies as $allergie_id) {
            $queryInsertAllergies->bindParam(":utilisateur_id", $this->id);
            $queryInsertAllergies->bindParam(":allergie_id", $allergie_id);
            $queryInsertAllergies->execute();
            
        }
    }
    

    // public function login($email, $password) {
    //     // Prépare la requête SQL
    //     $sql = "SELECT * FROM " . $this->table . " WHERE email=:email AND mot_de_passe=:password";
    
    //     // Prépare la requête pour exécution
    //     $query = $this->connexion->prepare($sql);
    
    //     // Nettoie les données
    //     $email = htmlspecialchars(strip_tags($email));
    //     $password = htmlspecialchars(strip_tags($password));
    
    //     // Bind les valeurs
    //     $query->bindParam(":email", $email);
    //     $query->bindParam(":password", $password);
    
    //     // Exécute la requête
    //     $query->execute();
    
    //     // Vérifie si un utilisateur correspondant a été trouvé
    //     if ($query->rowCount() == 1) {
    //         // Récupère les informations de l'utilisateur
    //         $row = $query->fetch(PDO::FETCH_ASSOC);
    
    //         // Met à jour les propriétés de l'objet utilisateur
    //         $this->id = $row['id'];
    //         $this->email = $row['email'];
    //         $this->mot_de_passe = $row['mot_de_passe'];
    //         $this->est_administrateur = $row['est_administrateur'];
    //         $this->nombre_convives_par_defaut = $row['nombre_convives_par_defaut'];
    //         $this->allergies = $row['allergies'];
    
    //         // Retourne true pour indiquer que l'utilisateur a été trouvé
    //         return true;
    //     } else {
    //         // Retourne false pour indiquer que l'utilisateur n'a pas été trouvé
    //         return false;
    //     }
    // }
    public function login($email, $password) {
        // Prépare la requête SQL
        $sql = "SELECT * FROM " . $this->table . " WHERE email=:email";
    
        // Prépare la requête pour exécution
        $query = $this->connexion->prepare($sql);
    
        // Nettoie les données
        $email = htmlspecialchars(strip_tags($email));
    
        // Bind les valeurs
        $query->bindParam(":email", $email);
    
        // Exécute la requête
        $query->execute();
    
        // Vérifie si un utilisateur correspondant a été trouvé
        if ($query->rowCount() == 1) {
            // Récupère les informations de l'utilisateur
            $row = $query->fetch(PDO::FETCH_ASSOC);
    
            // Vérifie si le mot de passe fourni correspond au mot de passe hashé
            if (password_verify($password, $row['mot_de_passe'])) {
                // Met à jour les propriétés de l'objet utilisateur
                $this->id = $row['id'];
                $this->email = $row['email'];
                $this->mot_de_passe = $row['mot_de_passe'];
                $this->est_administrateur = $row['est_administrateur'];
                $this->nombre_convives_par_defaut = $row['nombre_convives_par_defaut'];
                
    
                // Retourne true pour indiquer que l'utilisateur a été trouvé et que le mot de passe est correct
                return true;
            }
        }
    
        // Retourne false pour indiquer que l'utilisateur n'a pas été trouvé ou que le mot de passe est incorrect
        return false;
    }
    
    
    
    
}