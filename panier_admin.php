<?php
    session_start();
    require_once('connexion.php');

    if ($_SESSION["connected"]) {
        if (isset($_REQUEST["ajout"])) {    
            if (!in_array($_REQUEST["nolivre"], $_SESSION["panier"])) {
                // Vérifier si l'utilisateur n'a pas déjà le livre emprunté (si utilisation via requête)
                $requete = $connexion->prepare("SELECT mel FROM emprunter WHERE mel = :email");
                $requete->bindValue(":email", $_SESSION["email"]);
                $requete->setFetchMode(PDO::FETCH_OBJ);
                $requete->execute();
    
                if ((count($_SESSION["panier"]) + $requete->rowCount()) < 5) { 
                    // Vérifier si la personne n'a pas plus de 5 emprunts
                    $requete = $connexion->prepare("SELECT mel FROM emprunter WHERE nolivre = :nolivre");
                    $requete->bindValue(":nolivre", $_REQUEST["nolivre"], PDO::PARAM_INT);
                    $requete->execute();
                    $user_mel = $requete->fetch();
                    if (!isset($user_mel->mel) || $user_mel->mel != $_SESSION["email"]) {
                        array_push($_SESSION["panier"], $_REQUEST["nolivre"]);                 
                    }
                }
            }

        } elseif (isset($_REQUEST["retirer"])) {         
            $id = array_search($_REQUEST["nolivre"], $_SESSION["panier"]);
            unset($_SESSION["panier"][$id]);
        } elseif (isset($_REQUEST["annuler"])) {
            header("Location: accueil.php");
            exit;
        }

        header("Location: ../".$_REQUEST["redirect"]);
        exit;

    } else {
        header("Location: accueil.php");
        exit;
    }
?>
