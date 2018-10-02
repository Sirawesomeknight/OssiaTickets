<?php
if(!empty($_POST["eventid"]) && !empty($_POST["eventname"]) && !empty($_POST["eventloc"]) && !empty($_POST["eventdate"]) && !empty($_POST["quantity"]) && is_numeric($_POST["price"]) && is_numeric($_POST["maxprice"]) && !empty($_POST["delivery"]) && !empty($_POST["ticketclass"]) && $_POST["price"] <= 10000 && $_POST["flexprice"] <= 10000){
$cartinfo->eventid = $_POST["eventid"];
$cartinfo->eventname = $_POST["eventname"];
$cartinfo->eventloc = $_POST["eventloc"];
$cartinfo->eventdate = $_POST["eventdate"];
$cartinfo->quantity = $_POST["quantity"];
$cartinfo->price = $_POST["price"];
$cartinfo->flexprice = $_POST["maxprice"];
$cartinfo->merch = $_POST["merch"];
$cartinfo->delivery = $_POST["delivery"];
$cartinfo->ticketclass = $_POST["ticketclassbuy"];
setcookie("cartinfo",json_encode($cartinfo),time() + 3600,"/","ossiatickets.com",true,true);
}
/*
echo "eventid" . !empty($_POST["eventid"]);
echo "eventname" . !empty($_POST["eventname"]);
echo "eventloc" . !empty($_POST["eventloc"]);
echo "eventdate" . !empty($_POST["eventdate"]);
echo "quantity" . !empty($_POST["quantity"]);
echo "price" . is_numeric($_POST["price"]);
echo "flexprice" . is_numeric($_POST["maxprice"]);
echo "flexprice is ". $_POST["maxprice"];
echo "delivery" . !empty($_POST["delivery"]);
echo "ticketclass" . !empty($_POST["ticketclass"]);
echo "lessprice" . $_POST["price"] <= 10000;
echo "lessflex" . $_POST["flexprice"] <= 10000;
*/
?>
<html>
<script src="JS/banMobile.js"></script>
<script>
location.href = "payment";
</script>
</html>
