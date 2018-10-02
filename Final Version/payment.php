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
<link rel="stylesheet" href="CSS/payment.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/bottom.css">
<link rel="stylesheet" href="CSS/textinput.css">
<link rel="stylesheet" href="CSS/dropinput.css">
<script src="JS/checklogin.js" defer></script>
<script src="JS/payment.js" defer></script>
<script src="JS/loadbackground.js" defer></script>
<script src="JS/classie.js" defer></script>
<script src="JS/banMobile.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
var cookieSet = "<?php echo isset($_COOKIE['cartinfo']);?>";
if(cookieSet == false){
  alert("You do not have any tickets in your cart or your cart expired");
  location.href = "index";
}
var error = "<?php echo $_GET['error'];?>";
switch(error){
  case "eregist":
    alert("This email is already registered. Please login to continue.");
  break;
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
    <div id="inputs">
      <h1>Pay & Submit</h1>
      <p>All shipping commitments are guaranteed</p>
      <br>
      <table id="summary">
        <tr id="labels">
          <td>Item</td>
          <td>Quantity</td>
          <td>Price</td>
        </tr>
        <tr>
          <td><?php if(isset($_COOKIE["cartinfo"])){echo json_decode(urldecode($_COOKIE["cartinfo"]))->eventname;}?></td>
          <td id="quant"><?php if(isset($_COOKIE["cartinfo"])){echo json_decode(urldecode($_COOKIE["cartinfo"]))->quantity;}?></td>
          <td id="tprice">$<?php if(isset($_COOKIE["cartinfo"])){echo json_decode(urldecode($_COOKIE["cartinfo"]))->price;}?></td>
        </tr>
        <tr id="mrow">
          <td>Water Bottle</td>
          <td>1</td>
          <td id="wprice">$4.38</td>
        </tr>
        <tr>
          <td>Service & Processing Fees</td>
          <td></td>
          <td>$0.00</td>
        </tr>
        <tr id="shippingfees">
          <td>Shipping Cost</td>
          <td></td>
          <td id="shippingfee"></td>
        </tr>
        <tr>
          <td>Discounts/Amount Off</td>
          <td></td>
          <td id="discnt"><?php
          if(isset($_COOKIE["loginid"])){
          $sessionid = $_COOKIE["loginid"];
          require "PHP/protocol.php";
          require "PHP/getTicketData.php";
          require "config.php";
          $discount = 0;
          try{
            $preparing = $conn->prepare("SELECT customerid FROM users WHERE sessionid = ?");
            $preparing->bind_param("s",$sessionid);
            $preparing->execute();
            $getCustomer = \Stripe\Customer::retrieve(openssl_decrypt(mysqli_fetch_assoc($preparing->get_result())["customerid"],"AES-256-CBC",$decryption->key,0,$decryption->IV));
            //$discount = $getCustomer->discount->coupon->amount_off;
            $discount += $getCustomer->account_balance;
          }catch(Exception $e){}
          if(!empty($discount)){
            echo "$" . doubleval($discount) / 100;
          }else{
            echo "$0.00";
          }
          $conn->close();
        }else{
          echo "$0.00";
        }
          ?></td>
        </tr>
        <tr>
          <td>Total</td>
          <td></td>
          <td id="totalpay"></td>
        </tr>
      </table>
      <br>
      <br>
      <div id="requestInfo">
      <span id='neme' class='input input-text'>
      <input type='text' id='fullnameu' class='input_field input_field-text' value=''>
      <label class='input_label input_label-text' for='fullnameu'>
        <span class='input_label-content input_label-content-text'>Full Name</span>
      </label>
      <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
        <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
      </svg>
      </span>
      <span id='emeil' class='input input-text'>
      <input type='text' id='emailu' class='input_field input_field-text' value=''>
      <label class='input_label input_label-text' for='emailu'>
        <span class='input_label-content input_label-content-text'>Email</span>
      </label>
      <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
        <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
      </svg>
      </span>
      </div>
      </br>
      <div id="requestShipping">
      <ul>
        <li>Shipping (Varies)<br>Orders executed within 48 hours of the event cannot be guaranteed to arrive</li>
        <br>
        <li>Will Call/Pickup (Free)<br>Available on event-by-event basis</li>
      </ul>
      <br>
      <div class='custom-select'>
        <select id="shipping">
          <option disabled selected>Shipping Option</option>
        </select>
      </div>
      <br>
      <div id="addressdiv">
        <h2 class="useraddresslabel">Use this address?</h2>
        <p id="useraddress"></p>
        <h2 class="useraddresslabel">or enter a new one</h2>
      </div>
      <br>
      <button class="stdbutton" id="shippingButton" type="button">Enter Shipping Address</button>
      <br>
      <br>
      <br>
    </div>
    <form id="buyform" action="buy" method="POST">
    <div id='recap' class='g-recaptcha' data-sitekey='6Ld0alEUAAAAAAMyYKzaa246_HApFetwjNdV4rA9'></div>
    <input type="hidden" id="token" name="stripeToken">
    <input type="hidden" id="stripeShippingName" name="stripeShippingName">
    <input type="hidden" id="stripeEmail" name="stripeEmail">
    <input type="hidden" id="shippingOption" name="shippingOption">
    <input type="hidden" id="shippingAddress" name="shippingAddress">
    <br>
    <br>
    <div class='custom-select'>
      <select id="whichMethod" name="paymethod">
        <option disabled selected>Payment Method</option>
      </select>
    </div>
    </form>
    <br>
    <hr>
    <h1>Secure Checkout</h1>
    <br>
    <p>You will not be charged until your order is filled</p>
    <p>We do not store credit cards on our servers</p>
    <br>
    <br>
    <button type="submit" class="stdbutton" id="submitorder">Submit Order</button>
      <form action="/charge" method="POST" id="payment-form">
      <div class="form-row">
        <label for="card-element">
          Credit or debit card
        </label>
        <div id="card-element">
          <!-- A Stripe Element will be inserted here. -->
        </div>

        <!-- Used to display form errors. -->
        <div id="card-errors" role="alert"></div>
      </div>
      <div id="acceptedcards">
        <i class="fa fa-cc-visa" style="color:navy;"></i>
        <i class="fa fa-cc-amex" style="color:blue;"></i>
        <i class="fa fa-cc-mastercard" style="color:red;"></i>
        <i class="fa fa-cc-discover" style="color:orange;"></i>
      </div>
      <br>
      <br>
      <button type="submit" class="stdbutton">Submit Order</button>
     <script>
var stripe = Stripe('pk_live_uernK9Jly8uIssvCzYdbS1Ly');

// Create an instance of Elements.
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
base: {
 color: '#32325d',
 lineHeight: '18px',
 fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
 fontSmoothing: 'antialiased',
 fontSize: '16px',
 '::placeholder': {
   color: '#aab7c4'
 }
},
invalid: {
 color: '#fa755a',
 iconColor: '#fa755a'
}
};

// Create an instance of the card Element.
var card = elements.create('card', {style: style});

// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');

// Handle real-time validation errors from the card Element.
card.addEventListener('change', function(event) {
var displayError = document.getElementById('card-errors');
if (event.error) {
 displayError.textContent = event.error.message;
} else {
 displayError.textContent = '';
}
});

// Handle form submission.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
event.preventDefault();

stripe.createToken(card).then(function(result) {
 if (result.error) {
   // Inform the user if there was an error.
   var errorElement = document.getElementById('card-errors');
   errorElement.textContent = result.error.message;
 } else {
   // Send the token to your server
   var shippingCondition = 2;
   if(window.getComputedStyle(document.getElementById("requestShipping")).getPropertyValue("display") != "none"){
     document.getElementById("shippingOption").value = document.getElementById("shipping").value;
     shippingCondition = ((document.getElementById("shippingAddress").value != "" || document.getElementById("shipping").value == "wcall") && document.getElementById("shippingOption").value != "");
   }
   var infoCondition = 2;
   if(isLoggedIn == false){
     document.getElementById("stripeShippingName").value = document.getElementById("fullnameu").value;
     document.getElementById("stripeEmail").value = document.getElementById("emailu").value;
     infoCondition = (document.getElementById("fullnameu").value != "" && document.getElementById("emailu").value != "");
   }
   if(infoCondition != 0 && shippingCondition != 0){
   document.getElementById("token").value = result.token.id;
   document.getElementById("buyform").submit();
  }else{
   alert("You must complete all fields to continue");
  }
 }
});
});
</script>
    </form>
    </div>
    <div id="trustlabels">
      <img src="https://s3.us-east-2.amazonaws.com/csrfresources/stripe.png">
      <span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=39WhKOgmp5Rvkrmg5PBlAYfjDddYuiegsgMykqIOXfqT62Jxne8TBCeMR3J3"></script></span>
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
    <br>
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
<script defer>
if(<?php
if(isset($_COOKIE["loginid"])){
require "PHP/getTicketData.php";
require "PHP/protocol.php";
$sessionid = $_COOKIE["loginid"];
$preparing = $conn->prepare("SELECT email FROM users WHERE sessionid = '$sessionid'");
$preparing->bind_param("s",$sessionid);
$preparing->execute();
$isLoggedIn = mysqli_fetch_assoc($preparing->get_result())["email"];
if($isLoggedIn == ""){
  echo "true";
}else{
  echo "false";
}
$conn->close();
}else{
  echo "true";
}
?> == true){
  document.getElementById("requestInfo").style.display = "flex";
}
if("<?php echo json_decode(urldecode($_COOKIE["cartinfo"]))->delivery;?>" == "Shipping"){
  document.getElementById("requestShipping").style.display = "flex";
}
if(<?php if(json_decode(urldecode($_COOKIE["cartinfo"]))->merch == "true"){echo "true";}else{echo "false";}?> == true){
  document.getElementById("mrow").style.display = "inherit";
}
</script>
</body>
</html>
