<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css"> <!-- Assurez-vous d'avoir un fichier style.css pour les styles personnalisés -->
    <title>Ajouter un livre - Bibliodrive</title>
</head>
<body>
<?php include("cookie.html"); ?>
<?php
session_start();

if(!$_SESSION["adminUser"] || !isset($_SESSION["adminUser"])) {
    echo "Accès non autorisé."; // Refuse l'accès un utilisateur curieux, même si il requête l'API en POST 
    exit;
}
require("authentification.php");
require("enteteADM.html");

if(isset($_POST["noauteur"])){
    $noauteur = $_POST["noauteur"];
    $titre = $_POST["titre"];
    $ISBN13 = $_POST["ISBN13"];
    $annee_parution = $_POST["annee_parution"];
    $resume = $_POST["resume"];
    $cover = $_FILES["cover"];

    try {
        $req = $connexion->prepare("
            INSERT INTO 
            livre(noauteur, titre, isbn13, anneeparution, resume, dateajout, image) 
            VALUES(:noauteur, :titre, :ISBN13, :annee_parution, :resume, :dateajout, :cover)
        ");

        $req->bindValue(":noauteur", $noauteur, PDO::PARAM_INT);
        $req->bindValue(":titre", $titre);
        $req->bindValue(":ISBN13", $ISBN13);
        $req->bindValue(":annee_parution", $annee_parution);
        $req->bindValue(":resume", $resume);
        $req->bindValue(":dateajout", date("Y-m-d"));
        $req->bindValue(":cover", $cover['name']);

        $req->execute();
        $book_added = TRUE;

        move_uploaded_file($cover['tmp_name'], "couvertures/".$cover['name']); // Ajouter la cover dans le dossier cover de images pour l'afficher sur le site.
    } catch(Exception $e) {
        $erreur = $e;
        $book_added = FALSE;
    }
}
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h1 class="display-4 text-center">Ajouter un livre</h1>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="auteur">Auteur :</label>
                    <?php
                        echo "<select class='form-control' name='noauteur' id='auteur' required>";
                        echo "<option value='' disabled selected>---- Sélectionner ----</option>";
                        $req = $connexion->query("SELECT noauteur,nom FROM auteur");
                        $req->setFetchMode(PDO::FETCH_OBJ);

                        while($auteur = $req->fetch()){
                            echo "<option value='{$auteur->noauteur}'>{$auteur->nom}</option>";
                        }

                        echo "</select>";
                    ?>
                </div>

                <div class="form-group">
                    <label for="titre">Titre :</label>
                    <input type="text" class="form-control" name="titre" id="titre" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label for="ISBN13">ISBN13 :</label>
                    <input type="text" class="form-control" name="ISBN13" id="ISBN13" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label for="annee_parution">Année de parution :</label>
                    <input type="text" class="form-control" name="annee_parution" id="annee_parution" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label for="resume">Résumé :</label>
                    <textarea class="form-control" name="resume" id="resume" autocomplete="off" rows="7" required></textarea>
                </div>

                <div class="form-group">
                    <label for="cover">Image :</label>
                    <input type="file" class="form-control-file" id="cover" name="cover" accept="image/png, image/jpeg" required/>
                </div>

                <button type="submit" class="btn btn-primary">Ajouter le livre</button>

                <?php 
                    if(isset($book_added)) {
                        if ($book_added) 
                            echo '<p class="text-success mt-3">Livre ajouté avec succès !</p>';
                        else
                            echo '<p class="text-danger mt-3">Une erreur est survenue lors de l\'ajout du livre : '. $erreur . '</p>';
                    }
                ?>
            </form>
        </div>
    </div>
</div>

</body>
</html>