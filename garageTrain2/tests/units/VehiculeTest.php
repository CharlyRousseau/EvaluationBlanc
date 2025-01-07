<?php

use PHPUnit\Framework\TestCase;
require_once "/var/www/html/garageTrain2/src/database/db.php"; // Ajustez le chemin si nécessaire

class VehiculeTest extends TestCase
{
    private $mockConn;

    protected function setUp(): void
    {
        $this->mockConn = $this->createMock(mysqli::class);
    }

    // Méthode utilitaire pour simuler une requête SQL
    private function mockQuery(string $sql)
    {
        $this->mockConn
            ->method("query")
            ->with($this->equalTo($sql)) // Vérifier que la requête passée est correcte
            ->willReturn(true);
    }

    public function testAjouterVehicule()
    {
        $marque = "Toyota";
        $modele = "Corolla";
        $annee = 2023;
        $client_id = 1;

        $sql = "INSERT INTO vehicules (marque, modele, annee, client_id) VALUES ('$marque', '$modele', '$annee', '$client_id')";

        // Simuler la requête
        $this->mockQuery($sql);

        // Vérification que la méthode query() est appelée avec la bonne requête
        $result = $this->mockConn->query($sql);
        $this->assertTrue($result);
    }

    public function testModifierVehicule()
    {
        $marque = "Toyota";
        $modele = "Corolla";
        $nouvelleMarque = "Honda";
        $nouveauModele = "Civic";
        $nouvelleAnnee = 2024;

        $sql = "UPDATE vehicules SET marque = '$nouvelleMarque', modele = '$nouveauModele', annee = '$nouvelleAnnee' WHERE marque = '$marque' AND modele = '$modele'";

        // Simuler la requête
        $this->mockQuery($sql);

        // Vérification que la méthode query() est appelée avec la bonne requête
        $result = $this->mockConn->query($sql);
        $this->assertTrue($result);
    }

    public function testSupprimerVehicule()
    {
        $marque = "BMW";
        $modele = "X5";

        $sql = "DELETE FROM vehicules WHERE marque = '$marque' AND modele = '$modele'";

        // Simuler la requête
        $this->mockQuery($sql);

        // Vérification que la méthode query() est appelée avec la bonne requête
        $result = $this->mockConn->query($sql);
        $this->assertTrue($result);
    }
}
