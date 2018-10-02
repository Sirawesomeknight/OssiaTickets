<html>
<script src="JS/banMobile.js"></script>
<script>
var outcome = "<?php
try{
$sessionid = $_COOKIE["loginid"];
$eventid = $_POST["eventid"];
$price = doubleval($_POST["price"]);
$seatingloc = $_POST["seatloc"];
$collateral = $_POST["collateral"];
$selltogether = $_POST["selltogether"];
$delivery = $_POST["delivery"];
$ticketclass = $_POST["ticketclass"];

if($selltogether == "Yes"){
  $selltogether = true;
}else{
  $selltogether = false;
}

$email = "";

require_once "PHP/getTicketData.php";

require "vendor/autoload.php";

require "config.php";

require "PHP/protocol.php";

$customerid = "";

if(!empty($sessionid)){
  $getemail = $conn->prepare("SELECT email, customerid FROM users WHERE sessionid = ?");
  $getemail->bind_param("s",$sessionid);
  $getemail->execute();
  $result = mysqli_fetch_assoc($getemail->get_result());
  $email = $result["email"];
  $customerid = openssl_decrypt($result["customerid"],"AES-256-CBC",$decryption->key,0,$decryption->IV);;
}
$geteventid = $conn->prepare("SELECT eventid FROM events WHERE eventid = ?");
$geteventid->bind_param("s",$eventid);
$geteventid->execute();

$validEventId = mysqli_fetch_assoc($geteventid->get_result());
$noDuplicates = "";

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

putenv('GOOGLE_APPLICATION_CREDENTIALS=client_key.json');
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setSubject("tickets@ossiatickets.com");
$client->setScopes(Google_Service_Drive::DRIVE);
$client->setApplicationName('ticketdatabase');
$service = new Google_Service_Drive($client);

$filelist = "";

if(!empty($_POST["filepaths"])){
  $filelist = explode("#",$_POST["filepaths"]);
}

$seatingloc = explode("#",$seatingloc);
$quantity = sizeof($seatingloc) - 1;

$collateralapproved = false;

try{
  $customer = \Stripe\Customer::retrieve($customerid);
  $customer->sources->create(array("source" => $_POST["collateral"]));
  $collateralapproved = true;
}catch(Exception $e){}

  /*
  echo "Email" . !empty($email);
  echo "Quantity" . !empty($quantity);
  echo "Price" . is_numeric($price);
  echo "Quantity is num" . is_numeric($quantity);
  echo "Valid Event" . !empty($validEventId["eventid"]);
  echo "No Duplicate" . empty($noDuplicates);
  echo "Success" . ($result->success == true);
  echo "Seatingloc" . !empty($seatingloc);
  echo "Collateral" . !empty($collateral);
  echo "Collateral valid" . ($collateralapproved == true);
  */

if(!empty($email) && !empty($quantity) && is_numeric($price) && is_numeric($quantity) && !empty($validEventId["eventid"]) && empty($noDuplicates) && $result->success == true && !empty($seatingloc) && !empty($collateral) && $collateralapproved == true){
  $prefix = $eventid . "_";
  $tickettempidbase = str_replace(".","",uniqid($prefix,true));
  $insertorder = $conn->prepare("INSERT INTO orders (ticketid, userid, eventid, price, saletype, timeprocessed, ticketclass, sell_id, section, row, seatnum) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)");
  $sell_id = "";
  for($i = 0; $i < $quantity; $i++){
  $tickettempid = $tickettempidbase . "_" . $i;
  $seat = json_decode($seatingloc[$i]);
  $section = $seat->section;
  $row = $seat->row;
  $seatnum = $seat->seatnum;
  /*
  $ticketclassq = $conn->prepare("SELECT ticketclass FROM orders WHERE ? LIKE section OR section = ? ORDER BY CASE WHEN ? LIKE section THEN 1 WHEN section = ? THEN 0 END");
  $ticketclassq->bind_param("ss",$a = "%$seat->section%",$seat->section,$b = "%$seat->section%",$seat->section);
  $ticketclassq->execute();
  $ticketclass = mysqli_fetch_assoc($ticketclassq->get_result())["ticketclass"];
  echo "never ever leave";
  if(!empty($ticketclass)){}else if(preg_match("/(GA)(General Admission)(Gen Adm)/i",$linetext,$match)){
    $ticketclass = "GA";
  }else if(is_numeric($seatnum)){}else{
    $ticketclass = "none";
  }
  */
  if($selltogether == false || empty($sell_id)){
    $sell_id = $tickettempid;
  }
  /*
  echo "Tickettempid" . $tickettempid;
  echo "email" . $email;
  echo "eventid" . $eventid;
  echo "price" . $price;
  echo "delivery" . $delivery;
  echo "section" . $section;
  echo "row" . $row;
  echo "seatnum" . $seatnum;
  */
  $insertorder->bind_param("sssdssssss",$tickettempid,$email,intval($eventid),doubleval($price),$delivery,$ticketclass,$sell_id,$section,$row,$seatnum);
  $insertorder->execute();
  if(empty($filelist)){
    $updatestatus = $conn->prepare("UPDATE orders SET ticketstatus = 'Proof Not Submitted' WHERE ticketid = ?");
    $updatestatus->bind_param("s",$tickettempid);
    $updatestatus->execute();
  }else if(!empty($filelist[$i])){
    $filetype = strtolower(pathinfo($filelist[$i],PATHINFO_EXTENSION));
    $fileMetadata = new Google_Service_Drive_DriveFile(array(
      'name' => "$tickettempid.$filetype"));
      $content = file_get_contents($filelist[$i]);
      $filetypemime = mime_content_type($filelist[$i]);
      $file = $service->files->create($fileMetadata, array(
        'data' => $content,
        'mimeType' => $filetypemime,
        'uploadType' => 'multipart',
        'fields' => 'id'));
      unlink($filelist[$i]);
      $fileid = $file->getId();
      $updateproof = $conn->prepare("UPDATE orders SET uploadedproof = ?, ticketstatus = 'Processing Proof' WHERE ticketid = ?");
      $updateproof->bind_param("ss",$fileid,$tickettempid);
      $updateproof->execute();
    }else{
      $linkProof = $i % (sizeof($filelist) - 1);
      $linkProof = $tickettempidbase . "_" . $linkProof;
      $updateorder = $conn->prepare("UPDATE orders SET uploadedproof = (SELECT uploadedproof FROM (SELECT * FROM orders) AS o WHERE ticketid = ?), ticketstatus = 'Processing Proof' WHERE ticketid = ?");
      $updateorder->bind_param("ss",$linkproof,$tickettempid);
      $updateorder->execute();
    }
  }
  echo 1;
}else{
  echo "Authentication Error";
}
$conn->close();
}catch(Exception $e){
  echo "unkerr";
}
?>";
if(outcome == "1"){
  location.href = "confirmsell";
}else if(outcome == "unkerr"){
  location.href = "confirmsell?error=error";
}else{
  location.href = "index?error=autherror";
}
</script>
</html>
