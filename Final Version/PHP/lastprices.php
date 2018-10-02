<?php
require "getTicketData.php";

$eventid = $_POST["eventid"];
$ticketclass = $_POST["ticketclass"];
$ticketstring = "";
if(!empty($ticketclass)){
  $ticketstring = " AND ticketclass = '$ticketclass'";
}
//$foundTicket = false;
$output = "<tr><td>Section</td><td>Last Price</td></tr>";
//$sectionsOutputted = "";
$result = $conn->prepare("SELECT section, price, saletype FROM orders WHERE ticketstatus != 'Open' AND price > 0 AND section != '' AND eventid = ?$ticketstring ORDER BY timeprocessed");
$result->bind_param("s",$eventid);
$result->execute();
$finalresult = $result->get_result();
while($row = mysqli_fetch_assoc($finalresult)){
    //$foundTicket = true;
    $output = $output . "<tr><td><h3>" . $row["section"] . "</h3></td><td><h3>$" . number_format($row["price"],2) . "</h3><p>" . $row["saletype"] . "</p></td></tr>";
    //$sectionsOutputted = $sectionsOutputted . $row["section"] . ",";
}
/*
$result = $conn->prepare("SELECT section, price, saletype FROM orders WHERE ticketstatus = 'Open' OR ticketstatus = 'Example' AND price > 0 AND section != '' AND eventid = ? $ticketstring GROUP BY section ORDER BY price");
$result->bind_param("s",$eventid);
$result->execute();
$finalresult = $result->get_result();
while($row = mysqli_fetch_assoc($finalresult)){
  $section = $row["section"];
  if(strpos($sectionsOutputted,$section) === false){
    $foundTicket = true;
    $price = doubleval($row["price"]) + 10;
    $output = $output . "<tr><td><h3>" . $section . "</h3></td><td><h3>$" . number_format($price,2) . "</h3><p>" . $row["saletype"] . "</p></td></tr>";
  }
}
*/
echo $output;
$conn->close();
?>
