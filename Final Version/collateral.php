<!DOCTYPE html>
<html>
<head>
<title>Ossia</title>
<link rel="stylesheet" href="CSS/collateral.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/bottom.css">
<link rel="stylesheet" href="CSS/textinput.css">
<link rel="stylesheet" href="CSS/dropinput.css">
<script src="JS/collateral.js" defer></script>
<script src="JS/checklogin.js" defer></script>
<script src="JS/loadbackground.js" defer></script>
<script src="JS/classie.js" defer></script>
<script src="JS/banMobile.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="https://js.stripe.com/v3/"></script>
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
      <h1>Payment & Collateral</h1>
      <p>We collect credit card information for the following reasons:</p>
      <ul>
        <li><p>To pay you once your ticket has been sold</p></li>
        <li><p>To ensure the authenticity of your ticket(s)</p></li>
        <p id="seatlocformat" hidden><?php echo $_POST['seatloc'];?></p>
        <form id="sellform" action="sell" method="POST">
          <input type="hidden" name="eventid" value="<?php echo $_POST['eventid'];?>">
          <input type="hidden" id="seatloc" name="seatloc" value='<?php echo $_POST["seatloc"];?>'>
          <input type="hidden" id="Collateral" name="collateral">
          <input type="hidden" name="selltogether" value="<?php echo $_POST['selltogether'];?>">
          <input type="hidden" name="price" value="<?php echo $_POST['price'];?>">
          <input type="hidden" name="filepaths" value="<?php echo $_POST['filepaths'];?>">
          <input type="hidden" name="delivery" value="<?php echo $_POST['delivery'];?>">
          <input type="hidden" name="ticketclass" value="<?php echo $_POST['ticketclasssell'];?>">
          <div id='recap' class="g-recaptcha" data-sitekey="6Ld0alEUAAAAAAMyYKzaa246_HApFetwjNdV4rA9"></div>
        </form>
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
        <br>
        <br>
        <button type="submit" class="stdbutton">Submit Order</button>
      </form>
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
    document.getElementById("Collateral").value = result.token.id;
    document.getElementById("sellform").submit();
  }
 });
 });
 </script>
      </ul>
    </div>
    <div id="progresscontainer">
      <div id="progress"></div>
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
