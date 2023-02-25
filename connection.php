<?php

$conn = new mysqli("localhost", "root", "", "db_space_evader");

if ($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
}

?>