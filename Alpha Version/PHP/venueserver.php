<?php
session_start();
$servername = "ballotboxdbinstance.czbxy8tiwkhk.us-east-2.rds.amazonaws.com";
$username = "ballotbox";
$password = "ballotbox69";
$database = "ballotboxdb";
$conn = new mysqli($servername,$username,$password,$database);
if($conn->connect_error){
echo "Connection Error";
echo $conn->connect_error;
}else if($conn->error){
  echo $conn->error;
}
$eventid = $_GET["id"];
$iteration = $_GET["it"];
switch($iteration){
case 0:
$presqlquery = "SELECT * FROM events WHERE eventid = ";
$sqlquery = $presqlquery . $eventid;
$event = $conn->query($sqlquery);
$conn->close();
$row = $event->fetch_assoc();
echo "<h1>" . $row["eventname"] . "</h1>";
echo "<p>" . $row["eventlocation"] . "</p>";
break;
case 1:
$presqlquery = "SELECT * FROM events WHERE eventid = ";
$sqlquery = $presqlquery . $eventid;
$event = $conn->query($sqlquery);
$conn->close();
$row = $event->fetch_assoc();
echo "<p>" . $row["eventdate"] . "</p>";
break;
case 2:
$presqlquery = "SELECT * FROM seattypes WHERE eventid = ";
$sqlquery = $presqlquery . $eventid;
$seattypes = $conn->query($sqlquery);
$conn->close();
while($row = mysqli_fetch_assoc($seattypes)){
echo "<option value='" . $row["seattype"] . "'>" . $row["seattype"] . "</option>";
}
echo "<option value='any'>Any</option>";
break;
default:
$price = $_GET["sendprice"];
$seattype = $_GET["seattype"];
$quantity = $_GET["quantity"];
$offertime = $_GET["time"];
if($price == "mkt"){
$price = 0;
}
$sqlquery = "INSERT INTO buyers (userid,eventid,price,seattype,quantity,offertime) VALUES (0,$eventid,$price,'$seattype',$quantity,$offertime)";
if($conn->query($sqlquery) === true){
}else{
echo $conn->error;
}
$conn->close();
}
?>
