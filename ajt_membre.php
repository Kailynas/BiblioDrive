<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Ajouter un membre - Bibliodrive</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <?php
        session_start();

        if (!$_SESSION["adminUser"] || !isset($_SESSION["adminUser"])) {
            echo "Accès non autorisé.";
            exit;
        }

        require("authentification.php");
        require("enteteADM.html");

        if (isset($_POST["email"])) {

            $email = $_POST["email"];
            $mdp = $_POST["mdp"];
            $nom = $_POST["nom"];
            $prenom = $_POST["prenom"];
            $adresse = $_POST["adresse"];
            $ville = $_POST["ville"];
            $codepostal = $_POST["codePostal"];

            $mdp_hash = password_hash($mdp, PASSWORD_ARGON2I);

            try {
                $req = $connexion->prepare("
                INSERT INTO 
                utilisateur(mel,motdepasse,nom,prenom,adresse,ville,codepostal,profil) 
                VALUES(:email, :mdp, :nom, :prenom, :adresse, :ville, :codepostal, 'client')
                ");

                $req->bindValue(":email", $email);
                $req->bindValue(":mdp", $mdp_hash);
                $req->bindValue(":nom", $nom);
                $req->bindValue(":prenom", $prenom);
                $req->bindValue(":adresse", $adresse);
                $req->bindValue(":ville", $ville);
                $req->bindValue(":codepostal", $codepostal, PDO::PARAM_INT);

                $req->execute();
                $member_added = TRUE;

            } catch (Exception $e) {
                $erreur = $e->getMessage();
                $member_added = FALSE;
            }
        }
    ?>

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1 class="display-4 text-center">Ajouter un membre</h1>
            </div>
            <div class="card-body">
                <form method="post" class="form-admin">

                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" class="form-control" name="email" id="email" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label for="mdp">Mot de passe :</label>
                        <input type="password" class="form-control" name="mdp" id="mdp" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label for="nom">Nom :</label>
                        <input type="text" class="form-control" name="nom" id="nom" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prenom :</label>
                        <input type="text" class="form-control" name="prenom" id="prenom" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label for="adresse">Adresse :</label>
                        <input type="text" class="form-control" name="adresse" id="adresse" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label for="ville">Ville :</label>
                        <input type="text" class="form-control" name="ville" id="ville" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label for="codePostal">Code Postal :</label>
                        <input type="number" class="form-control" name="codePostal" id="codePostal" min="1" max="100000" autocomplete="off" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Créer un membre</button>

                    <?php
                        if (isset($member_added)) {
                            if ($member_added)
                                echo '<p class="text-success mt-3">Membre ajouté avec succès !</p>';
                            else
                                echo '<p class="text-danger mt-3">Une erreur est survenue lors de l\'ajout du membre : ' . $erreur . '</p>';
                        }
                    ?>

                </form>
            </div>
        </div>
    </div>

</body>
</html>
