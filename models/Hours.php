<?php 
// Problème avec le null, je n'arrive pas a mettre une valeur null pour les horaires mais également pour le nombre de convive
require_once('../config/database.php');

class Hours {
    // connexion à la table
    private $connexion;
    private $table = "horaires";
    // information table horaires
    public $id;
    public $jour_de_la_semaine;
    public $heure_midi_ouverture = NULL;
    public $heure_midi_fermeture = NULL;
    public $heure_soir_ouverture = NULL;
    public $heure_soir_fermeture = NULL;

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
            jour_de_la_semaine=:jour_de_la_semaine,
            heure_midi_ouverture=:heure_midi_ouverture,
            heure_midi_fermeture=:heure_midi_fermeture,
            heure_soir_ouverture=:heure_soir_ouverture,
            heure_soir_fermeture=:heure_soir_fermeture";
        $query = $this->connexion->prepare($sql);
        
        // Bind les valeurs
        $query->bindParam(":jour_de_la_semaine", $this->jour_de_la_semaine);
        $query->bindParam(":heure_midi_ouverture", $this->heure_midi_ouverture);
        $query->bindParam(":heure_midi_fermeture", $this->heure_midi_fermeture);
        $query->bindParam(":heure_soir_ouverture", $this->heure_soir_ouverture);
        $query->bindParam(":heure_soir_fermeture", $this->heure_soir_fermeture);

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
                jour_de_la_semaine=:jour_de_la_semaine,
                heure_midi_ouverture=:heure_midi_ouverture,
                heure_midi_fermeture=:heure_midi_fermeture,
                heure_soir_ouverture=:heure_soir_ouverture,
                heure_soir_fermeture=:heure_soir_fermeture
                WHERE id=:id";
            
        $query = $this->connexion->prepare($sql);
            
        // Bind les valeurs
        $query->bindParam(":id", $this->id);
        $query->bindParam(":jour_de_la_semaine", $this->jour_de_la_semaine);
        $query->bindParam(":heure_midi_ouverture", $this->heure_midi_ouverture);
        $query->bindParam(":heure_midi_fermeture", $this->heure_midi_fermeture);
        $query->bindParam(":heure_soir_ouverture", $this->heure_soir_ouverture);
        $query->bindParam(":heure_soir_fermeture", $this->heure_soir_fermeture);
            
        // Exécute la requête
        if($query->execute()){
            return true;
        }
    
        return false;
    } 
    

}