<?php
if(isset($_COOKIE["loginid"])){
$sessionid = $_COOKIE["loginid"];
require_once "../config.php";
require_once "getTicketData.php";
require_once "protocol.php";

$prepit = $conn->prepare("SELECT totaltickets, referralcode, email, customerid FROM users WHERE sessionid = ?");
$prepit->bind_param("s",$sessionid);
$prepit->execute();
$userinfo = mysqli_fetch_assoc($prepit->get_result());
$output->email = openssl_decrypt($userinfo["email"],"AES-256-CBC",$decryption->key,0,$decryption->IV);
$customer = \Stripe\Customer::retrieve(openssl_decrypt($userinfo["customerid"],"AES-256-CBC",$decryption->key,0,$decryption->IV));
$output->fullname = $customer->description;
$output->moneyowed = 0;
$output->rdollars = 0;
if(empty($customer->account_balance)){
  $output->moneyowed = 0;
}else{
$output->moneyowed = floatval($customer->account_balance) / 100;
}
if(!empty($customer->shipping->address)){
  $output->address = json_encode($customer->shipping->address);
}else{
  $output->address = "None stored on file";
}
$paymentmethods = "";
for($i = 0; $i < sizeof($customer->sources->data); $i++){
  if($customer->sources->data[$i]->status != "consumed"){
  $paymentmethods = $paymentmethods . "<option value='" . $customer->sources->data[$i]->id . "'>" . ucfirst($customer->sources->data[$i]->brand) . " " . $customer->sources->data[$i]->last4 . "</option>";
  }
}
$output->paymentmethods = $paymentmethods;
/*
if(empty($customer->discount->coupon->amount_off)){
  $output->rdollars = 0;
}else{
  $output->rdollars = floatval($customer->discount->coupon->amount_off) / 100;
}
$output->referralcode = $userinfo["referralcode"];
*/
$output->orderhistory = "<tr>
<th><h3>Event Name</h3></th>
<th><h3>Buy/Sell</h3></th>
<th><h3>Price</h3></th>
<th><h3>Quantity</h3></th>
<th><h3>Seat Information</h3></th>
<th><h3>Sale type</h3></th>
<th><h3>Order Status</h3></th>
<td></td>
</tr>";
$result = $conn->prepare("SELECT (SELECT eventname FROM events WHERE eventid = off.eventid) AS eventname, (SELECT eventloc FROM events WHERE eventid = off.eventid) AS eventloc, 'Buy' AS BS, price, quantity, CASE WHEN off.ticketstatus = 'Filled' THEN 'Assigned' ELSE 'Not Assigned Yet' END AS tickettype, CASE WHEN off.ticketstatus = 'Open' THEN (SELECT shipping FROM offers WHERE token = off.token GROUP BY token) END AS saletype, ticketstatus, token, timeprocessed FROM offers AS off WHERE email = ? UNION SELECT (SELECT eventname FROM events WHERE eventid = orders.eventid) AS eventname, (SELECT eventloc FROM events WHERE eventid = orders.eventid) AS eventloc, 'Sell' AS BS, price, COUNT(sell_id) AS quantity, 'Sell' AS tickettype, saletype, ticketstatus, ticketid, timeprocessed FROM orders WHERE userid = ? GROUP BY sell_id ORDER BY timeprocessed DESC");
$result->bind_param("ss",$userinfo["email"],$userinfo["email"]);
$result->execute();
$finalresult = $result->get_result();
while($row = mysqli_fetch_assoc($finalresult)){
  $status = $row["ticketstatus"];
  $token = $row["token"];
  $canCancel = true;
  if(!empty($row["eventname"])){
  $output->orderhistory = $output->orderhistory .  "<tr>";
  $output->orderhistory = $output->orderhistory .  "<th><p>" . $row["eventname"] . "</p>\n<p>" . $row["eventloc"] . "</p></th>";
  $output->orderhistory = $output->orderhistory .  "<th><p>" . $row["BS"] . "</p></th>";
  if($row["price"] == 0){
    $output->orderhistory = $output->orderhistory .  "<th><p>Sell Guarantee</p></th>";
  }else{
    $output->orderhistory = $output->orderhistory .  "<th><p>$" . number_format(doubleval($row["price"]),2,'.','') . "</p></th>";
  }
  $output->orderhistory = $output->orderhistory .  "<th><p>" . $row["quantity"] . "</p></th>";
  if($row["tickettype"] == 'Assigned'){
    $getAllids = $conn->prepare("SELECT ticketid FROM orderprocessing WHERE orderid = ?");
    $getAllids->bind_param("s",$token);
    $getAllids->execute();
    $output->orderhistory = $output->orderhistory .  "<th>";
    $finalresult = $getAllids->get_result();
    while($eachTicket = mysqli_fetch_assoc($finalresult)){
      $ticketid = $eachTicket["ticketid"];
      $prepareit = $conn->prepare("SELECT CONCAT('Section: ', section, ' Row: ', row,' Seat Number: ',seatnum) AS tickettype FROM orders WHERE ticketid = ?");
      $prepareit->bind_param("s",$ticketid);
      $prepareit->execute();
      $seating = mysqli_fetch_assoc($prepareit->get_result())["tickettype"];
      $output->orderhistory = $output->orderhistory .  "<p>$seating</p>\n";
    }
    $output->orderhistory = $output->orderhistory .  "</th>";
  }else if($row["tickettype"] == 'Sell'){
    $theuser = $userinfo["email"];
    $output->orderhistory = $output->orderhistory .  "<th>";
      $seating = $conn->prepare("SELECT CONCAT('Section: ', section, ' Row: ', row,' Seat Number: ',seatnum) AS tickettype FROM orders WHERE userid = ? AND ticketid = ?");
      $seating->bind_param("ss",$theuser,$token);
      $seating->execute();
      $tickettype = mysqli_fetch_assoc($seating->get_result())["tickettype"];
      $output->orderhistory = $output->orderhistory .  "<p>$tickettype</p>\n";
    $output->orderhistory = $output->orderhistory .  "</th>";
  }else{
  $output->orderhistory = $output->orderhistory .  "<th><p>" . $row["tickettype"] . "</p></th>";
  }
  $output->orderhistory = $output->orderhistory .  "<th><p>" . ucwords($row["saletype"]) . "</p></th>";
  $status = ucwords($row["ticketstatus"]);
  if($status != "Open" && $status != "Hold" && $status != "Processing Proof"){
    $canCancel = false;
  }
  $output->orderhistory = $output->orderhistory .  "<th><p>" . $status . "</p></th>";
  $howToCancel = "";
  $inputbutton = "<button class='stdbutton' type='button' value='" . $row["token"] . "' onclick='cancelorder(event)'>Cancel Order</button>";
  if($canCancel == false){
    $inputbutton = "";
  }
  $output->orderhistory = $output->orderhistory .  "<td>$inputbutton</td>";
  $output->orderhistory = $output->orderhistory .  "</tr>";
  }
}
$conn->close();
echo json_encode($output);
}
 ?>
