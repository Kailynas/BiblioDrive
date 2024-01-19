<?php
    try {
        $dns = 'mysql:host=localhost;dbname=biblio'; // dbname : nom de la base
        $utilisateur = 'root'; // root sur vos postes
        $motDePasse = ''; // pas de mot de passe sur vos postes
        $connexion = new PDO( $dns, $utilisateur, $motDePasse );
    } catch (Exception $e) {
        echo "Connexion à la base de donnée biblio impossible : ", $e->getMessage();
        die();
    }
?>