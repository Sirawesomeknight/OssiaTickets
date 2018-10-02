<?php
require "getTicketData.php";
require "protocol.php";
require "../vendor/autoload.php";
require "../config.php";
$sessionid = $_COOKIE["loginid"];

if(!empty($sessionid)){
  $getid = $conn->prepare("SELECT customerid FROM users WHERE sessionid = ?");
  $getid->bind_param("s",$sessionid);
  $getid->execute();
  try{
  $customer = \Stripe\Customer::retrieve(openssl_decrypt(mysqli_fetch_assoc($getid->get_result())["customerid"],"AES-256-CBC",$decryption->key,0,$decryption->IV));
  $shippingAddress = $customer->shipping->address;
  if(!empty($shippingAddress)){
  echo "<br>";
  echo $shippingAddress->line1 . "<br>";
  if(!empty($shippingAddress->line2)){
    echo $shippingAddress->line2 . "<br>";
  }
  echo $shippingAddress->city . "," . $shippingAddress->state . "<br>";
  echo $shippingAddress->postal_code;
}catch(Exception $e){
  echo 0;
}
}else{
  echo 0;
}
}else{
  echo 0;
}



 ?>
