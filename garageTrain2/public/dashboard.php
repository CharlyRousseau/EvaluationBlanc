<?php
session_start();

require_once "../src/database/db.php";
$conn = connectDB();

// Génération d'un token CSRF s'il n'existe pas déjà
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Sécurisation des requêtes SQL par requêtes préparées
$stmt = $conn->prepare("SELECT COUNT(*) AS total_clients FROM clients");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalClients = $row["total_clients"];

$stmt = $conn->prepare("SELECT COUNT(*) AS total_vehicules FROM vehicules");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalVehicules = $row["total_vehicules"];

$stmt = $conn->prepare("SELECT COUNT(*) AS total_rendezvous FROM rendezvous");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalRendezvous = $row["total_rendezvous"];

// Sécurisation des données pour les véhicules
$vehiculesStmt = $conn->prepare(
    "SELECT v.id, v.marque, v.modele, v.annee, c.nom AS client FROM vehicules v LEFT JOIN clients c ON v.client_id = c.id"
);
$vehiculesStmt->execute();
$vehiculesResult = $vehiculesStmt->get_result();
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
        <p>Total Clients: <?= htmlspecialchars($totalClients) ?></p>
    </div>
    <div>
        <h2>Véhicules</h2>
        <p>Total Véhicules: <?= htmlspecialchars($totalVehicules) ?></p>
    </div>
    <div>
        <h2>Rendez-vous</h2>
        <p>Total Rendez-vous: <?= htmlspecialchars($totalRendezvous) ?></p>
    </div>
    <h2>Véhicules</h2>
    <p>Total Véhicules: <?= htmlspecialchars($totalVehicules) ?></p>

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
                    <td><?= htmlspecialchars($vehicule["id"]) ?></td>
                    <td><?= htmlspecialchars($vehicule["marque"]) ?></td>
                    <td><?= htmlspecialchars($vehicule["modele"]) ?></td>
                    <td><?= htmlspecialchars($vehicule["annee"]) ?></td>
                    <td><?= htmlspecialchars($vehicule["client"]) ?></td>
                    <td>
                        <a href="modifier.php?id=<?= htmlspecialchars(
                            $vehicule["id"]
                        ) ?>">Modifier</a>
                        <a href="supprimer.php?id=<?= htmlspecialchars(
                            $vehicule["id"]
                        ) ?>"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h2>Ajouter un véhicule</h2>
    <form action="ajouter.php" method="POST">
        <!-- Token CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION[
            "csrf_token"
        ] ?>">

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
                $clientsStmt = $conn->prepare("SELECT id, nom FROM clients");
                $clientsStmt->execute();
                $clientsResult = $clientsStmt->get_result();
                while ($client = $clientsResult->fetch_assoc()) {
                    echo "<option value=\"" .
                        htmlspecialchars($client["id"]) .
                        "\">" .
                        htmlspecialchars($client["nom"]) .
                        "</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</body>
</html>
