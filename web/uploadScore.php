<?php

include("../connection.php");

$n = $_GET["n"];
$s = $_GET["s"];

if ($n == "" && $s == "") { 
    echo "Error occured: " + $conn->error;
    header("Location: ../index.php");
}

$sql = "INSERT INTO player(username, score) VALUES('$n', '$s')";

$conn->query($sql);

$conn->close();

header("Location: ../index.php");
die();

?>