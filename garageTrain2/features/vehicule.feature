Feature: Vehicle management

  Scenario: Adding a new vehicle
    Given I am connected to the database
    When I add a vehicle with the following details:
      | marque  | modele   | annee | client_id |
      | Toyota  | Corolla | 2023 | 1         |
    Then the vehicle should be added to the database
