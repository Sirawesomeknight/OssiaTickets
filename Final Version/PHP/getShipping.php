<?php
require "getTicketData.php";
$cartinfo = json_decode(urldecode($_COOKIE["cartinfo"]));
if($cartinfo->delivery == "Shipping"){
  $getPickup = $conn->prepare("SELECT eventdate, pickup FROM events WHERE eventid = ?");
  $getPickup->bind_param("s",$cartinfo->eventid);
  $getPickup->execute();
  $result = mysqli_fetch_assoc($getPickup->get_result());
  if($result["pickup"] == true){
    $output->canPickup = "<option value='wcall'>Will Call/Pickup</option>";
  }else{
    $output->canPickup = "";
  }
  $timeToEvent = strtotime($result["eventdate"]) - time();
  $numdays = $timeToEvent / 60 / 60 / 24;
  $output->shiptype = "";
  if($numdays <= 3){
    $output->shiptype = "<option value='expr'>Express Shipping (USPS)</option>";
  }else if($numdays <= 7){
    $output->shiptype = "<option value='prio'>Priority Shipping (USPS)</option>";
  }else if($numdays > 7){
    $output->shiptype = "<option value='reg'>Regular Shipping (USPS)</option>";
  }else{
    $output->shiptype = "none";
  }
  echo json_encode($output);
}else{
  echo "none";
}

 ?>
