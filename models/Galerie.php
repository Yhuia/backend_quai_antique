<?php 
require_once('../config\database.php');

class Image {
    // connexion à la table
    private $connexion;
    private $table = "images_galerie";
    // informations table images
    public $id;
    public $titre;
    public $url;

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

    public function Create() {
        // Prépare la requête SQL
        $sql = "INSERT INTO " . $this->table . " SET 
            titre=:titre,
            url=:url";
        
        $query = $this->connexion->prepare($sql);
        
        // Nettoie les données
        $this->titre = htmlspecialchars(strip_tags($this->titre));
        $this->url = addslashes($this->url);
        
        // Bind les valeurs
        $query->bindParam(":titre", $this->titre, PDO::PARAM_STR);
        $query->bindParam(":url", $this->url, PDO::PARAM_STR);
        
        // Exécute la requête
        if($query->execute()){
            return true;
        }
        
        return false;
    }
    // public function Create($blobData) {
    //     // Prépare la requête SQL
    //     $sql = "INSERT INTO " . $this->table . " SET titre=:titre, url=:url";
    //     $query = $this->connexion->prepare($sql);
    
    //     // Nettoie les données
    //     $this->titre = htmlspecialchars(strip_tags($this->titre));
    //     $blobData = addslashes($blobData);
    
    //     // Bind les valeurs
    //     $query->bindParam(":titre", $this->titre, PDO::PARAM_STR);
    //     $query->bindParam(":url", $blobData, PDO::PARAM_LOB);
    
    //     // Exécute la requête
    //     if($query->execute()){
    //         return true;
    //     }
    
    //     return false;
    // }
    

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

    public function Update() {
        // Prépare la requête SQL
        $sql = "UPDATE " . $this->table . " SET 
                titre=:titre,
                url=:url
                WHERE id=:id";
            
        $query = $this->connexion->prepare($sql);
            
        // Nettoie les données
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->titre = addslashes($this->titre);
        $this->url = addslashes($this->url);
            
        // Bind les valeurs
        $query->bindParam(":id", $this->id, PDO::PARAM_INT);
        $query->bindParam(":titre", $this->titre, PDO::PARAM_STR);
        $query->bindParam(":url", $this->url, PDO::PARAM_STR);
            
        // Exécute la requête
        if($query->execute()){
            return true;
        }

        return false;
    }   
}
?> 