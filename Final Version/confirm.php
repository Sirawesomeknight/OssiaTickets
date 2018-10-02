<!DOCTYPE html>
<html>
<head>
<title>Ossia</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="CSS/registered.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/bottom.css">
<script src="JS/registered.js" defer></script>
<script src="JS/loadbackground.js" defer></script>
<script src="JS/checklogin.js" defer></script>
</head>
<body onload="load()">
  <?php
  $email = $_GET["conf"];
  require_once "PHP/getTicketData.php";
  $email = str_replace(" ","+",$email);
  $moneymoves = $conn->prepare("UPDATE users SET confirmed = 1 WHERE email = ?");
  $moneymoves->bind_param("s",$email);
  $moneymoves->execute();
  $conn->close();
  ?>
  <div id="content">
    <div id="tint">
      <div id="navbar">
        <a class="navbutton" href="login" id="login">Login</a>
        <a class="logocont" href="index"><img src="logonotext.png" class="logo"></a>
        <a class="navbutton" href="faq">FAQ</a>
      </div>
      <div id="centerbox">
        <div id="loginbox">
          <h1>Email Confirmed!</h1>
          <p>Welcome to Ossia Tickets! Go login and get your tickets!</p>
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
