<?php
require "getTicketData.php";
require "protocol.php";
require "../vendor/autoload.php";
require "../config.php";

$sessionid = $_COOKIE["loginid"];

$addrstring = json_decode($_POST["naddress"]);
$cpass = $_POST["pass"];

if(!empty($cpass) && !empty($sessionid) && !empty($addrstring->line1) && !empty($addrstring->city) && !empty($addrstring->state) && !empty($addrstring->zip)){
  $getpass = $conn->prepare("SELECT password, customerid FROM users WHERE sessionid = ?");
  $getpass->bind_param("s",$sessionid);
  $getpass->execute();
  $results = mysqli_fetch_assoc($getpass->get_result());
  if(password_verify($cpass,$results["password"])){
    try{
    $customer = \Stripe\Customer::retrieve(openssl_decrypt($results["customerid"],'AES-256-CBC',$decryption->key,0,$decryption->IV));
    $customer->shipping = array("address" => array("line1" => $addrstring->line1, "line2" => $addrstring->line2, "state" => $addrstring->state, "city" => $addrstring->city, "postal_code" => $addrstring->zip), "name" => $getStripeBuyer->description);
    $customer->save();
    echo 2;
  }catch(Exception $e){
    echo $e->getMessage();
    echo 0;
  }
  }else{
    echo 1;
  }
}else{
  echo 0;
}

$conn->close();
 ?>
