<?php
if(isset($_COOKIE["loginid"])){
$sessionid = $_COOKIE["loginid"];
require "getTicketData.php";

$orderid = str_replace(" ","+",$_POST["cancelorder"]);

$preparebuy = $conn->prepare("SELECT token FROM offers WHERE token = ? AND email = (SELECT email FROM users WHERE sessionid = ?) AND ticketstatus = 'Open'");
$preparebuy->bind_param("ss",$orderid,$sessionid);
$preparebuy->execute();
$buyer = mysqli_fetch_assoc($preparebuy->get_result())["token"];
$preparesell = $conn->prepare("SELECT ticketid FROM orders WHERE ticketid = ? AND userid = (SELECT email FROM users WHERE sessionid = ?) AND ticketstatus = 'Open' OR ticketstatus = 'Hold' OR ticketstatus = 'Processing Proof'");
$preparesell->bind_param("ss",$orderid,$sessionid);
$preparesell->execute();
$seller = mysqli_fetch_assoc($preparesell->get_result())["ticketid"];
if(!empty($buyer) && !empty($orderid)){
  $conn->query("DELETE FROM offers WHERE token = '$buyer'");
  echo 1;
}else if(!empty($seller) && !empty($orderid)){
  $conn->query("DELETE FROM orders WHERE ticketid = '$seller'");
  echo 1;
}else{
  echo 0;
}
$conn->close();
}else{
  echo 0;
}
?>
