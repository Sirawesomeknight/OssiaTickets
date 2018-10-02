<?php
$servername = "ballotboxdbinstance.czbxy8tiwkhk.us-east-2.rds.amazonaws.com";
$username = "ballotbox";
$password = "ballotbox69";
$database = "ballotboxdb";
$conn = new mysqli($servername,$username,$password,$database);
if($conn->connect_error){
echo "Connection Error";
}
$iteration = $_GET["it"];
switch($iteration){
case 0:
$presqlquery = "SELECT * FROM events";
$sqlquery = $presqlquery . $eventid;
$events = $conn->query($sqlquery);
$conn->close();
$eventid = 0;
echo "<option value='Default'>Default</option>";
while($row = mysqli_fetch_assoc($events)){
echo "<option value='" . $eventid . "''>" . $row["eventname"] . "</option>";
$eventid++;
}
break;
case 1:
$eventid = $_GET["ed"];
$presqlquery = "SELECT * FROM events WHERE eventid =";
$sqlquery = $presqlquery . $eventid;
$seattypes = $conn->query($sqlquery);
$conn->close();
$row = mysqli_fetch_assoc($seattypes);
echo $row["eventdate"];
break;
case 2:
$eventid = $_GET["eventid"];
$price = $_GET["sendprice"];
$seattype = $_GET["seattype"];
$quantity = $_GET["quantity"];
$offertime = $_GET["time"];
if($price == "mkt"){
$price = 0;
}
$sqlquery = "INSERT INTO sellers (userid,eventid,price,seattype,quantity,offertime) VALUES (0,$eventid,$price,'$seattype',$quantity,$offertime)";
if($conn->query($sqlquery) === true){
}else{
echo $conn->error;
}
$conn->close();
break;
case 3:
$eventid = $_GET["ed"];
  $presqlquery = "SELECT * FROM seattypes WHERE eventid = ";
  $sqlquery = $presqlquery . $eventid;
  $seattypes = $conn->query($sqlquery);
  $conn->close();
  while($row = mysqli_fetch_assoc($seattypes)){
  echo "<option value='" . $row["seattype"] . "'>" . $row["seattype"] . "</option>";
}
break;
}
?>
