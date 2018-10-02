<?php
$servername = "ballotboxdbinstance.czbxy8tiwkhk.us-east-2.rds.amazonaws.com";
$username = "ballotbox";
$password = "ballotbox69";
$database = "ballotboxdb";
$conn = new mysqli($servername,$username,$password,$database);
if($conn->connect_error){
echo nl2br("\n Connection Error");
echo $conn->connect_error;
}
echo nl2br("\n Deleting Expired Offers From Buyers...");
$offers = $conn->query("SELECT offertime FROM buyers");
deleterows($offers);
echo nl2br("\n Completed");
echo nl2br("\n Deleting Expired Offers From Sellers...");
$offers = $conn->query("SELECT offertime FROM sellers");
deleterows($offers);
echo nl2br("\n Completed");
$getnumevents = $conn->query("SELECT * FROM events");
echo nl2br("\n Retrieved events");
if(mysqli_num_rows($getnumevents) == 0){
  echo nl2br("\n No events");
  $conn->close();
}else{
echo nl2br("\n Matching Tickets...");
for($i = 0; $i < mysqli_num_rows($getnumevents);$i++){
echo nl2br("\n Eventid " . $i);
$presearchquery = "SELECT userid, eventid, price, seattype, quantity, offertime FROM buyers WHERE eventid = ";
$searchquery = $presearchquery . $i;
$result = $conn->query($searchquery);
if(mysqli_num_rows($result) != 0){
while($row = mysqli_fetch_assoc($result)){
if($row["price"] == 0 && $row["seattype"] == "any"){
echo nl2br("\n Oldest Ticket");
oldestTicket(sorttickets($conn, $i));
}else if($row["price"] != 0 && $row["seattype"] == "any"){
echo nl2br("\n Lowest Price");
lowestPrice(sorttickets($conn, $i));
}else if($row["price"] != 0 && $row["seattype"] != "any"){
echo nl2br("\n Double Filter");
doubleFilter(sortticketseats($conn, $i, $row["seattype"]));
}else if($row["price"] == 0 && $row["seattype"] != "any"){
echo nl2br("\n Highest Price");
highestPrice(sortticketseats($conn, $i, $row["seattype"]));
}
}
}else{
  echo nl2br("\n No tickets founds");
  echo nl2br("\n Moving on...");
}
}
}
$conn->close();
function oldestTicket($finalselection){
if(mysqli_num_rows($finalselection) != 0){
$finalrow = mysqli_fetch_assoc($finalselection);
echo $finalrow["userid"];
}else{
echo nl2br("\n No Tickets Found Oldest Ticket");
}
}
function lowestPrice($finalselection){
if(mysqli_num_rows($finalselection) != 0){
$userid = array(mysqli_num_rows($finalselection));
$price = array(mysqli_num_rows($finalselection));
while($row = mysqli_fetch_assoc($finalselection)){
$userid = $row["userid"];
$price = $row["price"];
}
$oldprices = $price;
sort($price);
$listnum = 0;
for($listnum = 0; $listnum < mysqli_num_rows($finalselection); $listnum++){
if($price[0] == $oldprices[$j]){
echo $userid[$listnum];
break;
}
}
}else{
echo nl2br("\n No Tickets Found Lowest Price");
}
}
function doubleFilter($finalselection){
  if(mysqli_num_rows($finalselection) != 0){
  $userid = array(mysqli_num_rows($finalselection));
  $price = array(mysqli_num_rows($finalselection));
  while($row = mysqli_fetch_assoc($finalselection)){
  $userid = $row["userid"];
  $price = $row["price"];
  }
  $oldprices = $price;
  rsort($price);
  $listnum = 0;
  for($listnum = 0; $listnum < mysqli_num_rows($finalselection); $listnum++){
  if($price[0] == $oldprices[$j]){
  echo $userid[$listnum];
  //get closest price to set price
  break;
  }
  }
}else{
  echo nl2br("\n No Tickets Found Double Filter");
}
}
function highestPrice($finalselection){
  if(mysqli_num_rows($finalselection) != 0){
  $userid = array(mysqli_num_rows($finalselection));
  $price = array(mysqli_num_rows($finalselection));
  while($row = mysqli_fetch_assoc($finalselection)){
  $userid = $row["userid"];
  }
  $oldprices = $price;
  rsort($price);
  $listnum = 0;
  for($listnum = 0; $listnum < mysqli_num_rows($finalselection); $listnum++){
  if($price[0] == $oldprices[$j]){
  echo $userid[$listnum];
  echo $price[0];
  //highest price
  break;
  }
  }
}else{
  echo nl2br("\n No Tickets Found Highest Price");
}
}
function sorttickets($conn, $eventid){
  echo nl2br("\n Sorting Tickets...");
  $sellsearchquery = "SELECT userid, price, seattype FROM sellers WHERE eventid = ";
  $sellsearchquery = $sellsearchquery . $eventid;
  $finalselection = $conn->query($sellsearchquery);
  echo nl2br("\n Sorted Tickets");
  return $finalselection;
}
function sortticketseats($conn, $eventid, $seattype){
  echo nl2br("\n Sorting Tickets With Seats...");
  $sellsearchquery = "SELECT userid, price FROM sellers WHERE eventid = ";
  $sellsearchquery = $sellsearchquery . $eventid;
  $sellsearchquery = $sellsearchquery . " AND seattype = ";
  $sellsearchquery = $sellsearchquery . $seattype;
  $finalselection = $conn->query($sellsearchquery);
  if($finalselection === true){
  }else{
    $conn->error;
  }
  echo nl2br("\n Sorted Tickets");
  return $finalselection;
}
function purchaseTickets($userid){
//if sellquantity > listedq then subtract #tickets available to seller -> do not notify buyer
//if sellquantity < listedq then subtract #tickets available to buyer only if seattype = same -> do not notify buyer
//send email to buyer with purchasing link...buyer has 24 hours to buy
//send email to seller informing them
//once buyer purhcases, delete buyer and seller row
}
function deleterows($offers){
  $currentdate = date(DATE_W3C);
  while($rows = mysqli_fetch_assoc()){
  if($currentdate >= date(DATE_W3C, $rows)){
  $deletion = "DELETE FROM buyers WHERE offertime = ";
  $deletion = $deletion . date(DATE_W3C, $rows)
  $conn->query($deletion);
  }
  }
}
?>
