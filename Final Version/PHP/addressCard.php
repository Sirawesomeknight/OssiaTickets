<?php
require "getTicketData.php";
require "protocol.php";
require "../config.php";
$addressCard = $conn->prepare("SELECT customerid FROM users WHERE sessionid = ?");
$addressCard->bind_param("s",$_COOKIE["loginid"]);
$addressCard->execute();
$customerid = openssl_decrypt(mysqli_fetch_assoc($addressCard->get_result())["customerid"],'AES-256-CBC',$decryption->key,0,$decryption->IV);
$customer = \Stripe\Customer::retrieve($customerid);
if(!empty($customer->shipping->address)){
  $userdata->getAddress = json_encode($customer->shipping->address);
}else{
  $userdata->getAddress = "none";
}
$userdata->getCards = "<option value='chpayments'>Add Payment Method...</option>";
for($i = 0; $i < sizeof($customer->sources->data); $i++){
  if($customer->sources->data[$i]->status != "consumed"){
  $userdata->getCards = $userdata->getCards . "<option value='" . $customer->sources->data[$i]->id . "'>" . ucfirst($customer->sources->data[$i]->brand) . " " . $customer->sources->data[$i]->last4 . "</option>";
  }
}
echo json_encode($userdata);
$conn->close();
?>
