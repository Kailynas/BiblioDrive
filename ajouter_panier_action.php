<?php
    session_start();

    // Inclure le fichier de connexion à la base de données
    require_once('connexion.php');

    if(isset($_POST["nolivre"]) && isset($_POST["quantite"])) {
        $nolivre = $_POST["nolivre"];
        $quantite = $_POST["quantite"];

        // Récupérer les informations du livre
        $req = $connexion->prepare("SELECT titre, stock FROM livre WHERE nolivre = :nolivre");
        $req->bindValue(":nolivre", $nolivre, PDO::PARAM_INT);
        $req->setFetchMode(PDO::FETCH_OBJ);
        $req->execute();
        $livreInfo = $req->fetch();

        // Vérifier si le livre existe et s'il y a suffisamment de stock
        if($livreInfo && $livreInfo->stock >= $quantite) {

            // Ajouter le livre au panier
            if(!isset($_SESSION["panier"])) {
                $_SESSION["panier"] = array();
            }

            // Ajouter le livre au panier avec la quantité spécifiée
            $_SESSION["panier"][$nolivre] = array(
                "titre" => $livreInfo->titre,
                "quantite" => $quantite
            );

            // Mettre à jour le stock du livre dans la base de données
            $nouveauStock = $livreInfo->stock - $quantite;
            $reqUpdateStock = $connexion->prepare("UPDATE livre SET stock = :nouveauStock WHERE nolivre = :nolivre");
            $reqUpdateStock->bindValue(":nouveauStock", $nouveauStock, PDO::PARAM_INT);
            $reqUpdateStock->bindValue(":nolivre", $nolivre, PDO::PARAM_INT);
            $reqUpdateStock->execute();

            // Rediriger vers la page liste_livres.php avec un message de succès
            header("Location: liste_livres.php?success=1");
            exit();
        } else {
            // Rediriger vers la page liste_livres.php avec un message d'erreur
            header("Location: liste_livres.php?error=1");
            exit();
        }
    } else {
        // Rediriger vers la page liste_livres.php avec un message d'erreur
        header("Location: liste_livres.php?error=1");
        exit();
    }
?>
