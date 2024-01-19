<?php
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mel = $_POST['mel'];
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $codepostal = $_POST['codepostal'];
    $profil = 'membre'; // Profil par défaut pour les utilisateurs inscrits

    $sql = "INSERT INTO utilisateur (mel, motdepasse, nom, prenom, adresse, ville, codepostal, profil) 
            VALUES (:mel, :motdepasse, :nom, :prenom, :adresse, :ville, :codepostal, :profil)";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':mel', $mel);
    $stmt->bindParam(':motdepasse', $motdepasse);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':adresse', $adresse);
    $stmt->bindParam(':ville', $ville);
    $stmt->bindParam(':codepostal', $codepostal);
    $stmt->bindParam(':profil', $profil);

    if ($stmt->execute()) {
        header('Location: inscription_succes.php');
        exit;
    } else {
        echo "Erreur lors de l'inscription. Veuillez réessayer.";
    }
}
?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<div class="d-flex justify-content-center align-items-center" style="height: 80vh;">
    <form method="POST" action="inscription.php" class="col-md-6">
        <h2 class="mb-4">Inscription</h2>
        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="mel">Adresse e-mail :</label>
            <input type="email" name="mel" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="motdepasse">Mot de passe :</label>
            <input type="password" name="motdepasse" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="adresse">Adresse :</label>
            <input type="text" name="adresse" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="ville">Ville :</label>
            <input type="text" name="ville" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="codepostal">Code postal :</label>
            <input type="text" name="codepostal" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
</div>
