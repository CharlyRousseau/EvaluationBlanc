<?php
require_once "../src/database/db.php";
$conn = connectDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $marque = $_POST["marque"];
    $modele = $_POST["modele"];
    $annee = $_POST["annee"];
    $client_id = $_POST["client_id"];

    $conn->query(
        "INSERT INTO vehicules (marque, modele, annee, client_id) VALUES ('$marque', '$modele', '$annee', '$client_id')"
    );

    header("Location: dashboard.php"); // Rediriger vers le tableau de bord après l'ajout
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <title>Ajouter un véhicule</title>
</head>
<body>
    <h2>Ajouter un véhicule</h2>
    <form action="ajouter.php" method="POST">
        <div class="form-group">
            <label for="marque">Marque</label>
            <input type="text" class="form-control" id="marque" name="marque" required>
        </div>
        <div class="form-group">
            <label for="modele">Modèle</label>
            <input type="text" class="form-control" id="modele" name="modele" required>
        </div>
        <div class="form-group">
            <label for="annee">Année</label>
            <input type="number" class="form-control" id="annee" name="annee" required>
        </div>
        <div class="form-group">
            <label for="client">Client</label>
            <select class="form-control" id="client" name="client_id">
                <option value="">Sélectionner un client</option>
                <?php
                $clientsResult = $conn->query("SELECT id, nom FROM clients");
                while ($client = $clientsResult->fetch_assoc()) {
                    echo "<option value=\"{$client["id"]}\">{$client["nom"]}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</body>
</html>
