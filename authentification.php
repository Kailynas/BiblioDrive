<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<?php require_once('connexion.php') ?>
<?php include("cookie.html"); ?>
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
                    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Connexion</h5>
                    <form method="post" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <?php
                                $emailInput = isset($email_renseigne) ? $email_renseigne : '';
                                echo '<input class="form-control" type="email" name="email" id="email" placeholder="Email" autocomplete="off" value="'.$emailInput.'" required>';
                            ?>
                            <div class="invalid-feedback">
                                Veuillez entrer une adresse e-mail valide.
                            </div>
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="password" name="mdp" id="mdp" placeholder="Mot de passe" autocomplete="off" required>
                            <div class="invalid-feedback">
                                Veuillez entrer votre mot de passe.
                            </div>
                        </div>
                        <?php
                            if (isset($erreur_connexion)) {
                                echo '<p class="text-danger text-center mb-3">Votre email ou mot de passe est incorrect.</p>';
                            }
                        ?>
                        <button class="btn btn-primary btn-block" type="submit">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

