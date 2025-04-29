<?php

function pdo(){

    try{
        $bdd = new PDO("mysql: host=localhost; dbname=ficius; charset = utf8",'root','');

    }catch(PDOException $e){

        die("Erreur de Connexion à la base de données : " . $e -> getMessage());
    }

    if($bdd){
        
        return $bdd;
    }
}



?>