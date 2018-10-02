<!DOCTYPE html>
<html>
<head>
<title>Ossia</title>
<link rel="stylesheet" href="CSS/textinput.css">
<link rel="stylesheet" href="CSS/passreset.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/bottom.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="JS/passreset.js" defer></script>
<script src="JS/loadbackground.js" defer></script>
<script src="JS/checklogin.js" defer></script>
<script src="JS/classie.js" defer></script>
</head>
<body onload="load()">
<div id="content">
  <div id="tint">
  <script>
  var changedPass = "<?php
    if(!empty($_POST["cnpass"]) && !empty($_POST["npass"]) && !empty($_POST["resetcode"])){
      if($_POST["npass"] == $_POST["cnpass"] && strongPass($_POST["npass"])){
        $npass = $_POST["npass"];
        $resetcode = $orderid = str_replace(" ","+",$_POST["resetcode"]);
        require_once "PHP/getTicketData.php";
        require_once "PHP/protocol.php";
        $passhash = password_hash($npass,PASSWORD_DEFAULT);
        $updateuser = $conn->prepare("UPDATE users SET password = ?, resetcode = '' WHERE resetcode = ?");
        $updateuser->bind_param("ss",$passhash,$resetcode);
        $updateuser->execute();
        $conn->close();
        echo "true";
      }else {
        echo "Make sure your passwords match!";
      }
    }else {
      echo "empty";
    }
    function strongPass($pass){
      if(strlen($pass) >= 7 && preg_match('/[A-Z]/', $pass) && preg_match('/[1-9]/', $pass)){
        return true;
      }else{
        return false;
      }
    }
  ?>";
  if(changedPass == "true"){
    alert("Password Changed Successfully!");
    location.href = "login";
  }else if(changedPass != "empty"){
    alert(changedPass);
  }
  </script>
  <div id="navbar">
    <a class="navbutton" href="login" id="login">Login</a>
    <a class="logocont" href="index"><img src="logonotext.png" class="logo"></a>
    <a class="navbutton" href="faq">FAQ</a>
  </div>
  <script defer>
  if(isLoggedIn == true){
    location.href = "profile";
  }
  </script>
  <div id="centerbox">
    <div id="loginbox">
    <h1 style="margin-bottom:0; text-align:center;">Enter New Password</h1>
    <form id="passreset" action="passreset" method="POST" autocomplete="off">
      <span class="input input-text" style="margin-top:0; margin-bottom:0;">
        <input class="input_field input_field-text" type="password" id="npass" name="npass">
        <label class="input_label input_label-text" for="cnpass">
          <span class="input_label-content input_label-content-text">New Password</span>
        </label>
        <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
          <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
        </svg>
      </span>
      <!-- <input id="npass" name="npass" class="inputbox" type="password" placeholder="New Password" required><br><br> -->
      <span class="input input-text" style="margin-top:0;">
        <input class="input_field input_field-text" type="password" id="cnpass" name="cnpass">
        <label class="input_label input_label-text" for="cnpass">
          <span class="input_label-content input_label-content-text">Confirm Password</span>
        </label>
        <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
          <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
        </svg>
      </span>
      <!-- <input id="cnpass" name="cnpass" class="inputbox" type="password" placeholder="Confirm New Password" required> -->
      <input type="hidden" value="<?php echo $_GET['resetcode'];?>" name="resetcode">
      <button style="align-self:center;"class="stdbutton" type="submit" onclick='return checkPass()'>Change Password</button>
    </form>
    <div style="color:white; font-family:'sitefont';" id="passpointers">
      <h3>Passwords must include:</h3>
      <ul>
        <li>More than 7 characters</li>
        <li>At least 1 capital letter</li>
        <li>At least 1 number</li>
      </ul><br><br>
    </div>
    </div>
  </div>
</div>
</div>
<div id="bottom">
  <div id="bottom-cont">
    <div id="bottom-left">
      <a href="contact">Contact</a>
      <a href="privacy">Privacy</a>
      <a href="terms">Terms</a>
    </div>
    <div id="bottom-right">
      <a href="https://www.instagram.com/ossiatickets/"><i class="fa fa-instagram"></i></a>
      <a href="https://www.facebook.com/ossiatickets/"><i class="fa fa-facebook"></i></a>
      <p>&copy Warp10 LLC 2018 </p>
    </div>
  </div>
</div>
</body>
</html>
