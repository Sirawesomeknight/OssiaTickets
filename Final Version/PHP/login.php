<?php
$user = str_replace(' ','',$_POST["username"]);
$pass = str_replace(' ','',$_POST["password"]);
if(!empty($user) && !empty($pass)){
  if(uservalidchars($user) == true){
    require_once "getTicketData.php";
    require_once "protocol.php";

    $user = openssl_encrypt($user ,"AES-256-CBC",$decryption->key,0,$decryption->IV);
    $getpass = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $getpass->bind_param("s",$user);
    $getpass->execute();
    $row = mysqli_fetch_assoc($getpass->get_result());
    if(!empty($row)){
      if($row["confirmed"] == 1){
        $loginstuff = $conn->prepare("SELECT lastloggedin, loginattempts FROM users WHERE email = ?");
        $loginstuff->bind_param("s",$user);
        $loginstuff->execute();
        $userresult = mysqli_fetch_assoc($loginstuff->get_result());
        if(round(microtime(true) * 1000) - strtotime($userresult["lastloggedin"]) * 1000 <= 900 + ($userresult["loginattempts"] - 3) * 300 && $userresult["loginattempts"] > 5){
          $setlastlogin = $conn->prepare("UPDATE users SET lastloggedin = NOW() AND loginattempts = loginattempts + 1 WHERE email = ?");
          $setlastlogin->bind_param("s",$user);
          $setlastlogin->execute();
          echo "toomany";
        }else{
          if(password_verify($pass,$row["password"])){
            $sessionid = createsessionid($conn);
            //CHANGE TO SECURE WHEN PUBLISHING WEBSITE (second to last parameter = 1)
            setcookie("loginid",$sessionid,time() + 86400,"/","ossiatickets.com",true,true);
            $loginattempt = $conn->prepare("UPDATE users SET sessionid = ?, lastloggedin = NOW(), loginattempts = 0 WHERE email = ?");
            $loginattempt->bind_param("ss",$sessionid,$user);
            $loginattempt->execute();
            echo "complete";
          }else{
            $loginattempt = $conn->prepare("UPDATE users SET lastloggedin = NOW(), loginattempts = loginattempts + 1 WHERE email = ?");
            $loginattempt->bind_param("s",$user);
            $loginattempt->execute();
            echo "invalid";
          }
        }
      }else{
        echo "notconfirmed";
      }
    }else{
      echo "noexist";
    }
  }else{
    echo "invalidformat";
  }
}else{
  echo "empty";
}
$conn->close();

function createsessionid($conn){
$crypto = uniqid("",true);
if(checksessionid($crypto,$conn) == false){
  createsessionid($conn);
}
return $crypto;
}
function checksessionid($crypto,$conn){
  $unique = $conn->prepare("SELECT * FROM users WHERE sessionid = ?");
  $unique->bind_param("s",$crypto);
  $unique->execute();
  if(mysqli_num_rows($unique) > 0){
    return false;
  }
  return true;
}

function uservalidchars($user){
if(!filter_var($user,FILTER_VALIDATE_EMAIL)){
return false;
}
return true;
}
?>
