<!DOCTYPE html>
<html>
<head>
<title>Ossia</title>
</head>
<body>
<h1>Proof Approval</h1>
<script defer>
var conn = new XMLHttpRequest();
conn.onreadystatechange = function(){
  if(this.readyState == 4 && this.status == 200){
    var response = JSON.parse(this.responseText);
    document.getElementById("ordertable").innerHTML = response.orderdata;
    document.getElementById("orderfulfill").innerHTML = response.orderfulfill;
    document.getElementById("users").innerHTML = response.users;
    document.getElementById("events").innerHTML = response.events;
  }
}
conn.open("POST","PHP/consoledata",true);
conn.send();

function fulfill(ticketid){
  submitData('fulfill',ticketid)
}

function approveOrder(ticketid){
  submitData('approve',ticketid);
}

function holdOrder(ticketid){
  submitData('hold',ticketid);
}

function denyOrder(ticketid){
  submitData('deny',ticketid);
}

function submitData(outcome,ticketid){
  var conn = new XMLHttpRequest();
  conn.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      var response = this.responseText;
      if(response != 1){
        alert("An Error Has Occured. Error:" + response);
      }
      location.reload();
    }
  }
  var param = "param=" + outcome + "&ticketid=" + ticketid;
  conn.open("POST","PHP/sellexchange",true);
  conn.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  conn.send(param);
}
</script>
<table id="ordertable">

</table>
<hr>
<h1>Order Fulfillment</h1>
<table id="orderfulfill">

</table>
<hr>
<h1>Users</h1>
<table id="users">

</table>
<hr>
<h1>Events</h1>
<table id="events">

</table>
</body>
</html>
