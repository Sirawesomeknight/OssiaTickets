<?php
$eventid = $_POST["id"];
$isLoggedIn = $_POST["loggedin"];
$eventloc = $_POST["eventloc"];
$sessionid = $_COOKIE["loginid"];
require "getTicketData.php";
require "protocol.php";
require "../config.php";
$prep = $conn->prepare("SELECT totaltickets, customerid FROM users WHERE sessionid = ?");
$prep->bind_param("s",$sessionid);
$prep->execute();
$thedata = mysqli_fetch_assoc($prep->get_result());
$output->usertotaltickets = $thedata["totaltickets"];
$output->userreferralmoney = 0;
try{
$getCustomer = \Stripe\Customer::retrieve(openssl_decrypt($thedata["customerid"],"AES-256-CBC",$decryption->key,0,$decryption->IV));
$output->userreferralmoney = floatval($getCustomer->discount->coupon->amount_off) / 100;
}catch(Exception $e){
  $output->userreferralmoney = 0;
}
$prep = $conn->prepare("SELECT backgroundimg FROM venues WHERE venuename = ?");
$prep->bind_param("s",$eventloc);
$prep->execute();
$venueback = mysqli_fetch_assoc($prep->get_result());
$output->backgroundimage = $venueback["backgroundimg"];
$conn->close();
echo json_encode($output);
?>
