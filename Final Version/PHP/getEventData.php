<?php
require "getTicketData.php";
$eventid = $_POST["eventid"];
$getEvents = $conn->prepare("SELECT eventname, eventloc, eventdate, deliverytype, eventprof FROM events WHERE eventid = ?");
$getEvents->bind_param("s",$eventid);
$getEvents->execute();
$data = mysqli_fetch_assoc($getEvents->get_result());
$eventdata->eventname = $data["eventname"];
$eventdata->eventloc = $data["eventloc"];
$eventdata->eventdate = $data["eventdate"];
$eventdata->eventprof = $data["eventprof"];
$eventdata->delivery = $data["deliverytype"];
echo json_encode($eventdata);
$conn->close();
 ?>
