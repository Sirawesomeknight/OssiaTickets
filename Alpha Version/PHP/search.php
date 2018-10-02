<?php
$searchterms = $_GET["searchterms"];
$searchterms = explode(" ",$searchterms);
$servername = "ballotboxdbinstance.czbxy8tiwkhk.us-east-2.rds.amazonaws.com";
$username = "ballotbox";
$password = "ballotbox69";
$database = "ballotboxdb";
$conn = new mysqli($servername,$username,$password,$database);
if($conn->connect_error){
echo "Connection Error";
echo $conn->connect_error;
}else if($conn->error){
  echo $conn->error;
}
$sqlpreq = "SELECT * FROM events";
$results = $conn->query($sqlpreq);
$eventcount = 0;
while($row = mysqli_fetch_assoc($results)){
  $eventnamet = explode(" ",$row["eventname"]);
  $eventloct = explode(" ",$row["eventlocation"]);
  preg_replace('/[^A-Za-z0-9\-]/', '', $eventloct);
  $matchingterms = 0;
  /*
  There has got to be a better way to list out the terms to throw out
  Throw out all special characters in search input
  Numberwords
  Add searches for part of a term
  */
  for($i = 0; $i < sizeof($searchterms); $i++){
    for($j = 0; $j < sizeof($eventnamet); $j++){
      if(strtolower($eventnamet[$j]) != "the" && strtolower($eventname[$j]) != "tour" && strtolower($eventnamet[$j]) != "fest" && strtolower($eventnamet[$j]) != "a" && exemptChars(strtolower($eventnamet[$j]),strtolower($searchterms[$i])) == true){
        $matchingterms++;
      }
    }
    for($j = 0; $j < sizeof($eventloct); $j++){
      if(strtolower($eventloct[$j]) != "the" && strtolower($eventloct[$j]) != "tour" && strtolower($eventloct[$j]) != "fest" && strtolower($eventloct[$j]) != "a" && exemptChars(strtolower($eventloct[$j]),strtolower($searchterms[$i])) == true){
          $matchingterms++;
      }
    }
  }
  $searchfilter[$row["eventid"]] = $matchingterms;
  $eventcount++;
}
$savesfilter = $searchfilter;
rsort($searchfilter);
echo "<tr>";
echo "<td>Event Name</td>";
echo "<td> Event Date / Event Location </td>";
echo "<td> See Tickets </td>";
echo "</tr>";
for($i = 0; $i < sizeof($searchfilter); $i++){
  for($j = 0; $j < sizeof($searchfilter); $j++){
    if($searchfilter[$j] == $savesfilter[$i] && $searchfilter[$j] != 0){
      $prequery = "SELECT * FROM events WHERE eventid = ";
      $query = $prequery . $i;
      $results = $conn->query($query);
      $row = mysqli_fetch_assoc($results);
      echo "<tr>";
      echo "<td>" . $row["eventname"] . "</td>";
      echo "<td>" . $row["eventlocation"] . " " . $row["eventdate"] . "</td>";
      echo "<td><button onclick=goToEvent(" . $i . ")>See Tickets</button></td>";
      echo "</tr>";
    }
  }
}
function exemptChars($queryterm,$searchterm){
$queryterm = str_split($queryterm);
$searchterm = str_split($searchterm);
$matchingchars = 0;
for($i = 0; $i < sizeof($searchterm); $i++){
if($searchterm[$i] == $queryterm[$i]){
$matchingchars++;
}
}
if($matchingchars >= sizeof($searchterm) - 1){
  return true;
}else{
  return false;
}
}
?>
