<?php
require_once('../config\database.php');

class Allergies {
    private $connexion;
    private $table = "allergies";
    public $id;
    public $titre;

    public function __construct($db) {
        $this->connexion = $db;
    }

    public function Read() {
        $sql = "SELECT * FROM " . $this->table;
        $query = $this->connexion->prepare($sql);
        $query->execute();
    
        return $query; 
    }

    public function Create() {
        $sql = "INSERT INTO " . $this->table . " SET 
            titre=:titre";
        
        $query = $this->connexion->prepare($sql);
        
        $this->titre = htmlspecialchars(strip_tags($this->titre));
        
        $query->bindParam(":titre", $this->titre, PDO::PARAM_STR);
        
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
        $sql = "UPDATE " . $this->table . " SET 
                titre=:titre 
                WHERE id=:id";
            
        $query = $this->connexion->prepare($sql);
            
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->titre = htmlspecialchars(strip_tags($this->titre));
            
        $query->bindParam(":id", $this->id, PDO::PARAM_INT);
        $query->bindParam(":titre", $this->titre, PDO::PARAM_STR);
            
        if($query->execute()){
            return true;
        }

        return false;
    }   
}
?>
