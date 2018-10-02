/*
sellers (seller int, eventname varchar(255), seat varchar(255), price int, timesell int)
"CREATE TABLE events (eventname varchar(255), eventid int, eventdate date, eventlocation varchar(255), imgpath varchar(255))"
CREATE TABLE users (username varchar(20),password varchar(40),userid int NOT NULL AUTO_INCREMENT,PRIMARY KEY(userid))
2 Chainz, 1, Raleigh, NC, The Ritz, 9/13/2017 8:00PM
*/

var mysql = require('mysql');
var con = mysql.createConnection({host: "ballotboxdbinstance.czbxy8tiwkhk.us-east-2.rds.amazonaws.com",user: "ballotbox",password: "ballotbox69",database: "ballotboxdb"});
con.connect(function(err) {
if (err){
console.log("Not Connected");
throw(err);
}else{
console.log("Connected");
var sql = ["SELECT * FROM events"];
for(var i = 0; i < sql.length; i++){
con.query(sql[i], function (err, result) {
  if (err){
  console.log("failed");
  throw err;
  con.end();
  }
  console.log(result);
});
}
console.log("finished");
con.end();
}
});
