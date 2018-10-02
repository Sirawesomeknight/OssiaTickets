<!-- TODO - Frontend Format -->
<!DOCTYPE html>
<html>
<head>
<title>Ossia</title>
<link rel="stylesheet" href="CSS/textinput.css">
<link rel="stylesheet" href="CSS/forgot.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="CSS/bottom.css">
<script src="JS/forgot.js" defer></script>
<script src="JS/classie.js" defer></script>
<script src="JS/loadbackground.js" defer></script>
<script src="JS/checklogin.js" defer></script>
<script defer>
if(isLoggedIn == true){
  location.href = "profile";
}
</script>
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
    <div id="loginbox">
    <h1 style="margin-bottom:0;">Forgot Password</h1>
    <form autocomplete="off">
      <span class="input input-text" style="margin-top:0;">
        <input class="input_field input_field-text" type="email" id="username">
        <label class="input_label input_label-text" for="cnpass">
          <span class="input_label-content input_label-content-text">Email</span>
        </label>
        <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
          <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
        </svg>
      </span>
      <button class="stdbutton" type="button" onclick="forgotsys()">Submit</button>
    </form>
    </div>
  </div>
</div>
</div>
<div id="bottom">
  <div id="bottom-cont">
    <div id="bottom-left">
      <a href="contact">Contact</a>
      <a href="privacy">Privacy</a>
      <a href="Terms">Terms</a>
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
