var mysql = require('mysql');
var conn = mysql.createConnection({servername: "ballotboxdbinstance.czbxy8tiwkhk.us-east-2.rds.amazonaws.com", username: "ballotbox", password: "ballotbox69", database: "ballotboxdb"});
conn.connect(function (err){
if(err){
console.log("Database Connection Error");
throw(err);
}else{
setInterval(manageServer,60000);
}
});
function manageServer(){
conn.query("DELETE FROM buyers WHERE offertime = 0", function(err2, result2){});
conn.query("SELECT offertime FROM buyers", function(err, result){
var numrows = result.length;
var daysub = ((1 / 60) / 24);
for(var i = 0; i < numrows;i++){
var newtime = result[i] - daysub;
var preupdateq = "UPDATE buyers SET offertime = ";
preupdateq = preupdateq.concat(newtime);
conn.query(preupdateeq,function(err2,result2){});
}
});
if(price == 0 && seattype != "any"){

}else if(price == 0 && seattype == "any"){

}else if(price != 0 && seattype == 'any'){

}else if(price != 0 && seattype != 'any'){

}
/*
Check for valid time offers
Run for loop that checks all rows of buyers
{
search for all tickets from specified event
if(price == 0) -> search for lowest price
else -> search for all tickets setprice <= sellprice
if(seattype != any) -> narrow results down to seattype selected
search for all tickets with sellquantity <= specified quantity;
if(price == 0 && seattype != any) -> choose highest priced ticket
if(price != 0 && seattype == any) -> choose lowest priced ticket
if(price == 0 && seattype == any) -> choose random ticket
if(price != 0 && seattype != any) -> choose oldest ticket offer
if(ticketfound after 6 hours){
contact buyer through email
buyer accepts -> send buyer to pay screen...calculate shipping...buyer pays...money transferred to grabbit...delete database entries...give ticket seller shipping label...ticket ships...money transferred to seller
buyer rejects -> continue waiting, add seller offer to list of rejected offers
}
}
*/
}
