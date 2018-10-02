<?php
$eventid = $_POST["eventid"];
require_once "getTicketData.php";
$venuestuff = $conn->prepare("SELECT seating FROM venues WHERE venuename = (SELECT eventloc FROM events WHERE eventid = ?)");
$venuestuff->bind_param("s",$eventid);
$venuestuff->execute();
$venueback = mysqli_fetch_assoc($venuestuff->get_result());
$output->tickettypes = $venueback["seating"];
$deliverystuff = $conn->prepare("SELECT deliverytype FROM events WHERE eventid = ?");
$deliverystuff->bind_param("s",$eventid);
$deliverystuff->execute();
$deliverytypes = mysqli_fetch_assoc($deliverystuff->get_result())["deliverytype"];
$output->deliverytypes = $deliverytypes;
$conn->close();
echo json_encode($output);
?>
