<?php

function connectDB()
{
    $host = "db";
    $user = "root";
    $password = "root";
    $database = "garaga_train2";
    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die(
            "La connexion à la base de données a échoué : " .
                $conn->connect_error
        );
    } else {
        return $conn;
    }
}
