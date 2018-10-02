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
  <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="CSS/index.css">
  <link rel="stylesheet" href="CSS/topbar.css">
  <link rel="stylesheet" href="CSS/bottom.css">
  <script src="JS/index.js" defer></script>
  <script src="JS/loadbackground.js" defer></script>
  <script src="JS/checklogin.js" defer></script>
</head>
<body onload="load()">
  <div id="content">
    <div id="tint">
      <div id="navbar">
        <a class="navbutton" href="login" id="login">Login</a>
        <a class="logocont" href="index"><img src="logonotext.png" class="logo"></a>
        <a class="navbutton" href="faq">FAQ</a>
      </div>
      <div id="labelcontainer">
          <h1 id="ossiatitle">OSSIA</h1>
          <div id="searchstuff">
            <input type="search" id="searchbar" placeholder="Search music artists or tours..." autocomplete="off" onsubmit="eventselect(0)">
            <div id="searchsuggestions"></div>
          </div>
        <select id="currentLoc">
          <option value="Chicago, IL" selected>Chicago, IL</option>
          <option value="Philadelphia, PA">Philadelphia, PA</option>
        </select>
      </div>
      <p id="saletype"></p>
      <div id="welcomecontainer">
        <h2 id="firsttitle">Ossia Tickets</h2>
        <p id="msg">The only no fee, true ticket exchange.<br>It's ticketing done better.</p>
        <div id="buysellbuttons">
          <p class="transtype" id="buy" onclick="revealSearch(event)">Buy Tickets</p>
          <p class="transtype" id="sell" onclick="revealSearch(event)">Sell Tickets</p>
          <p class="transtype" id="regist" onclick="regist()">Create Account</p>
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
