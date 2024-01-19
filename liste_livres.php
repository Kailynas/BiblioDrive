<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Bibliodrive - Recherche</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <header>
        <?php
            session_start();
            require("authentification.php");
            require("entete.html");
            $auteur = isset($_GET["auteur"]) ? $_GET["auteur"] : "";
        ?>
    </header>

    <div class="container mt-3">
        <div class="resultat-recherche">

        <?php
            if (isset($auteur)) {
                $req = $connexion->prepare("
                    SELECT nolivre, image, anneeparution, resume, titre, stock
                    FROM livre
                    INNER JOIN auteur ON livre.noauteur = auteur.noauteur
                    WHERE nom LIKE :auteur
                ");

                $req->bindValue(":auteur", '%' . $auteur . '%', PDO::PARAM_STR);
                $req->setFetchMode(PDO::FETCH_OBJ);
                $req->execute();

                if ($req->rowCount() == 0) {
                    echo "<p class='text-center'>Aucun Résultat.</p>";
                } else {
                    while ($livre = $req->fetch()) {
                        if (file_exists("couvertures/".$livre->image)) {
                            $cover = $livre->image;
                        } else {
                            $cover = "couvertures/";
                        }
                        echo '
                            <div class="card mb-3" id="livre_'.$livre->nolivre.'">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <img src="couverture/'.$cover.'" class="card-img" alt="Couverture du livre">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title">'.$livre->titre.'</h5>
                                            <p class="card-text">Année de parution: <b>'.$livre->anneeparution.'</b></p>
                                            <p class="card-text"><strong>Résumé:</strong> '.$livre->resume.'</p>';

                                            if ($livre->stock > 0) {
                                                echo '
                                                <form action="ajouter_panier_action.php" method="post" class="form-inline">
                                                    <input type="hidden" name="nolivre" value="'.$livre->nolivre.'">
                                                    <label for="quantite" class="mr-2">Quantité:</label>
                                                    <input type="number" id="quantite" name="quantite" class="form-control mr-2" min="1" max="'.$livre->stock.'" value="1" required>
                                                    <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                                                </form>';
                                            } else {
                                                echo '<p class="stock-indisponible">Stock épuisé</p>';
                                            }

                        echo '</div>
                                    </div>
                                </div>
                            </div>';
                    }
                }
            }
        ?>

        </div>
    </div>

    <footer class="mt-5">
        <?php require('msg-biblio.html')?>
    </footer>

</body>
</html>
