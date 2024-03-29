<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>Bibliodrive</title>
</head>
<body>
<?php include("cookie.html"); ?>
    <header>
        <?php
            session_start();
            require("authentification.php");
            if ($_SESSION["adminUser"]) require("enteteADM.html");
            else require("entete.html");
            require_once('connexion.php');
        ?>
    </header>
    
    <?php
        if ($_SESSION["adminUser"]) {
            echo '<h1 class="big-title admin">Admin panel.</h1>';
            exit;
        } else {
            echo '<h1 class="big-title">Dernières acquisitions</h1>';
        }
        $req = $connexion->prepare("SELECT image FROM livre ORDER BY dateajout DESC LIMIT 2;");
        $req->setFetchMode(PDO::FETCH_OBJ);
        $req->execute();
    ?>

    <div id="carouselExample" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <?php
                $active = true;
                if ($req->rowCount() != 0) {
                    while ($dernier_acqui = $req->fetch()) {
                        $activeClass = $active ? 'active' : '';
                        echo '<div class="carousel-item ' . $activeClass . '" data-interval="1000">';
                        echo '<img src="couverture/'.$dernier_acqui->image.'" class="d-block w-50">';
                        echo '</div>';
                        $active = false;
                    }
                }
            ?>  
        </div>
    </div>

    <footer>
        <?php require('msg-biblio.html')?>
    </footer>

</body>
</html>
