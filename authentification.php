<?php require_once('connexion.php') ?>

<div class="container mt-3">
    <div class="mobile-nav">
        <div>
            <!-- Ajoutez ici le contenu du logo ou du nom du site -->
        </div>

        <div class="authen-container">
            <?php
                if (!isset($_SESSION["connected"])) {
                    $_SESSION["connected"] = FALSE;
                    $_SESSION["adminUser"] = FALSE;
                }

                if (isset($_POST["email"])) {
                    $requete = $connexion->prepare("SELECT motdepasse,profil FROM utilisateur WHERE mel = :email");
                    $requete->bindValue(":email", $_POST["email"], PDO::PARAM_STR);
                    $requete->execute();
                    $requete->setFetchMode(PDO::FETCH_OBJ);
                    
                    $utilisateur = $requete->fetch();
                    if ($utilisateur) {
                        if (password_verify($_POST["mdp"], $utilisateur->motdepasse)) { 
                            $_SESSION["email"] = $_POST["email"];
                            $_SESSION["connected"] = TRUE;
                            $_SESSION["panier"] = array();
                            if ($utilisateur->profil == "admin") $_SESSION["adminUser"] = TRUE;
                        }
                    } else {
                        $erreur_connexion = TRUE;
                        $email_renseigne = $_POST["email"];
                    }
                }
                
                if (isset($_POST["logoff"])) {
                    $_SESSION["connected"] = FALSE;
                    unset($_SESSION["email"]);
                    unset($_SESSION["panier"]);
                    
                    if ($_SESSION["adminUser"]) {
                        $_SESSION["adminUser"] = FALSE;
                    }
            
                    header("Location: accueil.php");  // Redirection vers accueil.php
                    exit;
                }

                if ($_SESSION["connected"]) {
                    $requete = $connexion->prepare("SELECT mel,nom,prenom,adresse,profil FROM utilisateur WHERE mel = :email");
                    $requete->bindValue(":email", $_SESSION["email"], PDO::PARAM_STR);
                    $requete->execute();
                    $requete->setFetchMode(PDO::FETCH_OBJ);
                    $utilisateur = $requete->fetch();
            ?>
                    <div class="user-info">
                        <p class="titre-form">Bonjour, <br><?= $utilisateur->nom.' '.$utilisateur->prenom ?></p>
                        <p class="email-set"><?= $utilisateur->mel ?></p>
                        <?php
                            if ($utilisateur->profil == "client") {
                                echo '<p class="adresse-set">'.$utilisateur->adresse.'</p>';
                            } else {
                                echo '<p class="admin-account">Vous êtes Administrateur</p>';
                            }
                        ?>
                    </div>
                    <form method="post" class="form-login">
                        <input type="hidden" name="logoff" value="true">
                        <input class="btn btn-danger submit-login button-general" type="submit" value="Se déconnecter">
                    </form>
            <?php
                } else {
            ?>
                    <div class="login-form">
                        <p class="titre-form">Connexion :</p>
                        <form method="post" class="form-login">
                            <?php
                                if (isset($email_renseigne)) 
                                    echo '<input class="form-control" type="email" name="email" id="email" placeholder="Email" autocomplete="off" value="'.$email_renseigne.'" required>';
                                else 
                                    echo '<input class="form-control" type="email" name="email" id="email" placeholder="Email" autocomplete="off" required>';
                                    
                                echo '<input class="form-control" type="password" name="mdp" id="mdp" placeholder="Mot de passe" autocomplete="off" required>';
                                    
                                if (isset($erreur_connexion)) 
                                    echo '<p class="erreur-connexion">Votre email ou mot de passe est incorrect.</p>';
                                    
                                echo '<input class="btn btn-primary submit-login button-general" type="submit" value="Se connecter">';
                            ?>
                        </form>
                    </div>
            <?php } ?>
        </div>
    </div>
</div>
