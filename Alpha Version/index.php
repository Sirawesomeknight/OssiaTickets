<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="CSS/index.css">
<script type="text/javascript" src="javascript/index.js"></script>
<title>Grabbit</title>
</head>
<body onload="load()">
<?php
session_start();
$_SESSION["eventid"][0] = "0";
?>
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
    <div id="hist">
    <p id="thist">History of ticket purchases</p>
    </div>
    <div id="centcent">
        <div id="searchbarbox">
        <input type="input" value="Search..." id="searchbar" onkeydown="search()">
        </div>
        <div id="featuresbox">
        <p>Key Features</p>
        </div>
        <div id="bottomcenter">
            <div id="upcomingevents">
            <p>Upcoming Events</p>
            </div>
            <div id="collegeevents">
            <p>College Events</p>
            </div>
        </div>
    </div>
    <div id="adspace2">
    <p>Ad Space 2</p>
    </div>
</div>
</body>
</html>
