<?php
error_reporting(E_ERROR | E_PARSE);
$direction = $_POST["direction"];
$currentBox = $_POST["currentBox"];
$stream = $_POST["stream"];
$output->html = "";
$sell = ["QUANTITY","TICKETCLASS","SEATING","SALETYPE","PRICE","SELLTOGETHER","REVIEWSELL","PHP/ticketupload"];
$buy = ["QUANTITY","TICKETCLASS","PRICE","FLEXPRICE","MAXPRICE","REVIEWBUY","processingRequest"];
//MERCH
if($stream == "BUY"){
  $stream = $buy;
}else{
  $stream = $sell;
}
$whichBox = "";
if(!empty($stream) && !empty($currentBox)){
for($i = 0; $i < sizeof($stream); $i++){
  if($stream[$i] == $currentBox){
    $whichBox = $stream[$i + $direction];
    $output->html = "<div class='inputs' id='$whichBox'>";
    $output->progress = $i + $direction;
  }
}
}else{
  $whichBox = $direction;
}
switch($whichBox){
  case "QUANTITY":
    $output->html = $output->html . "<h1 style='min-width: 60%;'>Select Quantity</h1>";
    $output->progress = 1;
  break;
  case "PRICE":
    $mode = strtolower($_POST["whichMode"]);
    $output->html = $output->html . "<h1>How much do you want to $mode each ticket for?</h1>";
    if($mode == "buy"){
      $output->progress = 3;
    }else{
      $output->progress = 5;
    }
  break;
  case "SEATING":
    $output->html = $output->html . "<h1>Seating Review</h1>";
    $output->html = $output->html . "<p>Please upload one ticket proof</p>";
    $output->html = $output->html . "<p>Please upload etickets or some proof of your ticket purchase (receipt, picture of physical ticket, etc. etc.)</p>";
    $output->html = $output->html . "<p>File types accepted: PDF, JPG, and PNG</p>";
    $output->progress = 3;
  break;
  case "FLEXPRICE":
    $output->html = $output->html . "<h1>Do you want to list another price if you can't get a ticket at the price you listed?</h1>";
    $output->progress = 4;
  break;
  case "SALETYPE":
    $output->html = $output->html . "<h1>How do you want to sell your ticket?</h1>";
    $output->progress = 4;
  break;
  case "MERCH":
    $output->html = $output->html . "<h1>Thirsty?</h1>";
    $output->html = $output->html . "<p>Its going to be hot out there! Purchase a small 12oz reusable Ossia plastic waterbottle!</p>";
    $output->html = $output->html . "<p>Only $4.00 (This is less than any ticket shipping or service fee our competitors have.)</p>";
    $output->html = $output->html . "<img src='https://s3.us-east-2.amazonaws.com/csrfresources/bottle.jpg' id='bottlejpg'>";
    $output->progress = 7;
  break;
  case "MAXPRICE":
    $output->html = $output->html . "<h1 id='mpricelabel'>Please list a backup price</h1>";
    $output->progress = 5;
  break;
  case "SELLTOGETHER":
    $output->html = $output->html . "<h1>Do you want to sell your tickets together?</h1>";
    $output->progress = 6;
  break;
  case "TICKETCLASS":
    $output->html = $output->html . "<h1>Choose your ticket type</h1>";
    $output->progress = 2;
  break;
  case "BUY":
    require "getTicketData.php";
    $eventid = $_POST["eventid"];
    $classarray = array("Choose Ticket Type");
    $getData = $conn->query("SELECT DISTINCT ticketclass FROM orders WHERE eventid = '$eventid'");
    while($getclasses = mysqli_fetch_assoc($getData)){
      $classarray[sizeof($classarray)] = $getclasses["ticketclass"];
    }
    $output->html = $output->html . beansdropdown($classarray,"ticketclass");
    $output->html = $output->html . beansinputfield("price","Price Per Ticket",true);
    $output->html = $output->html . beansdropdown(array("Absolute Max Price","Yes","No"),"flexprice");
    $output->html = $output->html . beansinputfield("maxprice","Max Price",true);
    $output->html = $output->html . "<input class='inputinfo' type='checkbox' id='Merch' name='merch'>";
    $output->action = $buy[sizeof($buy) - 1];
    $conn->close();
  break;
  case "SELL":
    require "getTicketData.php";
    $eventid = $_POST["eventid"];
    $classarray = array("Choose Ticket Type");
    $getData = $conn->query("SELECT DISTINCT ticketclass FROM orders WHERE eventid = '$eventid'");
    while($getclasses = mysqli_fetch_assoc($getData)){
      if(!empty($getclasses["ticketclass"])){
        $classarray[sizeof($classarray)] = $getclasses["ticketclass"];
      }
    }
    $output->html = $output->html . beansdropdown($classarray,"ticketclass");
    $output->html = $output->html . "<input class='inputinfo' type='hidden' id='Proof' name='filepaths'>";
    $output->html = $output->html . beansdropdown(array("Sale Type","Sell Guaranteed","Set Price"),"saletype");
    $output->html = $output->html . beansinputfield("price","Price Per Ticket",true);
    $output->html = $output->html . beansdropdown(array("Sell Together?","Yes","No"),"selltogether");
    for($i = 0; $i < 10; $i++){
      $output->html = $output->html . "<div id='Seating$i' class='inputinfo'>";
      $output->html = $output->html . "<input enctype='multipart/form-data' accept='.jpg, .png, .jpeg, .pdf' type='file' name='proof[]' class='proofbutton'>";
      $output->html = $output->html . beansinputfield("section[]","Section",false);
      $output->html = $output->html . beansinputfield("row[]","Row",false);
      $output->html = $output->html . beansinputfield("seatnum[]","Seat",false);
      $output->html = $output->html . "</div>";
    }
    $output->action = $sell[sizeof($sell) - 1];
  break;
}
if($whichBox != "BUY" && $whichBox != "SELL"){
  $output->html = $output->html . "</div>";
}
echo json_encode($output);


function beansinputfield($id,$inputvalue,$shouldhide){
  $spanid = ucfirst($id);
  $onpress = "";
  $onfocusout = "";
  if($shouldhide == true){
    $shouldhide = "inputinfo";
    $onpress = "return validPrice(event)";
    $onfocusout = "makePrice(event)";
  }else{
    $shouldhide = "";
  }
  return "<div id='$spanid' class='$shouldhide'><span class='input input-text'>
  <input type='text' id='$id' class='input_field input_field-text $id' name='$id' onkeypress='$onpress' onfocusout='$onfocusout'>
  <label class='input_label input_label-text' for='$id'>
    <span class='input_label-content input_label-content-text'>$inputvalue</span>
  </label>
  <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
    <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
  </svg>
  </span></div>";
}

function beansdropdown($options,$id){
  $default = $options[0];
  $divid = ucfirst($id);
  $output = "<div id='$divid' class='inputinfo'><div class='custom-select'>
    <select id='$id' name='$id'>
    <option disabled selected value=''>$default</option>";
      for($i = 1; $i < sizeof($options); $i++){
        $option = $options[$i];
        $output = $output . "<option value='$option'>$option</option>";
      }
    $output = $output . "</select></div></div>";
    return $output;
}

function submitbutton(){
  return "<p class='actbutton' id='nextSeat'>Add Another</p>";
}
?>
