<?php
session_start();
 ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="CSS/venue.css">
<script type="text/javascript" src="javascript/venue.js"></script>
<title>BallotBox</title>
</head>
<body>
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
        <div id="topcont">
        </div>
        <div id="tickets">
        <div id="transactions">

        </div>
        <div id="actions">
            <div id="exchangetype">
            </div>
            <div id="exchange">
              <form name="buyersform">
              <div id="priceoptions">
              <input id="mbuy" name="price" type="radio" value="Market Buy" checked>Market Buy
              <input id="sbuy" type="radio" name="price" value="Set Price">Set Price
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
              </select>
              <br>
              <br>
              <p>How long is this offer valid?</p>
              <br>
              <div id="buyslidediv">
              <p id="minmum">1</p>
              <input id="buyslide" type="range" value="10">
              <p id="maxmum">100</p>
              </div>
              <br>
              <p id="currentval">100</p>
              <button onclick="postorder()" id="coin">Submit</button>
              </form>
            </div>
        </div>
      </div>
    </div>
    <div id="adspace2">
    <p>Ad Space 2</p>
    </div>
</div>
<script type = "text/javascript" src="javascript/venue.js" onload="load()"></script>
</body>
</html>
