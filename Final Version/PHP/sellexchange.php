<?php
$command = $_POST["param"];
$sell_id = str_replace(" ","+",$_POST["ticketid"]);
if(!empty($command) && !empty($sell_id)){
switch($command){
  case "approve":
    approve($sell_id);
  break;
  case "deny":
    deny($sell_id);
  break;
  case "hold":
    hold($sell_id);
  break;
  case "fulfill":
    fulfill($sell_id);
  break;
  default:
    echo "unknown command";
  break;
}
}else{
  echo "insufficient parameters";
  echo "command" . $command;
  echo "sell_id" . $sell_id;
}

function fulfill($sell_id){
  require "getTicketData.php";
  require "protocol.php";
  $conn->query("UPDATE orderprocessing SET status = 'complete' WHERE ticketid = '$sell_id'");
  $conn->close();
  echo 1;
}

function hold($sell_id){
  require "getTicketData.php";
  require "protocol.php";
  $conn->query("UPDATE orders SET ticketstatus = 'Hold' WHERE sell_id = '$sell_id'");
  $conn->close();
  echo 1;
}

function deny($sell_id){
  require "getTicketData.php";
  require "protocol.php";
  $conn->query("DELETE FROM orders WHERE sell_id = '$sell_id'");
  $conn->close();
  echo 1;
}

function approve($sell_id){
require "getTicketData.php";
require "protocol.php";
$conn->query("UPDATE orders SET ticketstatus = 'Open' WHERE sell_id = '$sell_id'");
$getOffers = $conn->query("SELECT token, price, ticketclass, eventid, email, shipping, quantity FROM offers WHERE eventid = (SELECT eventid FROM orders WHERE sell_id = '$sell_id' GROUP BY sell_id) AND ticketclass = (SELECT ticketclass FROM orders WHERE sell_id = '$sell_id' GROUP BY sell_id) ORDER BY price");
while($row = mysqli_fetch_assoc($getOffers)){
$price = $row["price"];
$ticketclass = $row["ticketclass"];
$eventid = $row["eventid"];
$user = $row["email"];
$deliverytype = $row["shipping"];
$quantity = $row["quantity"];
$token = $row["token"];
$preparedresult = "";
if($deliverytype != "Online"){
  $preparedresult = $conn->prepare("SELECT ryan.sell_id AS sell_id, ryan.quant AS quantity FROM (SELECT AVG(price) AS avgpr, userid, sell_id, ticketstatus, ticketclass, eventid, COUNT(ticketid) AS quant FROM orders WHERE ticketstatus = 'Open' GROUP BY sell_id ORDER BY price) AS ryan WHERE ryan.avgpr <= ? AND ryan.ticketclass = ? AND ryan.eventid = ? AND ryan.userid != ?");
}else{
  $preparedresult = $conn->prepare("SELECT ryan.sell_id AS sell_id, ryan.quant AS quantity FROM (SELECT AVG(price) AS avgpr, userid, sell_id, ticketstatus, ticketclass, eventid, COUNT(ticketid) AS quant FROM orders WHERE ticketstatus = 'Open' GROUP BY sell_id, userid ORDER BY price) AS ryan WHERE ryan.avgpr <= ? AND ryan.ticketclass = ? AND ryan.eventid = ? AND ryan.userid != ?");
}
$adjustedprice = $price * 1.03 + 0.25;
$preparedresult->bind_param("dsss",$adjustedprice,$ticketclass,$eventid,$user);
$preparedresult->execute();
$sellerquantity = 0;
$initialresult = $preparedresult->get_result();
$ids = array("");
while($result = mysqli_fetch_assoc($initialresult)){
  if($result["quantity"] + $sellerquantity <= $quantity){
    $ids[sizeof($ids) - empty($ids[0])] = $result["sell_id"];
    $sellerquantity += $result["quantity"];
  }
}
$executed = false;
if($sellerquantity == $quantity){
  $executed = true;
}

if($executed == true){
  $prepared = $conn->prepare("UPDATE orders SET sell_id = ?, ticketstatus = 'Processing' WHERE sell_id = ?");
  for($i = 0; $i < sizeof($ids); $i++){
    $prepared->bind_param("ss",$token,$ids[$i]);
    $prepared->execute();
  }
  $url = 'https://ossiatickets.com/executeorder';
  $data = array('orderid' => $token);

  $options = array(
      'http' => array(
          'header'  => "Content-type: application/x-www-form-urlencoded",
          'method'  => 'POST',
          'content' => http_build_query($data)
      ),
      "ssl" => array(
          "verify_peer"=> false,
          "verify_peer_name"=> false,
      )
  );
  $context  = stream_context_create($options);
  $theoutput = file_get_contents($url, false, $context);
  if($theoutput != "successexec"){
    echo $theoutput;
  }
}
}
$conn->close();
echo 1;
}
?>
