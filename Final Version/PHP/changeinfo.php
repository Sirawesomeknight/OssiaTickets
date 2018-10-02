<?php
if(isset($_COOKIE["loginid"])){
$sessionid = $_COOKIE["loginid"];
require_once "getTicketData.php";
require_once "../vendor/autoload.php";
require_once 'protocol.php';
$cpass = $_POST["cpass"];
$npass = $_POST["npass"];
$nemail = $_POST["nemail"];

$preparing = $conn->prepare("SELECT password, email FROM users WHERE sessionid = ?");
$preparing->bind_param("s",$sessionid);
$preparing->execute();

$isPassCorrect = mysqli_fetch_assoc($preparing->get_result());

$oemail = openssl_decrypt($isPassCorrect["email"],"AES-256-CBC",$decryption->key,0,$decryption->IV);

if(password_verify($cpass,$isPassCorrect["password"])){
  if(!empty($npass)){
    if(strlen($npass) >= 7 && preg_match('/[A-Z]/', $npass) && preg_match('/[0-9]/', $npass)){
    $npass = password_hash($npass,PASSWORD_DEFAULT);
    $preparestat = $conn->prepare("UPDATE users SET password = ? WHERE sessionid = ?");
    $preparestat->bind_param("ss",$npass,$sessionid);
    $preparestat->execute();
    echo 2;
  }else{
    echo 4;
  }
  }else if(!empty($nemail) && !empty($oemail)){
    if(validemail($nemail) && notRepeatEmail($conn,$nemail,$decryption->key,$decryption->IV)){
    putenv('GOOGLE_APPLICATION_CREDENTIALS=client_key.json');
    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->setSubject("tickets@ossiatickets.com");
    $client->setScopes(Google_Service_Gmail::GMAIL_MODIFY);
    $client->setApplicationName('ticketdatabase');
    $service = new Google_Service_Gmail($client);

    $message = (new Swift_Message('Email Changed!'))
      ->setFrom(['tickets@ossiatickets.com' => 'Ossia Tickets'])
      ->setTo([$oemail]);
      $message->setBody("Your email was changed to $nemail in your settings. <br> If you did not request this change, please contact Ossia support immidiately."
      ,'text/html');
      $emailmessage = new Google_Service_Gmail_Message();
      $message = strtr(base64_encode($message), array('+' => '-', '/' => '_'));
      $emailmessage->setRaw($message);

      $service->users_messages->send("me",$emailmessage);

    $nemail = openssl_encrypt($nemail,"AES-256-CBC",$decryption->key,0,$decryption->IV);
    $prepareit = $conn->prepare("UPDATE users SET email = ? WHERE sessionid = ?");
    $prepareit->bind_param("ss",$nemail,$sessionid);
    $prepareit->execute();
    echo 1;
    }else{
    echo 5;
    }
  }else{
    echo 0;
  }
}else{
  echo 3;
}
$conn->close();
}else{
  echo 6;
}

function validemail($email){
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    return false;
  }
  return true;
}

function notRepeatEmail($conn,$nemail,$decryptionkey,$decryptionIV){
  $nemail = openssl_encrypt($nemail,"AES-256-CBC",$decryptionkey,0,$decryptionIV);
  $preparedup = $conn->prepare("SELECT email FROM users WHERE email = ?");
  $preparedup->bind_param("s",$nemail);
  $preparedup->execute();
  $dupcheck = mysqli_fetch_assoc($preparedup->get_result())["email"];
  if(empty($dupcheck)){
    return true;
  }else{
    return false;
  }
}
?>
