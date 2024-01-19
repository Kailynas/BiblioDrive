<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" type="text/css" href="style.css">
    <title>Detail - Bibliodrive</title>
</head>
<body>

    <?php
        session_start();

        require("authentification.php");

        require_once('connexion.php');

        require("entete.html");

        if(!isset($_GET["livre"])) {
            echo '<h1 class="big-title">Aucun livre renseigné.</h1>';
            exit;
        }
        else {
            $req = $connexion->prepare("
            SELECT nolivre, nom, prenom, isbn13, resume, image FROM livre
            INNER JOIN auteur ON livre.noauteur = auteur.noauteur
            WHERE nolivre = :nolivre;
            ");

            $req->bindValue(":nolivre", $_GET["livre"], PDO::PARAM_INT);

            $req->setFetchMode(PDO::FETCH_OBJ);
            $req->execute();

            if($req->rowCount() == 0) {
                echo '<h1 class="big-title">Livre non trouvé.</h1>';
                exit;
            }

            $info_livre = $req->fetch();
        }
    ?>


    <div class="resume-container">
        <div>
        <div class="retour-detail">
    <a href="<?php echo 'lister_livres.php?auteur='.$info_livre->nom.'#livre_'.$info_livre->nolivre; ?>">← Retour</a>
</div>
            <p><b>Auteur:</b> <?php echo $info_livre->nom . " " . $info_livre->prenom ;?></p>
            <p><b>ISBN13:</b> <?php echo $info_livre->isbn13;?></p>
            <p class="titre-resume">Résumé du livre</p>
            <div class="bloc-resume">
                <?php echo $info_livre->resume;?>
            </div>
            <div class="info-commande">
                <?php
                    $req = $connexion->prepare("
                    SELECT nolivre FROM emprunter
                    WHERE nolivre = :nolivre;
                    ");
        
                    $req->bindValue(":nolivre", $_GET["livre"], PDO::PARAM_INT);
        
                    $req->setFetchMode(PDO::FETCH_OBJ);
                    $req->execute();
                    $emprute = $req->fetch();

                    if($emprute){
                        echo '<p class="order-unavailable">Indisponible</p>';
                    } else {
                        echo '<p class="order-available">Disponible</p>';
                    }
                
                
                ?>
                <!-- <iframe name="ajouter_livre_panier" style="display:none;"></iframe> -->
                
                <?php
                    if($_SESSION["connected"]){
                        $req = $connexion->prepare("SELECT mel FROM emprunter WHERE nolivre = :nolivre");
                        $req->bindValue(":nolivre", $_GET["livre"], PDO::PARAM_INT);
                        $req->setFetchMode(PDO::FETCH_OBJ);
                        $req->execute();
                        $user_mel = $req->fetch();

                        $req = $connexion->prepare("SELECT mel FROM emprunter WHERE mel = :email");
                        $req->bindValue(":email", $_SESSION["email"]);
                        $req->execute();
                        $emprunt_utilisateur = $req->fetch();

                        if(isset($user_mel->mel) && $user_mel->mel == $_SESSION["email"]){
                            echo '<p>Déjà emprunté.</p>';
                        } elseif($req->rowCount() == 5){ // Vérifier si la personne n'a pas plus de 5 emprunts
                            echo '<p>Vous avez 5 emprunts.</p>';
                        } else {
                            if(!$emprute){
                                if(in_array($_GET["livre"],$_SESSION["panier"])){
                                    echo '<a href="utilitaires/panier_admin?retirer=true&nolivre='.$_GET["livre"].'&redirect=detail?livre='.$_GET["livre"].'" class="button-general retirer-panier">Retirer du panier</a>';
                                } else {
                                    if((count($_SESSION["panier"]) + $req->rowCount()) == 5){
                                        echo '<p>Vous ne pouvez plus ajouter de livres dans votre panier.<br>5 emprunts max. ('.$req->rowCount().' en cours).</p>';
                                    } else {
                                        echo '<a href="utilitaires/panier_admin?ajout=true&nolivre='.$_GET["livre"].'&redirect=detail?livre='.$_GET["livre"].'" class="button-general ajout-panier">Ajouter au panier</a>';
                                    }
                                }
                            }
                        }

                    } else {
                        if(!$emprute){
                            echo "<p>Connectez vous pour ajouter à votre panier.</p>";
                        }
                    }
                ?>
            </div>
        </div>
        <div>
            <?php
                if(file_exists("couverture/".$info_livre->image)){
                    $cover = $info_livre->image;
                } else {
                    $cover = "couverture/";
                }
            ?>
            <img src="couverture/<?php echo $cover?>" alt="Book cover" class="book-cover-img">
        </div>
    </div>
    

    <footer>
        <?php require('msg-biblio.html')?>
    </footer>
    
</body>
</html>

