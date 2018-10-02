<!DOCTYPE html>
<html>
<script src="JS/banMobile.js"></script>
<script>
var buyoutcome = "<?php
try{
$sessionid = $_COOKIE["loginid"];

if(empty($_POST["shippingOption"])){
  $getOrderData->merch = false;
}else{
  if($getOrderData->merch == "on"){
    $getOrderData->merch = true;
  }else{
    $getOrderData->merch = false;
  }
}

$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array('secret' => '6Ld0alEUAAAAAIidotF6dAKzaMqDUPlc6ilHuAYM', 'response' => $_POST["g-recaptcha-response"]);

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
$result = json_decode(file_get_contents($url, false, $context));

if(!isset($_COOKIE["cartinfo"])){
  echo "cartexpr";
}else{

require 'vendor/autoload.php';
require "PHP/getTicketData.php";
require 'config.php';
require 'PHP/protocol.php';

$getOrderData = json_decode(urldecode($_COOKIE["cartinfo"]));

$eventid = $getOrderData->eventid;
$shippingAddress = $_POST["shippingAddress"];
$price = doubleval($getOrderData->price);
$quantity = $getOrderData->quantity;
$ticketclass = $getOrderData->ticketclass;

if(empty($getOrderData->flexprice) || $getOrderData->flexprice < $getOrderData->price){
  $getOrderData->flexprice = $getOrderData->price;
}

$token = "";
if(!empty($_POST["stripeToken"])){
  $token = openssl_encrypt($_POST["stripeToken"],'AES-256-CBC',$decryption->key,0,$decryption->IV);
}else{
  $token = openssl_encrypt($_POST["paymethod"],'AES-256-CBC',$decryption->key,0,$decryption->IV);
}

$user = "";
if(!empty($_POST["stripeEmail"])){
  $user = openssl_encrypt($_POST["stripeEmail"],'AES-256-CBC',$decryption->key,0,$decryption->IV);
  $usercol = $conn->prepare("SELECT email FROM users WHERE email = ?");
  $usercol->bind_param("s",$user);
  $usercol->execute();
  if(!empty(mysqli_fetch_assoc($usercol->get_result())["email"])){
    $user = "";
    echo "user";
  }
}else{
  $usercol = $conn->prepare("SELECT email FROM users WHERE sessionid = ?");
  $usercol->bind_param("s",$sessionid);
  $usercol->execute();
  $user = mysqli_fetch_assoc($usercol->get_result())["email"];
}
$fullname = $_POST["stripeShippingName"];

$preparecheck = $conn->prepare("SELECT eventname FROM events WHERE eventid = ?");
$preparecheck->bind_param("s",$eventid);
$preparecheck->execute();
$verify = mysqli_fetch_assoc($preparecheck->get_result());

if($getOrderData->delivery == "Online"){
  $shippingAddress = "Online";
}else if($_POST["shippingOption"] == "wcall"){
  $shippingAddress = "None";
}
/*
echo "User:" . !empty($user);
echo "Verified Section:" . !empty($verify["eventname"]);
echo "Token:" . !empty($token);
echo "memes" . $_POST["shippingOption"];
echo "Shipping Address" . !empty($shippingAddress);
echo "Confirm no robot" . ($result->success == true);
*/
if(!empty($user) && !empty($verify["eventname"]) && !empty($token) && !empty($shippingAddress) && $result->success == true){
$statement = $conn->prepare("INSERT INTO offers (email, eventid, quantity, price, timeprocessed, token, flexprice, shipping, shippingOption, address, purchasedMerch, ticketclass, ticketstatus) VALUES (?,?,?,?,NOW(),?,?,?,?,?,?,?,'Open')");
$statement->bind_param("ssidsdsssis",$user, $eventid, $quantity, $price, $token, $getOrderData->flexprice,$getOrderData->delivery,$_POST["shippingOption"],openssl_encrypt($shippingAddress,'AES-256-CBC',$decryption->key,0,$decryption->IV),$getOrderData->merch,$ticketclass);
$statement->execute();
try{
$pstate = $conn->prepare("SELECT customerid FROM users WHERE email = ?");
$pstate->bind_param("s",$user);
$pstate->execute();
$customer = \Stripe\Customer::retrieve(openssl_decrypt(mysqli_fetch_assoc($pstate->get_result())["customerid"],'AES-256-CBC',$decryption->key,0,$decryption->IV));
if(empty($customer->source) && !empty($customer)){
  $customer->sources->create($_POST["stripeToken"]);
}
if(empty($customer->shipping->address)){
  $addrstring = json_decode($shippingAddress);
  $customer->shipping = array("address" => array("line1" => $addrstring->line1, "line2" => $addrstring->line2, "state" => $addrstring->state, "city" => $addrstring->city, "postal_code" => $addrstring->zip), "name" => $customer->description);
  $customer->save();
}
}catch(Exception $e){}
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
    $result = file_get_contents($url, false, $context);
  }else{
  putenv('GOOGLE_APPLICATION_CREDENTIALS=client_key.json');
  $client = new Google_Client();
  $client->useApplicationDefaultCredentials();
  $client->setSubject("tickets@ossiatickets.com");
  $client->setScopes(Google_Service_Gmail::GMAIL_MODIFY);
  $client->setApplicationName('ticketdatabase');
  $service = new Google_Service_Gmail($client);

  $decryptemail = openssl_decrypt($user,'AES-256-CBC',$decryption->key,0,$decryption->IV);

  try{
  $message = (new Swift_Message('Ticket Order Placed!'))
    ->setFrom(['tickets@ossiatickets.com' => 'Ossia Tickets'])
    ->setTo([$decryptemail => "$fullname"]);
    $message->setBody("Your ticket order was placed"
    ,'text/html');
    $emailmessage = new Google_Service_Gmail_Message();
    $message = strtr(base64_encode($message), array('+' => '-', '/' => '_'));
    $emailmessage->setRaw($message);

    $service->users_messages->send("me",$emailmessage);
  }catch(Exception $e){}
  echo "successnoexchange";
  }
}else{
echo "autherror";
}
$conn->close();
}
}catch(Exception $e){
  echo "unkerr";
}

function ctvm($sellerprice){
  return $sellerprice;
}
?>";
switch(buyoutcome){
  case "successexec":
   location.href = "complete?exchange=true";
  break;
  case "successnoexchange":
   location.href = "complete?exchange=false";
  break;
  case "authexecerror":
   location.href = "complete?exchange=authexecerror";
  break;
  case "autherror":
   location.href = "buytickets?eventid=<?php echo json_decode(urldecode($_COOKIE['cartinfo']))->eventid;?>";
  break;
  case "execerror":
   location.href = "index";
  break;
  case "cartexpr":
   alert("Cart Expired. Please resubmit your order.");
   location.href = "buytickets?eventid=<?php echo json_decode(urldecode($_COOKIE['cartinfo']))->eventid;?>";
  break;
  case "evthapp":
   location.href = "index?error=evthapp";
  break;
  case "userautherror":
   location.href = "payment?error=eregist";
  break;
  default:
    location.href = "complete?exchange=error";
  break;
}
//Include possibility for concert exchange MySQL code
</script>
</html>
