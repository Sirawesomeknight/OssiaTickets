<!DOCTYPE html>
<html>
<head>
<title>Ossia</title>
<link rel="stylesheet" href="CSS/confirmsell.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/bottom.css">
<script src="JS/confirmsell.js" defer></script>
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
    <div id="ordersubmitted">
      <h1>Thank you for submitting your order</h1>
      <p>We will likely sell your ticket in the coming days.</p>
      <p>Once we sell your ticket, we will contact you right away.</p>
    </div>
    <div id="error">
      <h1>Unknown Error :(</h1>
      <p>Unfortunately, there was a unknown error</p>
      <p>Please contact us at 1-(773)-750-6005 or at tickets@ossiatickets.com</p>
    </div>
    <script>
      if("<?php echo $_POST["error"];?>" == "error"){
        document.getElementById("ordersubmitted").style.display = "none";
        document.getElementById("error").style.display = "flex";
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
