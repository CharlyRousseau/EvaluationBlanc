<?php
session_start();

require_once "../src/database/db.php";
$conn = connectDB();

$result = $conn->query("SELECT COUNT(*) AS total_clients FROM clients");
$row = $result->fetch_assoc();
$totalClients = $row["total_clients"];

$result = $conn->query("SELECT COUNT(*) AS total_vehicules FROM vehicules");
$row = $result->fetch_assoc();
$totalVehicules = $row["total_vehicules"];

$result = $conn->query("SELECT COUNT(*) AS total_rendezvous FROM rendezvous");
$row = $result->fetch_assoc();
$totalRendezvous = $row["total_rendezvous"];

$vehiculesResult = $conn->query(
    "SELECT v.id, v.marque, v.modele, v.annee, c.nom AS client FROM vehicules v LEFT JOIN clients c ON v.client_id = c.id"
);
$vehicules = $vehiculesResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/styles.min.css">
    <title>Tableau de Bord Garage Train</title>
</head>
<body>
    <h1>Tableau de Bord Garage Train</h1>
    <div>
        <h2>Clients</h2>
        <p>Total Clients: <?= $totalClients ?></p>
    </div>
    <div>
        <h2>Véhicules</h2>
        <p>Total Véhicules: <?= $totalVehicules ?></p>
    </div>
    <div>
        <h2>Rendez-vous</h2>
        <p>Total Rendez-vous: <?= $totalRendezvous ?></p>
    </div>
    <h2>Véhicules</h2>
    <p>Total Véhicules: <?= $totalVehicules ?></p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Année</th>
                <th>Client</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicules as $vehicule): ?>
                <tr>
                    <td><?= $vehicule["id"] ?></td>
                    <td><?= $vehicule["marque"] ?></td>
                    <td><?= $vehicule["modele"] ?></td>
                    <td><?= $vehicule["annee"] ?></td>
                    <td><?= $vehicule["client"] ?></td>
                    <td>
                        <a href="modifier.php?id=<?= $vehicule[
                            "id"
                        ] ?>">Modifier</a>
                        <a href="supprimer.php?id=<?= $vehicule[
                            "id"
                        ] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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
