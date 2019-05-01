<?php
function connectShopDB()
{
    $servername = "localhost";
    $username = "shop";
    $password = "hYCIkdc2RDghvxAH";
    $dbname = "arabcci_shop";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        return $conn;
    }
}