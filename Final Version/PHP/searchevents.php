<?php
require_once "getTicketData.php";
$searchquery = $_POST["search"];
$location = $_POST["location"];
$searchquery = str_replace('&','and', $searchquery);
$searchquery = preg_replace('/(and)(the)(tour)(an)(concert)(event)/i','', $searchquery);
$searchquery = preg_replace('/[^a-zA-Z0-9]/','_', $searchquery);
$numberwords = ["one","two","three","four","five","six","seven","eight","nine"];
for($i = 0; $i < 10; $i++){
$searchquery = str_replace("$i","$numberwords[$i]",$searchquery);
}
$hasRun = false;
$result = $conn->prepare("SELECT eventname, eventloc, eventid, eventdate, eventprof, deliverytype FROM events WHERE (eventname LIKE ? OR eventloc LIKE ?) AND canSearch = 1 ORDER BY CASE WHEN eventname LIKE ? AND eventloc LIKE ? THEN 0 WHEN eventname LIKE ? THEN 1 WHEN eventname LIKE ? THEN 2 WHEN eventloc LIKE ? THEN 3 ELSE 4 END");
$result->bind_param("sssssss",$a = "%$searchquery%",$b = "%$searchquery%",$c = "$searchquery%",$d = "%$location%",$e = "$searchquery%",$f = "$searchquery%",$g = "$searchquery%");
$result->execute();
$finalresult = $result->get_result();
while($row = $finalresult->fetch_assoc()){
  $eventname = $row["eventname"];
  $eventloc = $row["eventloc"];
  $eventdate = $row["eventdate"];
  $eventprof = $row["eventprof"];
  $eventid = $row["eventid"];
  $delivery = $row["deliverytype"];
  echo "<h2>$eventname</h2>";
  echo "<p>$eventloc</p>";
  echo "<p>$eventdate</p>";
  echo "<p hidden>$delivery</p>#";
  echo "$eventid#";
  echo "$eventprof#";
  $hasRun = true;
}
if($hasRun == false){
  echo 0;
}
$conn->close();
?>
