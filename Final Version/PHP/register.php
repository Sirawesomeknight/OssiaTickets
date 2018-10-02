<!DOCTYPE html>
<html>
<head>
<title>Ossia</title>
</head>
<body>
<form id="completionform" method="POST" action="../registered">
<input type="hidden" name="firstname" id="firstname">
<input type="hidden" name="lastname" id="lastname">
<input type="hidden" name="email" id="email">
<input type="hidden" name="g-recaptcha-response" id="grecap">
</form>
<script>
var outcome = "<?php
$firstname = ucfirst(strtolower($_POST["firstname"]));
$lastname = ucfirst(strtolower($_POST["lastname"]));
$email = str_replace(' ','',strtolower($_POST["email"]));
$pass = $_POST["pass"];
$confpass = $_POST["confpass"];
//$address = $_POST["shippingAddress"];
$refcode = "";
if(isset($_POST["ref"])){
  $refcode = str_replace(' ','',$_POST["ref"]);
}

$registinfo = array($firstname, $lastname, $email, $pass);

require_once "getTicketData.php";
require_once "protocol.php";
require_once "../config.php";

if(checkempty($registinfo) == true){
  if(validemail($email) == true){
    $encryptedemail = openssl_encrypt($email,"AES-256-CBC",$decryption->key,0,$decryption->IV);
    if(sameEmail($encryptedemail,$conn)){
      if(strongPass($pass)){
      $addressobj = json_decode($address);
      $newuserid = \Stripe\Customer::create(array(
          "description" => "$firstname $lastname",
          "email" => $email
          //if this line is added back in remember the comma at the end of the line above
          //"shipping" => array("address" => array("line1" => $addressobj->line1,"line2" => $addressobj->line2,"city" => $addressobj->city,"state" => $addressobj->state,"postal_code" => $addressobj->postal_code), "name" => "$firstname $lastname");
        ));

      $password = password_hash($pass,PASSWORD_DEFAULT);
      $customerid = openssl_encrypt($newuserid->id,"AES-256-CBC",$decryption->key,0,$decryption->IV);

      $referralcode = makeid($conn);
      $insertuser = $conn->prepare("INSERT INTO users (email, password, referralcode, confirmed, totaltickets, created, customerid) VALUES (?,?,?,?,?,NOW(),?)");
      $insertuser->bind_param("sssiis",$encryptedemail,$password,$referralcode,$a = false,$b = 0,$customerid);
      $insertuser->execute();

      if(!empty($refcode)){
        $referring = $conn->prepare("SELECT customerid, email FROM users WHERE referralcode = ?");
        $referring->bind_param("s",$refcode);
        $referring->execute();
        $referer = mysqli_fetch_assoc($referring->get_result());
        if(!empty($referer)){
          $refereremail = openssl_decrypt($referer["email"],"AES-256-CBC",$decryption->key,0,$decryption->IV);
          $refererid = openssl_decrypt($referer["customerid"],"AES-256-CBC",$decryption->key,0,$decryption->IV);
          try{
          $referer = \Stripe\Customer::retrieve($refererid);
          $referee = \Stripe\Customer::retrieve($newuserid->id);
          $referer->coupon = "refcode";
          $referer->save();
          $referee->coupon = "refcode";
          $referee->save();
          echo "complete";
          //send referer email
        }catch(Exception $e){
          $delete = $conn->prepare("DELETE FROM users WHERE email = ?");
          $delete->bind_param("s",$encryptedemail);
          $delete->execute();
          $removeuser = \Stripe\Customer::retrieve($newuserid->id);
          $removeuser->delete();
          echo "incompleterefcode";
        }
        }else{
          echo "incompleterefcode";
        }
      }else{
        echo "complete";
      }
    }else{
      echo "notstrongpass";
    }
  }else{
    echo "alreadyregistered";
  }
  }else{
    echo "invalid";
  }
}else{
  echo "empty";
}
$conn->close();

function strongPass($pass){
  if(strlen($pass) >= 7 && preg_match('/[A-Z]/', $pass) && preg_match('/[1-9]/', $pass)){
    return true;
  }else{
    return false;
  }
}

function makeid($conn){
  $thechars = "1234567890qwertyuiopasdfghjklzxcvbnm";
  $idchars = $thechars.str_split();
  $theid = "";
  //NEED RANDOM SEED
  for($i = 0; $i < 6; $i++){
    $theid = $theid . $thechars[rand(0,35)];
  }
  $getemail = $conn->prepare("SELECT email FROM users WHERE referralcode = ?");
  $getemail->bind_param("s",$theid);
  $getemail->execute();
  $checkForDuplicate = mysqli_fetch_assoc($getemail->get_result());
  if(!empty($checkForDuplicate)){
    return makeid($conn);
  }
  return $theid;
}

function checkempty($registinfo){
  for($i = 0; $i < sizeof($registinfo); $i++){
    if(empty($registinfo[$i])){
      return false;
    }
  }
  return true;
}

function validemail($email){
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    return false;
  }
  return true;
}

function sameEmail($email,$conn){
  $result = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $result->bind_param("s",$email);
  $result->execute();
  $numrows = mysqli_num_rows($result->get_result());
  if($numrows > 0){
    return false;
  }
  return true;
}
?>";
switch(outcome){
  case "empty":
    location.href = "../register?error=empty";
  case "invalid":
    location.href = "../register?error=invalid";
  break;
  case "alreadyregistered":
    location.href = "../register?error=alreadyregistered";
  break;
  case "notstrongpass":
    location.href = "../register?error=notstrongpass";
  break;
  case "incompleterefcode":
    location.href = "../register?error=incompleterefcode";
  break;
  case "complete":
    document.getElementById("email").value = "<?php echo $_POST['email'];?>";
    document.getElementById("firstname").value = "<?php echo $_POST['firstname'];?>";
    document.getElementById("lastname").value = "<?php echo $_POST['lastname'];?>";
    document.getElementById("grecap").value = "<?php echo $_POST['g-recaptcha-response'];?>";
    document.getElementById("completionform").submit();
  break;
}
</script>
</body>
</html>
