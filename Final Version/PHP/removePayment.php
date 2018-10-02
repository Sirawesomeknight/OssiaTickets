<?php
if(isset($_COOKIE["loginid"])){
require "getTicketData.php";
require "protocol.php";
require "../config.php";
$paymentid = $_POST["paymentid"];
$addressCard = $conn->prepare("SELECT customerid FROM users WHERE sessionid = ?");
$addressCard->bind_param("s",$_COOKIE["loginid"]);
$addressCard->execute();
$customerid = openssl_decrypt(mysqli_fetch_assoc($addressCard->get_result())["customerid"],'AES-256-CBC',$decryption->key,0,$decryption->IV);
$customer = \Stripe\Customer::retrieve($customerid);
$customer->sources->retrieve($paymentid)->delete();
$conn->close();
echo 1;
}else{
  echo 0;
}
 ?>
