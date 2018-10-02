var eventdate;
var oreq;
function load(){
document.getElementById("about").addEventListener("click", goAbout);
document.getElementById("selltix").addEventListener("click", goSellTix);
document.getElementById("faq").addEventListener("click", goFAQ);
document.getElementById("contact").addEventListener("click",goContact);
document.getElementById("logobox").addEventListener("click", goHome);
document.getElementById("buyslidediv").addEventListener("mouseover", changeUpdate);
document.getElementById("buyslidediv").addEventListener("mouseout", changeUpdate);
document.getElementById("buyslide").value = 50;
document.getElementById("sbuy").addEventListener("click", priceselect);
document.getElementById("mbuy").addEventListener("click", priceselect);
oreq = new XMLHttpRequest();
var iteration = 0;
oreq.onreadystatechange = function(){
if(this.readyState == 4 && this.status == 200){
switch(iteration){
case 0:
document.getElementById("topcont").innerHTML = this.responseText;
break;
case 1:
document.getElementById("seattypes").innerHTML = this.responseText;
break;
}
iteration++;
if(iteration < 2){
callPHP(oreq, iteration);
}
}
};
callPHP(oreq, iteration);
}
function callPHP(oreq, iteration){
var preurl = "PHP/venueserver.php?it=";
var url = preurl.concat(iteration);
var theid = location.search;
theid = theid.substring(theid.length - 1,theid.length);
preurl = "&id=";
url = url.concat(preurl.concat(theid));
oreq.open("GET",url,true);
oreq.send();
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
document.getElementById("currentval").innerHTML = Math.floor((buyslide.value / 100) * days);
document.getElementById("maxmum").innerHTML = days;
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
    document.getElementById("buy").value = "";
}
function priceselect(){
if(document.getElementById("sbuy").checked == true){
  var buyinput = document.createElement("input");
  buyinput.type = "text";
  buyinput.value = "How much do you want to pay?";
  buyinput.id = "buy";
  buyinput.addEventListener("click",clear);
  document.getElementById("priceoptions").appendChild(buyinput);
}else{
  var buyid = document.getElementById("buy");
  if(buyid){
    document.getElementById("priceoptions").removeChild(document.getElementById("buy"));
 }
}
}
function postorder(){
  var sendprice;
  var hasSubmitted = false;
  if(document.getElementById("sbuy").checked == true){
      sendprice = document.getElementById("buy").value;
  }else{
      sendprice = "mkt";
  }
  var seattype = document.getElementById("seattypes").value;
  var quantity = document.getElementById("quantity").value;
  var adddays = Math.floor(document.getElementById("buyslide").value / 100 * days);
  var time = new Date();
  time.setDate(time.getDate() + adddays);
  var preurl = "PHP/venueserver.php?it=3&";
  var vpre = ['sendprice=','&seattype=','&quantity=','&time='];
  var url = preurl.concat(vpre[0].concat(sendprice.concat(vpre[1].concat(seattype.concat(vpre[2].concat(quantity.concat(vpre[3].concat(time))))))));
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
