var oreq;
var iteration;
var eventdate;
var isInitialMode;
//true don't send eventid
//false send eventid
function load(){
isInitialMode = true;
document.getElementById("about").addEventListener("click", goAbout);
document.getElementById("selltix").addEventListener("click", goSellTix);
document.getElementById("faq").addEventListener("click", goFAQ);
document.getElementById("contact").addEventListener("click",goContact);
document.getElementById("logobox").addEventListener("click", goHome);
document.getElementById("sellslidediv").addEventListener("mouseover", changeUpdate);
document.getElementById("sellslidediv").addEventListener("mouseout", changeUpdate);
document.getElementById("sellslide").value = 50;
document.getElementById("sell").addEventListener("click",clear);
oreq = new XMLHttpRequest();
iteration = 0;
oreq.onreadystatechange = function() {
  if(this.readyState == 4 && this.status == 200){
    if(iteration == 0){
    document.getElementById("events").innerHTML = this.responseText;
  }else if(iteration == 1){
    eventdate = new Date(this.responseText);
    updateValue();
    iteration = 3;
    callPHP();
  }else if(iteration == 3){
    document.getElementById("seattypes").innerHTML = this.responseText;
  }
  }
};
callPHP();
}
function callPHP(){
  var preurl = "PHP/sellserver.php?it=";
  var url = preurl.concat(iteration);
  if(iteration != 0){
    url = url.concat("&ed=");
    url = url.concat(document.getElementById("events").value);
  }
  oreq.open("GET",url,true);
  oreq.send();
}
function editSeattypes(){
val = document.getElementById("events").value;
if(val != "Default"){
iteration = 1;
callPHP();
}
}
var updater;
var state = false;
function changeUpdate(){
if(state == false){
updater = setInterval(updateValue,200);
state = true;
}else{
clearInterval(updater);
state = false;
}
}
var days;
function updateValue(){
var rn = new Date();
days = Math.floor((eventdate - rn) / 1000 / 60 / 60 / 24);
document.getElementById("currentval").innerHTML = Math.floor((sellslide.value / 100) * days);
document.getElementById("maxmum").innerHTML = days;
}
function postorder(){
  var hasSubmitted = false;
  var sendprice = document.getElementById("sell").value;
  var seattype = document.getElementById("seattypes").value;
  var quantity = document.getElementById("quantity").value;
  var adddays = Math.floor(document.getElementById("buyslide").value / 100 * days);
  var time = new Date();
  time.setDate(time.getDate() + adddays);
  var eventid = document.getElementById("events").value;
  var preurl = "PHP/sellserver.php?it=2&";
  var vpre = ['&sendprice=','&seattype=','&quantity=','&time=','eventid='];
  var url = preurl.concat(vpre[4].concat(eventid.concat(vpre[0].concat(sendprice.concat(vpre[1].concat(seattype.concat(vpre[2].concat(quantity.concat(vpre[3].concat(time))))))))));
  oreq.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200 && hasSubmitted == false){
      oreq.open("GET","PHP/servermanager.php",true);
      oreq.send();
      hasSubmitted = true;
    }
  };
  oreq.open("GET",url,true);
  oreq.send();
}
function goAbout(){
  location.href = "about.html";
}
function goBuyTix(){
  location.href = "buytix.html";
}
function goSellTix(){
  location.href = "selltix.php";
}
function goFAQ(){
  location.href = "faq.html";
}
function goContact(){
  location.href = "contact.html";
}
function goHome(){
  location.href = "index.html";
}
function clear(){
    document.getElementById("sell").value = "";
}
