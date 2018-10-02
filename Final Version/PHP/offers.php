<?php
//Sellers must sell tickets together in the same section or individual ticket
require_once "getTicketData.php";

$eventid = $_POST["id"];
$eventloc = $_POST["eventloc"];
$quantity = intval($_POST["q"]);
$belowprice = doubleval($_POST["pr"]);
$tickettype = $_POST["sec"];
$pricecondition = "";
$typecondition = "";
if(empty($quantity) || !is_numeric($quantity)){
  $quantity = 2;
}
if(!is_numeric($belowprice)){
  $belowprice = 0;
}
if(!empty($belowprice)){
  $pricecondition = "AND price <= '$belowprice'";
}
$venueinfo = "SELECT seating, venuepic FROM venues WHERE venuename = ?";
$venueinfo = $conn->prepare($venueinfo);
$venueinfo->bind_param("s",$eventloc);
$venueinfo->execute();
$venueinfo = mysqli_fetch_assoc($venueinfo->get_result());
echo $venueinfo["venuepic"];
echo "#";
echo $venueinfo["seating"];
$foundTicket = false;
$result = $conn->prepare("SELECT section, price, saletype, COUNT(*) AS quantity FROM orders WHERE ticketstatus != 'open' AND eventid = ?$pricecondition GROUP BY section ORDER BY timeprocessed");
$result->bind_param("s",$eventid);
$result->execute();
$finalresult = $result->get_result();
while($row = mysqli_fetch_assoc($finalresult)){
  if($quantity <= $row["quantity"]){
    $foundTicket = true;
    echo "#" . $row["section"] . "#" . $row["price"] . "#" . $row["saletype"];
  }
}
if($foundTicket == false){
  $result = $conn->prepare("SELECT section, price, saletype, COUNT(*) AS quantity FROM orders WHERE ticketstatus = 'open' AND eventid = ? ? GROUP BY section ORDER BY price");
  $result->bind_param("ss",$eventid,$pricecondition);
  $result->execute();
  $finalresult = $result->get_result();
  while($row = mysqli_fetch_assoc($finalresult)){
    if($quantity <= $row["quantity"]){
      $foundTicket = true;
      $price = $row["price"] + 10;
      echo "#" . $row["section"] . "#" . $price . "#" . $row["saletype"];
    }
  }
}
if($foundTicket == false){
  echo "#";
  echo 0;
}
$conn->close();
?>
