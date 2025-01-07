<?php
require_once "../src/database/db.php";
$conn = connectDB();

$id = $_GET["id"];
$vehiculeResult = $conn->query("SELECT * FROM vehicules WHERE id = $id");
$vehicule = $vehiculeResult->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $marque = $_POST["marque"];
    $modele = $_POST["modele"];
    $annee = $_POST["annee"];
    $client_id = $_POST["client_id"];

    $conn->query(
        "UPDATE vehicules SET marque='$marque', modele='$modele', annee='$annee', client_id='$client_id' WHERE id = $id"
    );

    header("Location: dashboard.php"); // Rediriger après modification
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <title>Modifier un véhicule</title>
</head>
<body>
    <h2>Modifier un véhicule</h2>
    <form action="modifier.php?id=<?= $id ?>" method="POST">
        <div class="form-group">
            <label for="marque">Marque</label>
            <input type="text" class="form-control" id="marque" name="marque" value="<?= $vehicule[
                "marque"
            ] ?>" required>
        </div>
        <div class="form-group">
            <label for="modele">Modèle</label>
            <input type="text" class="form-control" id="modele" name="modele" value="<?= $vehicule[
                "modele"
            ] ?>" required>
        </div>
        <div class="form-group">
            <label for="annee">Année</label>
            <input type="number" class="form-control" id="annee" name="annee" value="<?= $vehicule[
                "annee"
            ] ?>" required>
        </div>
        <div class="form-group">
            <label for="client">Client</label>
            <select class="form-control" id="client" name="client_id">
                <option value="<?= $vehicule[
                    "client_id"
                ] ?>" selected><?= $vehicule["client"] ?></option>
                <?php
                $clientsResult = $conn->query("SELECT id, nom FROM clients");
                while ($client = $clientsResult->fetch_assoc()) {
                    echo "<option value=\"{$client["id"]}\">{$client["nom"]}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>
</body>
</html>
