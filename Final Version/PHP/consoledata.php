<?php
require "getTicketData.php";
require "protocol.php";
$result = $conn->query("SELECT sell_id, uploadedproof FROM orders WHERE ticketstatus = 'Processing Proof' GROUP BY sell_id");
$output->orderdata = "<tr><td>Sell ID</td><td>Proof Name</td><td>Outcome</td></tr>";
while($row = mysqli_fetch_assoc($result)){
  $ticketid = $row["sell_id"];
  $uploadedproof = $row["uploadedproof"];
  $output->orderdata = $output->orderdata . "<tr>";
  $output->orderdata = $output->orderdata . "<td>$ticketid</td>";
  $output->orderdata = $output->orderdata . "<td>$uploadedproof</td>";
  $output->orderdata = $output->orderdata . "<td><button onclick=approveOrder('$ticketid')>Approve</button>";
  $output->orderdata = $output->orderdata . "<button onclick=holdOrder('$ticketid')>Hold</button>";
  $output->orderdata = $output->orderdata . "<button onclick=denyOrder('$ticketid')>Deny</button></td>";
  $output->orderdata = $output->orderdata . "</tr>";
}
$result = $conn->query("SELECT * FROM orderprocessing WHERE status = 'processing'");
$output->orderfulfill = "<tr><td>Seller ID</td><td>Buyer ID</td><td>Sale Type</td><td>Time Processed</td><td>Ticket ID</td><td>Order ID/Token</td><td>Sell Price</td><td>Buy Price</td><td>Status</td><td>Completed</td></tr>";
while($row = mysqli_fetch_assoc($result)){
$buyerid = $row["buyerid"];
$sellerid = $row["sellerid"];
$saletype = $row["saletype"];
$time = $row["timeprocessed"];
$ticketid = $row["ticketid"];
$orderid = $row["orderid"];
$sellprice = $row["sellersprice"];
$buyprice = $row["buyersprice"];
$status = $row["status"];
$output->orderfulfill = $output->orderfulfill . "<tr><td>$sellerid</td><td>$buyerid</td><td>$saletype</td><td>$time</td><td>$ticketid</td><td>$orderid</td><td>$sellprice</td><td>$buyprice</td><td>$status</td>";
$output->orderfulfill = $output->orderfulfill . "<td><button onclick=fulfill('$ticketid')>Fulfill</button></td></tr>";
}
$result = $conn->query("SELECT email FROM users");
$output->users = "<tr><td>Email Decoded</td><td>Email Encoded</td></tr>";
while($row = mysqli_fetch_assoc($result)){
  $email = $row["email"];
  $emaildecoded = openssl_decrypt($row["email"],"AES-256-CBC",$decryption->key,0,$decryption->IV);
  $output->users = $output->users . "<tr><td>$emaildecoded<td></td><td>$email</td></tr>";
}
$result = $conn->query("SELECT eventname, eventloc, eventdate, canSearch FROM events");
$output->events = "<tr><td>Event Name</td><td>Event Date</td><td>Event Location</td><td>Can Search?</td></tr>";
while($row = mysqli_fetch_assoc($result)){
  $eventname = $row["eventname"];
  $eventdate = $row["eventdate"];
  $eventloc = $row["eventloc"];
  $canSearch = $row["canSearch"];
  $output->events = $output->events . "<tr><td>$eventname</td><td>$eventdate</td><td>$eventloc</td><td>$canSearch</td></tr>";
}

echo json_encode($output);
$conn->close();
 ?>
