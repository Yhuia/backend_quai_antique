<?php 
require_once('../config\database.php');

class Category {
    // connexion à la table
    private $connexion;
    private $table = "categorie";
    // information table categories
    public $id;
    public $nom;

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
            nom=:nom";
        
        $query = $this->connexion->prepare($sql);
        
        // Nettoie les données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        
        // Bind les valeurs
        $query->bindParam(":nom", $this->nom, PDO::PARAM_STR);
        
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
                nom=:nom 
                WHERE id=:id";
            
        $query = $this->connexion->prepare($sql);
            
        // Nettoie les données
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->nom = htmlspecialchars(strip_tags($this->nom));
            
        // Bind les valeurs
        $query->bindParam(":id", $this->id, PDO::PARAM_INT);
        $query->bindParam(":nom", $this->nom, PDO::PARAM_STR);
            
        // Exécute la requête
        if($query->execute()){
            return true;
        }

        return false;
    }   
}
?>
