<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="CSS/selltix.css">
<script type="text/javascript" src="javascript/selltix.js"></script>
<title>BallotBox</title>
</head>
<body onload="load()">
<div id="top">
    <img id="logobox" src="logo.png">
    <div id="centertop">
        <div id="adspace1">
        <p>Ad Space 1</p>
        </div>
        <div id="menu">
                <button id="about">About</button>
                <button id="selltix">Sell Tickets</button>
                <button id="faq">FAQ/Help</button>
                <button id="contact">Contact</button>
        </div>
    </div>
    <div id="options">
        <p>Login/Register</p>
        <p>Report Ticket</p>
        <div id="loginreg">
        </div>
        <div id="report">
        </div>
    </div>
</div>
<div id="center">
    <div id="content">
      <form name="sellersform">
      <br>
      <br>
      <select onclick="editSeattypes()" id="events">
      </select>
      <div id="priceoptions">
      <input id="ssell" name="price" type="input" value="How much do you want to sell for?">
      </div>
      <br>
      <br>
      <select id="quantity">
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
      <option value="7">7</option>
      <option value="8">8</option>
      <option value="9">9</option>
      <option value="10">10</option>
      </select>
      <br>
      <br>
      <select id="seattypes">
        <option>Default</option>
      </select>
      <br>
      <br>
      <p>How long is this offer valid?</p>
      <br>
      <div id="sellslidediv">
      <p id="minmum">1</p>
      <input id="sellslide" type="range" value="10">
      <p id="maxmum">100</p>
      </div>
      <br>
      <p id="currentval">100</p>
      <button onclick="postorder()" id="coin">Submit</button>
      </form>
    </div>
    <div id="adspace2">
    <p>Ad Space 2</p>
    </div>
</div>
</body>
</html>
