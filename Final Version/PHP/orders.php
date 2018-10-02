<?php
//Sellers must sell tickets together in the same section or individual ticket
require "getTicketData.php";

$eventid = $_POST["id"];
$ticketclass = $_POST["ticketclass"];
$venueinfo = "SELECT venuepic FROM venues WHERE venuename = (SELECT eventloc FROM events WHERE eventid = ?)";
$venueinfo = $conn->prepare($venueinfo);
$venueinfo->bind_param("s",$eventid);
$venueinfo->execute();
$venueinfo = mysqli_fetch_assoc($venueinfo->get_result());
$output->venueimage = $venueinfo["venuepic"];
$conn->close();
$url = 'https://ossiatickets.com/PHP/lastprices';
$data = array("eventid" => $eventid, "ticketclass" => $ticketclass);

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    ),
    "ssl" => array(
        "verify_peer"=> false,
        "verify_peer_name"=> false,
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$output->pricetable = $result;
echo json_encode($output);
?>
