<?php
setcookie("cartinfo","",time() + 1,"/","ossiatickets.com",true,true);
?>
<!DOCTYPE html>
<html>
<head>
<title>Ossia</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="CSS/complete.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/bottom.css">
<script src="JS/complete.js" defer></script>
<script src="JS/loadbackground.js" defer></script>
<script src="JS/checklogin.js" defer></script>
<script src="JS/banMobile.js"></script>
</head>
<body onload="load()">
  <div id="content">
    <div id="tint">
      <div id="navbar">
        <a class="navbutton" href="login" id="login">Login</a>
        <a class="logocont" href="index"><img src="logonotext.png" class="logo"></a>
        <a class="navbutton" href="faq">FAQ</a>
      </div>
      <div id="centerbox">
        <div class="outcomebox" id="ordersubmitted">
          <h1>Thank you for submitting your order</h1>
          <p>We are getting you a ticket.</p>
          <p>Once we fulfill your order, we will contact you right away.</p>
        </div>
        <div class="outcomebox" id="foundticket">
          <h1>We got your ticket!</h1>
          <p>We are now processing your ticket order.</p>
          <p>We will follow up shortly with a delivery email.</p>
        </div>
        <div class="outcomebox" id="paymenterror">
          <h1>Payment Error :(</h1>
          <p>Unfortunately, there was a payment error</p>
          <p>Please contact us at 1-(773)-750-6005 or at tickets@ossiatickets.com</p>
        </div>
        <script>
        var whichComplete = "<?php echo $_GET["exchange"];?>";
        if(whichComplete == "false"){
          document.getElementById("ordersubmitted").style.display = "block";
        }else if(whichComplete == "error" || whichComplete == "authexecerror"){
          document.getElementById("paymenterror").style.display = "block";
        }else{
          document.getElementById("foundticket").style.display = "block";
        }
      </script>
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
