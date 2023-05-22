<?php
require_once('../config\database.php');

class Formules {
    // connexion à la table
    private $connexion;
    private $table = "formules";
    // information table formules
    public $id;
    public $titre;
    public $description;
    public $prix;
    public $menus_id;

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
            titre =:titre,
            description=:description,
            prix=:prix";

        $query = $this->connexion->prepare($sql);

        // Nettoie les données
        $this->titre = htmlspecialchars($this->titre);
        $this->description = addslashes($this->description);
        $this->prix = htmlspecialchars($this->prix);
        

        // Bind les valeurs
        $query->bindParam(":titre", $this->titre);
        $query->bindParam(":description", $this->description);
        $query->bindParam(":prix", $this->prix);
        
       

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
                description=:description,
                prix=:prix
                WHERE id=:id";

        $query = $this->connexion->prepare($sql);

        // Nettoie les données
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->description = addslashes($this->description);
        $this->prix = htmlspecialchars(strip_tags($this->prix));

        // Bind les valeurs
        $query->bindParam(":id", $this->id);
        $query->bindParam(":description", $this->description);
        $query->bindParam(":prix", $this->prix);

        // Exécute la requête
        if($query->execute() ){
            return true;
        }

        return false;
    }
}

?>