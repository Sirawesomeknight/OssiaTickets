<!DOCTYPE html>
<html>
<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-124570336-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-124570336-1');
</script>
  <title>Ossia</title>
  <link rel="stylesheet" href="CSS/textinput.css">
  <link rel="stylesheet" href="CSS/register.css">
  <link rel="stylesheet" href="CSS/topbar.css">
  <link rel="stylesheet" href="CSS/bottom.css">
  <link rel="stylesheet" href="CSS/dropinput.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://www.google.com/recaptcha/api.js"></script>
  <script src="JS/register.js" defer></script>
  <script src="JS/loadbackground.js" defer></script>
  <script src="JS/classie.js" defer></script>
  <script src="JS/textinput.js" defer></script>
  <script src="JS/checklogin.js" defer></script>
</head>
<body onload="load()">
  <script>
  switch("<?php echo $_GET['error'];?>"){
    case "empty":
    alert("Inputs were empty!");
    case "invalid":
    alert("Invalid characters used to register");
    break;
    case "alreadyregistered":
    alert("User already registered");
    break;
    case "notstrongpass":
    alert("Your password is not strong enough!");
    break;
    case "incompleterefcode":
    alert("Referral code not valid!");
    break;
  }
</script>
<div id="content">
  <div id="tint">
    <div id="navbar">
      <a class="navbutton" href="login" id="login">Login</a>
      <a class="logocont" href="index"><img src="logonotext.png" class="logo"></a>
      <a class="navbutton" href="faq">FAQ</a>
    </div>
  <div id="centerbox">
    <div id="loginbox">
      <h1>Register</h1>
      <form id="registform" method="POST" action="PHP/register" autocomplete="off">
        <span class="input input-text" style="margin-top:0;">
          <input class="input_field input_field-text" type="text" id="firstname" name="firstname">
          <label class="input_label input_label-text" for="firstname">
            <span class="input_label-content input_label-content-text">First Name</span>
          </label>
          <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
          </svg>
        </span>
        <!-- <input type="input" class="inputbox" name="firstname" id="firstname" placeholder="Firstname" required><br><br> -->
        <span class="input input-text" style="margin-top:0;">
          <input class="input_field input_field-text" type="text" id="lastname" name="lastname">
          <label class="input_label input_label-text" for="lastname">
            <span class="input_label-content input_label-content-text">Last Name</span>
          </label>
          <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
          </svg>
        </span>
        <!-- <input type="input" class="inputbox" name="lastname" id="lastname" placeholder="Lastname" required><br><br> -->
        <span class="input input-text" style="margin-top:0;">
          <input class="input_field input_field-text" type="email" id="email" name="email">
          <label class="input_label input_label-text" for="email">
            <span class="input_label-content input_label-content-text">Email</span>
          </label>
          <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
          </svg>
        </span>
        <!-- <input type="email" name="email" class="inputbox" id="email" placeholder="Email" required><br><br> -->
        <span class="input input-text" style="margin-top:0;">
          <input class="input_field input_field-text" type="password" id="pass" name="pass">
          <label class="input_label input_label-text" for="pass">
            <span class="input_label-content input_label-content-text">Password</span>
          </label>
          <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
          </svg>
        </span>
        <!-- <input type="password" id="pass" name="pass" class="inputbox" placeholder="Password" required><br><br> -->
        <div id="passpointers">
          <h3>Passwords must include:</h3>
          <ul>
            <li>More than 7 characters</li>
            <li>At least 1 capital letter</li>
            <li>At least 1 number</li>
          </ul><br><br>
        </div>
        <span class="input input-text" style="margin-top:0;">
          <input class="input_field input_field-text" type="password" id="confpass" name="confpass">
          <label class="input_label input_label-text" for="confpass">
            <span class="input_label-content input_label-content-text">Confirm Password</span>
          </label>
          <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
          </svg>
        </span>
        <input type="hidden" id="shippingAddress" name="shippingAddress">
        <hr>
        <br>
        <br>
        <!-- <button class="stdbutton" id="shippingButton" type="button">Enter Shipping Address</button> -->
        <br>
        <br>
        <div class="g-recaptcha" data-sitekey="6Ld0alEUAAAAAAMyYKzaa246_HApFetwjNdV4rA9"></div>
        <br>
        <button class="stdbutton" id="registbutton" type="button">Register</button>
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
      <a href="terms">Terms</a>
    </div>
    <div id="bottom-right">
      <a href="https://www.instagram.com/ossiatickets/"><i class="fa fa-instagram"></i></a>
      <a href="https://www.facebook.com/ossiatickets/"><i class="fa fa-facebook"></i></a>
      <p>&copy Warp10 LLC 2018 </p>
    </div>
  </div>
</div>
<div id="overlaylayer">
  <div class="overlaydiv" id="shippingDiv">
    <h1>Shipping Address</h1>
    <br>
    <span class="input input-text" style="margin-top:0;">
      <input class="input_field input_field-text" type="text" id="line1">
      <label class="input_label input_label-text" for="line1">
        <span class="input_label-content input_label-content-text">Address Line 1</span>
      </label>
      <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
        <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
      </svg>
    </span>
    <!-- <input type="input" class="inputbox" name="line1" id="line1" placeholder="Address Line 1" required><br><br> -->
    <span class="input input-text" style="margin-top:0;">
      <input class="input_field input_field-text" type="text" id="line2">
      <label class="input_label input_label-text" for="line2">
        <span class="input_label-content input_label-content-text">Address Line 2</span>
      </label>
      <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
        <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
      </svg>
    </span>
    <!-- <input type="input" class="inputbox" name="line2" id="line2" placeholder="Address Line 2"><br><br> -->
    <span class="input input-text" style="margin-top:0;">
      <input class="input_field input_field-text" type="text" id="city">
      <label class="input_label input_label-text" for="city">
        <span class="input_label-content input_label-content-text">City</span>
      </label>
      <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
        <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
      </svg>
    </span>
    <!-- <input type="input" class="inputbox" name="city" id="city" placeholder="City" required><br><br> -->
    <div class='custom-select'>
    <select name="state" id="state">
      <option disabled selected>State</option>
    </select></div><br><br>
    <span class="input input-text" style="margin-top:0;">
      <input class="input_field input_field-text" type="text" id="zipcode" onkeypress="return zipCheck(event)">
      <label class="input_label input_label-text" for="zip">
        <span class="input_label-content input_label-content-text">Zip Code</span>
      </label>
      <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
        <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
      </svg>
    </span>
    <button class="stdbutton" id="validateaddr" type="button">Add Address</button>
  </div>
</div>
</body>
</html>
