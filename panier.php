<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Panier - Bibliodrive</title>
</head>
<body>
<?php include("cookie.html"); ?>
    <?php
        session_start();
        require_once('connexion.php');
        require("authentification.php");
        require("entete.html");

        if(isset($_POST["emprunt_livre"])) {
            // Ajouter le code pour valider le panier et mettre à jour la base de données ici
            // ...

            // Message d'emprunt réussi
            $message_emprunt = "Votre livre a bien été emprunté!";
        }
    ?>

    <div class="container mt-5">
        <h1 class="display-4 text-center mb-5">Votre panier</h1>

        <div class="row justify-content-center">
            <?php
                // Votre code pour afficher les livres du panier ici...
    
                if (isset($_SESSION["panier"]) && count($_SESSION["panier"]) > 0) {
                    foreach ($_SESSION["panier"] as $livre) {
                        $requete = $connexion->prepare("
                            SELECT nolivre, nom, prenom, titre, anneeparution
                            FROM livre
                            JOIN auteur ON livre.noauteur = auteur.noauteur
                            WHERE nolivre = :nolivre;
                        ");
    
                        $requete->bindValue(":nolivre", $livre, PDO::PARAM_INT);
    
                        $requete->setFetchMode(PDO::FETCH_OBJ);
                        $requete->execute();
    
                        while ($info_panier = $requete->fetch()) {
                            echo '<div class="col-md-6">';
                            echo '<div class="card mb-3">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">'.$info_panier->nom.' '.$info_panier->prenom.'</h5>';
                            echo '<p class="card-text">'.$info_panier->titre.' ('.$info_panier->anneeparution.')</p>';
                            echo '<a href="panier.php?retirer=true&nolivre='.$info_panier->nolivre.'&redirect=panier.php" class="btn btn-danger">Retirer du panier.</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }

                    echo '<div class="col-md-8">';
                    if (isset($message_emprunt)) {
                        echo '<div class="alert alert-success" role="alert">';
                        echo $message_emprunt;
                        echo '</div>';
                    }
                    echo '<form method="post">';
                    echo '<button type="submit" name="emprunt_livre" class="btn btn-success">Valider le panier</button>';
                    echo '</form>';
                    echo '</div>';
                } else {
                    echo '<div class="col-md-8">';
                    echo '<div class="alert alert-info" role="alert">';
                    echo 'Votre panier est vide.';
                    echo '</div>';
                    echo '</div>';
                }
            ?>
        </div>
    </div>

</body>
</html>
