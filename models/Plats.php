<?php 
require_once('../config\database.php');

class Plats {
    // connexion à la table
    private $connexion;
    private $table = "plats";
    // information table plats
    public $id;
    public $titre;
    public $description;
    public $prix;
    public $id_categorie;

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
            description=:description, 
            prix=:prix, 
            id_categorie=:id_categorie";
        
        $query = $this->connexion->prepare($sql);
        
        // Nettoie les données
        $this->titre = htmlspecialchars($this->titre);
        $this->description = addslashes($this->description);
        $this->prix = addslashes($this->prix);
        $this->id_categorie = htmlspecialchars(strip_tags($this->id_categorie));
        
        // Bind les valeurs
        $query->bindParam(":titre", $this->titre);
        $query->bindParam(":description", $this->description);
        $query->bindParam(":prix", $this->prix);
        $query->bindParam(":id_categorie", $this->id_categorie);
        
        // Exécute la requête
        if($query->execute()){
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
    public function Update() {
        // Prépare la requête SQL
        $sql = "UPDATE " . $this->table . " SET 
                titre=:titre, 
                description=:description, 
                prix=:prix, 
                id_categorie=:id_categorie 
                WHERE id=:id";
            
        $query = $this->connexion->prepare($sql);
            
        // Nettoie les données
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->titre = addslashes($this->titre);
        $this->description = addslashes($this->description);
        $this->prix = htmlspecialchars(strip_tags($this->prix));
        $this->id_categorie = htmlspecialchars(strip_tags($this->id_categorie));
            
        // Bind les valeurs
        $query->bindParam(":id", $this->id);
        $query->bindParam(":titre", $this->titre);
        $query->bindParam(":description", $this->description);
        $query->bindParam(":prix", $this->prix);
        $query->bindParam(":id_categorie", $this->id_categorie);
            
        // Exécute la requête
        
        if($query->execute() ){
                return true;
            
        }
    
            
        return false;
    }
    
    
    
}


?>
