<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Panier - Bibliodrive</title>
</head>
<body>

    <?php
        session_start();
        require_once('connexion.php');
        require("authentification.php");
        require("entete.html");

        if(isset($_POST["emprunt_livre"])) {
            // Votre code pour l'emprunt ici...
        }
    ?>

    <h1 class="grand-titre">Votre panier</h1>

    <div class="conteneur-panier">
        <?php
            // Votre code pour afficher les livres du panier ici...

            if (count($_SESSION["panier"]) > 0) {
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
                        echo '<div class="info-panier">';
                        echo "<p>".$info_panier->nom." ".$info_panier->prenom." - ".$info_panier->titre." (".$info_panier->anneeparution.")</p>";
                        echo '<a href="panier_admin.php?retirer=true&nolivre='.$info_panier->nolivre.'&redirect=bib2/panier.php" class="button-general">Annuler</a>';
                        echo '</div>';
                    }
                }

                echo '<form method="post">';
                echo '<input type="hidden" name="emprunt_livre" value="true">';
            }
        ?>
        
    </div>
</body>
</html>
