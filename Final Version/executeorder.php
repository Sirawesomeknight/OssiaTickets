<?php
require_once "PHP/getTicketData.php";

require_once 'config.php';
require_once 'vendor/autoload.php';

require_once "PHP/protocol.php";

$orderid = $_POST["orderid"];

try{
$checkBuy = $conn->prepare("SELECT token FROM offers WHERE token = ? AND ticketstatus != 'Open'");
$checkBuy->bind_param("s",$orderid);
$checkBuy->execute();
$checkDuplicateOffer = mysqli_fetch_assoc($checkBuy->get_result())["token"];
$checkSell = $conn->prepare("SELECT sell_id FROM orders WHERE sell_id = ? AND ticketstatus != 'Processing'");
$checkSell->bind_param("s",$orderid);
$checkSell->execute();
$checkDuplicateOrder = mysqli_fetch_assoc($checkSell->get_result())["sell_id"];
$getOrderBuy = $conn->prepare("SELECT offers.price, offers.email, offers.eventid, offers.timeprocessed, offers.purchasedMerch, offers.shippingOption, offers.address, offers.quantity, users.customerid FROM (SELECT * FROM offers WHERE token = ?) AS offers LEFT JOIN users ON offers.email = users.email");
$getOrderBuy->bind_param("s",$orderid);
$getOrderBuy->execute();
$getOffer = mysqli_fetch_assoc($getOrderBuy->get_result());
$getOrderSell = $conn->prepare("SELECT orders.ticketid AS ticketid, orders.price AS price, orders.userid AS email, orders.saletype AS saletype, users.customerid AS customerid FROM orders INNER JOIN users ON orders.userid = users.email WHERE sell_id = ?");
$getOrderSell->bind_param("s",$orderid);
$getOrderSell->execute();
$getOrder = $getOrderSell->get_result();
/*
echo "checkcdoff" . $checkDuplicateOffer;
echo "checkcdord" . $checkDuplicateOrder;
echo "checkoffex" . $getOffer;
*/
if(empty($checkDuplicateOffer) && empty($checkDuplicateOrder) && !empty($getOffer) && !empty($getOrder)){

$quantity = $getOffer["quantity"];
$buyer->email = $getOffer["email"];
$buyer->price = $getOffer["price"];
$buyer->purchasedMerch = $getOffer["purchasedMerch"];
$buyer->shippingOption = $getOffer["shippingOption"];
if($buyer->shippingOption != "Online"){
$buyer->address = json_decode(openssl_decrypt($getOffer["address"],"AES-256-CBC",$decryption->key,0,$decryption->IV));
}else{
$buyer->address = "";
}
$buyer->timeprocessed = strtotime($getOffer["timeprocessed"]);
$eventid = $getOffer["eventid"];
$buyer->id = "";
if(!empty($getOffer["customerid"])){
  $buyer->id = openssl_decrypt($getOffer["customerid"],"AES-256-CBC",$decryption->key,0,$decryption->IV);
}

putenv('GOOGLE_APPLICATION_CREDENTIALS=client_key.json');
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setSubject("tickets@ossiatickets.com");
$client->setApplicationName('ticketdatabase');

$seller;
$sellers = array("");
$ticketids = array("");
$isShipping = false;

while($row = mysqli_fetch_assoc($getOrder)){
  $ticket = $row["ticketid"];
  $seller->email = $row["email"];
  $saletype = $row["saletype"];
  $seller->price = 0;
  if($row["price"] == 0){
    $seller->price = 0.90 * doubleval($buyer->price);
  }else{
    $seller->price = ctvm($row["price"]);
  }
  $seller->id = openssl_decrypt($row["customerid"],"AES-256-CBC",$decryption->key,0,$decryption->IV);
  $seller->stripe = \Stripe\Customer::retrieve($seller->id);
  $seller->stripe->account_balance = $seller->stripe->account_balance + intval(($seller->price * 100));
  $seller->stripe->save();

  $changestatus = $conn->prepare("UPDATE orders SET ticketstatus = 'Customer Paying' WHERE ticketid = ?");
  $changestatus->bind_param("s",$ticket);
  $changestatus->execute();
  $insertorder = $conn->prepare("INSERT INTO orderprocessing (ticketid, sellerid, buyerid, saletype, timeprocessed, orderid, buyersprice, sellersprice) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)");
  $insertorder->bind_param("sssssdd",$ticket,$seller->email,$buyer->email,$saletype,$orderid,$buyer->price,$seller->price);
  $insertorder->execute();
  $seller->email = openssl_decrypt($seller->email,"AES-256-CBC",$decryption->key,0,$decryption->IV);
  $sellers[sizeof($sellers) - empty($sellers[0])] = $seller->email;
  $ticketids[sizeof($ticketids) - empty($sellers[0])] = $ticket;
}

$estimates->price = 0;

if($buyer->shippingOption != "Online"){
  $estimates->price = doubleval($buyer->shippingOption * 100);
}

if($buyer->purchasedMerch == true){
  $estimates->price += (4.38 + 2.66) * 100;
}

$charge->failure_code = "";
$chargearray = array("");
if(!empty($buyer->id)){
  $getStripeBuyer = \Stripe\Customer::retrieve($buyer->id);
  /*
  $amount = intval($buyer->price * 100 * $quantity + $estimates->price - $getStripeBuyer->account_balance);
  if($amount < 0){
    $getStripeBuyer->account_balance = abs($amount);
    $amount = 0;
  }else{
    $getStripeBuyer->account_balance = 0;
  }
  */
  if($getStripeBuyer->shipping == null){
    $getStripeBuyer->shipping = array("address" => array("line1" => $buyer->address->line1, "line2" => $buyer->address->line2, "city" => $buyer->address->city, "state" => $buyer->address->state, "postal_code" => $buyer->address->postal_code), "name" => $getStripeBuyer->description);
  }
  $getStripeBuyer->save();
  $chargearray = array(
    "amount" => $amount,
    "currency" => "usd",
    "description" => $buyer->email . " " . $eventid,
    "customer" => $buyer->id,
    "receipt_email" => openssl_decrypt($buyer->email,"AES-256-CBC",$decryption->key,0,$decryption->IV),
  );
}else{
  $chargearray = array(
    "amount" => intval($buyer->price * 100 * $quantity + $estimates->price),
    "currency" => "usd",
    "description" => $buyer->email . " " . $eventid,
    "source" => openssl_decrypt($orderid,"AES-256-CBC",$decryption->key,0,$decryption->IV),
    "receipt_email" => openssl_decrypt($buyer->email,"AES-256-CBC",$decryption->key,0,$decryption->IV),
  );
}

if($buyer->shippingOption != "Online"){
$chargearray["shipping"] = array("address" => array("line1" => $buyer->address->line1, "line2" => $buyer->address->line2, "city" => $buyer->address->city, "postal_code" => $buyer->address->postal_code), "name" => $getStripeBuyer->description);
}

$charge = \Stripe\Charge::create($chargearray, array(
"idempotency_key" => $orderid,
));

if($charge->failure_code == null || $charge->failure_code == ""){
$updateorders = $conn->prepare("UPDATE orders SET ticketstatus = 'Filled' WHERE sell_id = ?");
$updateorders->bind_param("s",$orderid);
$updateorders->execute();
for($i = 0; $i < sizeof($sellers); $i++){
$theticket = $ticketids[$i];
$message = (new Swift_Message('Ticket Sold!'))
  ->setFrom(['tickets@ossiatickets.com' => 'Ossia Tickets'])
  ->setTo([$sellers[$i]])
  ->setBody('Your ticket was sold!',"text/html");

sendEmail($message,$client);
}
/*
$raffletickets = intval($price);
$updateraffle = $conn->prepare("UPDATE users SET raffletickets = raffletickets + ? WHERE email = ?");
$updateraffle->bind_param("is",$raffletickets,$user);
$updateraffle->execute();
*/
$updateoffers = $conn->prepare("UPDATE offers SET ticketstatus = 'Filled' WHERE token = ?");
$updateoffers->bind_param("s",$orderid);
$updateoffers->execute();

// You can embed files from a URL if allow_url_fopen is on in php.ini
/*
$buyer->email = openssl_decrypt($buyer->email,"AES-256-CBC",$decryption->key,0,$decryption->IV);

$message = (new Swift_Message('Ticket Purchased!'))
  ->setFrom(['tickets@ossiatickets.com' => 'Ossia Tickets'])
  ->setTo([$buyer->email])
  ->setBody("Tickets Purchased!",'text/html');
  */

  //$client->setScopes(Google_Service_Drive::DRIVE);
  //$service = new Google_Service_Drive($client);
/*
$getUploadedFiles = $conn->prepare("SELECT uploadedproof FROM orders WHERE sell_id = ? GROUP BY uploadedproof");
$getUploadedFiles->bind_param("s",$orderid);
$getUploadedFiles->execute();
$finalresult = $getUploadedFiles->get_result();
while($uploadedfile = mysqli_fetch_assoc($finalresult)){
  $fileid = $uploadedfile["uploadedproof"];
  $response = $service->files->get($fileid);
  $filename = $response->id;
  $filetype = $response->mimeType;
  $ext = "";
  switch($filetype){
    case "application/pdf":
      $ext = ".pdf";
    break;
    case "image/jpeg":
      $ext = ".jpg";
    break;
    case "image/png";
      $ext = ".png";
    break;
  }
  $response = $service->files->get($fileid, array(
    'alt' => 'media'));
  $file = $response->getBody()->getContents();
  $path = "ticketdownloads/" . $filename . $ext;
  $writer = fopen($path,"w");
  fwrite($writer,$file);
  fclose($writer);
  $message->attach(Swift_Attachment::fromPath($path));
  unlink($path);
}
*/
//sendEmail($message,$client);
  setcookie("cartinfo","",time() + 1,"/","ossiatickets.com",true,true);
  echo "successexec";
}else{
  $updateorders = $conn->prepare("UPDATE orders SET ticketstatus = 'Open' WHERE sell_id = ?");
  $updateorders->bind_param("s",$orderid);
  $updateorders->execute();
  $updateoffers = $conn->prepare("UPDATE offers SET ticketstatus = 'Error' WHERE token = ?");
  $updateoffers->bind_param("s",$orderid);
  $updateoffers->execute();
  setcookie("cartinfo","",time() + 1,"/","ossiatickets.com",true,true);
  echo "execerror";
}
}else{
  echo "autherror";
}
}catch(Exception $e){
  $updateorders = $conn->prepare("UPDATE orders SET ticketstatus = 'Open' WHERE sell_id = ?");
  $updateorders->bind_param("s",$orderid);
  $updateorders->execute();
  $updateoffers = $conn->prepare("UPDATE offers SET ticketstatus = 'Error' WHERE token = ?");
  $updateoffers->bind_param("s",$orderid);
  $updateoffers->execute();
  echo $e->getMessage();
  echo "unkerr";
}
$conn->close();

function sendEmail($message,$client){
  $client->setScopes(Google_Service_Gmail::GMAIL_MODIFY);
  $service = new Google_Service_Gmail($client);
  $emailmessage = new Google_Service_Gmail_Message();
  $message = strtr(base64_encode($message), array('+' => '-', '/' => '_'));
  $emailmessage->setRaw($message);

  $service->users_messages->send("me",$emailmessage);
}

function ctvm($sellerprice){
  return $sellerprice;
}
?>
