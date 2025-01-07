<?php

use Behat\Behat\Context\Context;
use Behat\Hook\BeforeFeature;
use Behat\Step\Given;
use Behat\Step\When;
use Behat\Step\Then;
use Behat\Gherkin\Node\TableNode;

class FeatureContext implements Context
{
    private $mysqli;

    public function __construct()
    {
        // Optionally, initialize context here
    }

    #[BeforeFeature]
    public static function prepareForTheFeature()
    {
        // You can clean up or prepare your database before the feature runs
    }

    #[Given("I am connected to the database")]
    public function iAmConnectedToTheDatabase()
    {
        // Database connection parameters
        $host = "db";
        $username = "root";
        $password = "root";
        $database = "garaga_train2";

        // Create connection
        $this->mysqli = new mysqli($host, $username, $password, $database);

        // Check connection
        if ($this->mysqli->connect_error) {
            throw new \Exception(
                "Database connection failed: " . $this->mysqli->connect_error
            );
        }
    }

    #[When("I add a vehicle with the following details:")]
    public function iAddAVehicle(TableNode $table)
    {
        // Iterate over each row in the table
        foreach ($table as $row) {
            // Assuming 'marque', 'modele', 'annee', and 'client_id' are the columns
            $marque = $row["marque"]; // Accessing the value of 'marque' column in the row
            $modele = $row["modele"];
            $annee = $row["annee"];
            $clientId = $row["client_id"];

            // Insert the vehicle into the database
            $query = "INSERT INTO vehicules (marque, modele, annee, client_id)
                      VALUES ('$marque', '$modele', '$annee', '$clientId')";

            if (!$this->mysqli->query($query)) {
                throw new \Exception(
                    "Error adding vehicle: " . $this->mysqli->error
                );
            }
        }
    }

    #[Then("the vehicle should be added to the database")]
    public function theVehicleShouldBeAddedToTheDatabase()
    {
        // Verify the vehicle was added to the database
        $result = $this->mysqli->query(
            "SELECT * FROM vehicules WHERE marque = 'Toyota' AND modele = 'Corolla' AND annee = 2023"
        );

        if ($result->num_rows === 0) {
            throw new \Exception("Vehicle not found in the database");
        }
    }
}
