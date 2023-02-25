<?php 

include("../connection.php");

$name = $_POST['username'];

$comment = $_POST['comment'];
if ($check = preg_match("/[']/m", $comment)) {
    header("Location: ../index.php");
    die();
    return;
}

$date = date('Y/m/d H:i:s');

$sql = "INSERT INTO comment(username, commenttext, datepublished) VALUES('$name', '$comment', '$date')";
$conn->query($sql);
$conn->close();

header("Location: ../index.php");
die();

?>