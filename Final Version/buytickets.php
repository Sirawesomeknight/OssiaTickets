<!DOCTYPE>
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="CSS/buytickets.css">
  <link rel="stylesheet" href="CSS/textinput.css">
  <link rel="stylesheet" href="CSS/dropinput.css">
  <link rel="stylesheet" href="CSS/topbar.css">
  <link rel="stylesheet" href="CSS/bottom.css">
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script src="JS/buyticketsnew.js"></script>
  <script src="JS/classie.js" defer></script>
  <script src="JS/checklogin.js" defer></script>
  <script src="JS/loadbackground.js" defer></script>
  <script src="https://js.stripe.com/v3/"></script>
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
      <div id="content2">
        <div class="infobox" id="mainbox">
          <div id="artistinfo">
            <img id="rigatone">
            <div id="eventlist">
              <h3 id="eventnamelabel"></h3>
              <p id="eventloclabel"></p>
              <p id="eventdatelabel"></p>
              <p id="transacttype" hidden><?php echo $_GET["saletype"]?></p>
            </div>
          </div>
          <div id="order">
              <div id="orderdesc">
                <div id="QUANTITY">
                  <h1 style='min-width: 60%;'>Select Quantity</h1>
                </div>
              </div>
              <div class="inputs" id="Reviewbuy">
                <h1>Review Order</h1>
                <div id="reviewcontents">
                  <div id="left">
                    <p id='btquant'></p>
                    <p id='bppt'></p>
                  </div>
                  <div id="right">
                    <p id='mmt'></p>
                    <p id="dtype">Delivery Type: </p>
                  </div>
                </div>
              </div>
              <div class="inputs" id="Reviewsell">
                <h1>Review Order</h1>
                <p id="tquant"></p>
                <p id="ppt"></p>
                <p>If your ticket is still unsold within 120 hours of the event, your sale type will become best available price.</p>
                <p id="dtype">Delivery Type: <?php echo $_POST['delivery'];?></p>
              </div>
              <form id="orderinfo" method="POST" enctype='multipart/form-data'>
                <input type="hidden" id="eventid" name="eventid" value="<?php echo $_GET['eventid'];?>">
                <input type="hidden" id="eventname" name="eventname">
                <input type="hidden" id="eventdate" name="eventdate">
                <input type="hidden" id="eventloc" name="eventloc">
                <input type="hidden" id="delivery" name="delivery">
                <div id="Quantity" class="inputinfo">
                <div class="custom-select">
                  <select id='quantity' name='quantity'>
                  <option selected disabled value=''>Quantity</option>
                  <option value='1'>1</option>
                  <option value='2'>2</option>
                  <option value='3'>3</option>
                  <option value='4'>4</option>
                  <option value='5'>5</option>
                  <option value='6'>6</option>
                  <option value='7'>7</option>
                  <option value='8'>8</option>
                  <option value='9'>9</option>
                  <option value='10'>10</option>
                </select></div></div>
              </form>
          </div>
          <p class="actbutton" id='submit'>&mdash;&mdash;></p>
          <div id="lowerbox">
            <div id="progresscontainer">
              <div id="progress"></div>
            </div>
            <p class="actbutton" id='back'><&mdash;&mdash;</p>
          </div>
        </div>
        <div class="infobox" id="lastprices">
          <h1>Last Prices</h1>
          <table id="pricetable">
            <tr>
              <th>Section</th>
              <th>Prices</th>
            </tr>
          </table>
        </div>
        <div class="infobox" id="seatingmap">
          <h1>Seating Map</h1>
          <img id="venueimage">
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
  <div id="overlay">
    <img id="loadingicon" src="https://s3.us-east-2.amazonaws.com/csrfresources/loadingicon.gif">
  </div>
  <div id="dragdropoverlay">

  </div>
</body>
</html>
