<?php
if (isset($_GET["ID"])) {
$ID = $_GET["ID"];

  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "student";

  // Create connection
  $connection = new mysqli($servername, $username, $password, $database);

  $sql = "DELETE FROM userinformation WHERE ID=$ID";
  $connection->query($sql);
}
header("location: /student1/index.php");


?>