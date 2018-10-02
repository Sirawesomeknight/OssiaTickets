<?php
$sessionid = $_COOKIE["loginid"];
require "getTicketData.php";
require "protocol.php";
require "../config.php";
$result = $conn->prepare("SELECT customerid FROM users WHERE sessionid = ?");
$result->bind_param("s",$sessionid);
$result->execute();
$customerid = mysqli_fetch_assoc($result->get_result())["customerid"];
$customerid = openssl_decrypt($customerid,"AES-256-CBC",$decryption->key,0,$decryption->IV);
$customercards = \Stripe\Customer::retrieve($customerid)->sources->all(array(
  "object" => "card"
));
$cardstring = "";
for($i = 0; $i < sizeof($customercards); $i++){
  $cardstring = $cardstring . "<option value='" . $customercards[$i]->id . "'>" . $customercards[$i]->brand . " " . $customercards[$i]->last4 . "</option>";
}
$conn->close();
echo $cardstring;
 ?>
