<?php
$servername = "crowdsurf.czbxy8tiwkhk.us-east-2.rds.amazonaws.com";
$username = "ballotbox";
$password = "ballotbox69";
$database = "crowdsurf";
$conn = new mysqli($servername,$username,$password,$database);
if($conn->connect_error){
echo nl2br("\n Connection Error");
echo $conn->connect_error;
}
 ?>
