/*
REQUIRED FIELDS CHECK
ONFIELD CHANGE EFFECTS MARKET PRICES
*/
var iteration = 0;
var marketprices = "";
var marketprice = ["","","","","","","","","",""];
var quantity = 1;
var currentOffset = 1100;
var oldQuantity = 1;
function load(){
  document.getElementById("stix").addEventListener("click",selltix);
  document.getElementById("abt").addEventListener("click",faq);
  document.getElementById("ctus").addEventListener("click",contactus);
  document.getElementById("logo").addEventListener("click",home);
  /*
  document.getElementById("msell").addEventListener("click", priceselect);
  */
  document.getElementById("registerbutt").addEventListener("click",signin);
  document.getElementById("sellsub").addEventListener("click",postorder);
  document.getElementById("quantity").addEventListener("change",updateQuantity);
  oreq = new XMLHttpRequest();
  iteration = 0;
  oreq.onreadystatechange = function() {
    if(this.readyState == 4 && this.status == 200){
      if(iteration == 0){
      document.getElementById("artisttitle").innerHTML = this.responseText;
    }else if(iteration == 1){
      document.getElementById("loctime").innerHTML = this.responseText;
    }else if(iteration == 2){
      marketprices = this.responseText;
      if(isGA == 2){
        marketprice = split(",",marketprices);
      }
    }
    if(iteration <= 2){
      callPHP();
    }
    iteration++;
    }
  };
  callPHP();
}
function signin(){
  location.href="signupregister.html";
}
function selltix(){
  location.href="selltix.html";
}
function faq(){
  location.href= "faq.html";
}
function contactus(){
  location.href="contactus.html";
}
function home(){
  location.href = "index.html";
}
function clear(){
  document.getElementById("sell").value = "";
}
function updateQuantity(){
  quantity = document.getElementById("quantity").value;
}
var breakl = ["","","","","","","","","",""];
var section = ["","","","","","","","","",""];
var row = ["","","","","","","","","",""];
var seat = ["","","","","","","","","",""];
var label = ["","","","","","","","","",""];
var seats;
function detectseatspre(){
  offsetBottom(90,false);
  document.getElementById("secondoption").removeChild(document.getElementById("overalldiv"));
  detectseats();
}
function detectseats(){
  document.getElementById("quantity").addEventListener("change",detectseatspre);
  var overalldiv = document.createElement('div');
  overalldiv.id = "overalldiv";
  document.getElementById("secondoption").appendChild(overalldiv);
  var numseats = parseInt(document.getElementById("quantity").value);
  seats = numseats;
  for(var i = 0; i < numseats; i++){
    label[i] = document.createElement('h4');
    section[i] = document.createElement('input');
    row[i] = document.createElement('input');
    seat[i] = document.createElement('input');
    var tstring = "Ticket ";
    label[i].innerHTML = tstring.concat(i + 1);
    section[i].style.width = "60px";
    section[i].style.position="relative";
    row[i].style.position="relative";
    row[i].style.marginLeft = "20px";
    row[i].style.width = "60px";
    seat[i].style.position="relative";
    seat[i].style.marginLeft = "20px";
    seat[i].style.width = "60px";
    section[i].placeholder = "Section";
    row[i].placeholder = "Row";
    seat[i].placeholder = "Seat #";
    breakl[i] = document.createElement("br");
    document.getElementById("overalldiv").appendChild(label[i]);
    document.getElementById("overalldiv").appendChild(section[i]);
    document.getElementById("overalldiv").appendChild(row[i]);
    document.getElementById("overalldiv").appendChild(seat[i]);
    document.getElementById("overalldiv").appendChild(breakl[i]);
  }
}
function clearseats(){
  document.getElementById("secondoption").removeChild(document.getElementById("overalldiv"));
  document.getElementById("quantity").removeEventListener("change",detectseatspre);
}
function prepricechange(){
  document.getElementById("thirdoption").removeChild(pricediv);
  if(isMarketSell == 1){
    offsetBottom(30,false);
    preprice("msell");
  }else if(isMarketSell == 2){
    offsetBottom(90,false);
    preprice("ssell");
  }
}
var pricediv = "";
function preprice(button){
  pricediv = document.createElement("div");
  pricediv.id = "pricediv";
  document.getElementById("thirdoption").appendChild(pricediv);
  document.getElementById("quantity").addEventListener("change",prepricechange);
  if(button == "msell"){
    activateMarketSell();
  }else{
    activateSetPrice();
  }
}
function activateMarketSell(){
  var mprice = document.createElement("h4");
  /*
  Last price sold for
  Lowest price listed
  Pick whichever is lower then deduce 1%, unless they lose money on the ticket
  Update price every action
  */
  callPHP();
  var compiledPrices = "";
  if(isGA == 1){
    compiledPrices = "$";
    compiledPrices = compiledPrices.concat(marketprices);
    compiledPrices = compiledPrices.concat("per ticket");
  }else if(isGA == 2){
    for(var i = 0; i < quantity; i++){
      compiledPrices = compiledPrices.concat("$");
      compiledPrices = compiledPrices.concat(marketprice[i]);
      compiledPrices = compiledPrices.concat("for ticket " + i);
      compiledPrices = compiledPrices.concat("\n");
    }
  }
  mprice.innerHTML = compiledPrices;
  mprice.id = "mprice";
  pricediv.appendChild(mprice);
}
var priceboxes = ["","","","","","","","","",""];
function activateSetPrice(){
  for(var i = 0; i < quantity; i++){
  priceboxes[i] = document.createElement("input");
  priceboxes[i].type = "number";
  priceboxes[i].placeholder = "Ticket " + i;
  priceboxes[i].style.marginTop="20px";
  priceboxes[i].style.position = "relative";
  priceboxes[i].style.backgroundImage= "url('resources/dollars.png')";
  priceboxes[i].style.backgroundRepeat= "no-repeat";
  priceboxes[i].style.backgroundPosition="5px";
  priceboxes[i].style.padding="0px 45px 0px";
  priceboxes[i].style.width= "100px";
  priceboxes[i].style.height="50px";
  priceboxes[i].style.fontFamily="Trebuchet MS";
  priceboxes[i].style.fontSize="45px";
  priceboxes[i].addEventListener("click",clear);
  breakl[i] = document.createElement("br");
  pricediv.appendChild(priceboxes[i]);
  pricediv.appendChild(breakl[i]);
  }
}
var hasSeatClick = false;
var hasSellClick = false;
var isMarketSell = 0;
var isGA = 0;
function selectButton(button){
  if(button == "msell"){
    if(hasSellClick == true){
      removestuff(button);
    }else{
    offsetBottom(30,true);
    }
    preprice(button);
    isMarketSell = 1;
    hasSellClick = true;
  }else if(button == "ssell"){
    if(hasSellClick == true){
      removestuff(button);
    }else{
    offsetBottom(90,true);
    }
    preprice(button);
    isMarketSell = 2;
    hasSellClick = true;
  }else if(button == "ga"){
    if(hasSeatClick == true){
      removestuff(button);
    }
    hasSeatClick = true;
    isGA = 1;
  }else if(button == "other"){
    if(hasSeatClick == true){
      removestuff(button);
    }else{
    offsetBottom(90,true);
    }
    detectseats();
    hasSeatClick = true;
    isGA = 2;
  }
  document.getElementById(button).style.backgroundColor = "rgb(130, 0, 0)";
}

function offsetBottom(offsetAmount,justAdd){
  var offset = 0;
  if(justAdd == true){
    offset = quantity * offsetAmount + currentOffset;
  }else{
    offset = currentOffset + ((quantity - oldQuantity) * offsetAmount);
  }
  oldQuantity = quantity;
  currentOffset = offset;
  offset = "" + offset + "px";
  var totaloff = "" + offset + 100 + "px";
  document.getElementById("bottom").style.marginTop = offset;
  body.style.height = totaloff;
}
function removestuff(button){
  if(button == "msell"){
    button = "ssell";
    for(var i = 0; i < quantity; i++){
    pricediv.removeChild(priceboxes[i]);
    }
    document.getElementById("thirdoption").removeChild(pricediv);
  }else if(button == "ssell"){
    button = "msell";
    pricediv.removeChild(document.getElementById("mprice"));
    document.getElementById("thirdoption").removeChild(pricediv);
  }else if(button == "ga"){
    button = "other";
    clearseats();
  }else if(button == "other"){
    button = "ga";
  }
  document.getElementById(button).style.backgroundColor = "brown";
}

function callPHP(){
  var preurl = "PHP/sellserver.php?it=";
  var url = preurl.concat(iteration);
  url = url.concat("&ed=");
  var geteid = location.search;
  geteid = geteid.slice(4,geteid.length);
  url = url.concat(geteid);
  url = url.concat("&type=");
  if(iteration == 2){
    url = url.concat(setSeatURL(url,quantity));
  }
  oreq.open("GET",url,true);
  oreq.send();
}
function setSeatURL(url,quantity){
  if(isGA == 0){
    break;
  }else if(isGA == 1){
    url = url.concat("GA;");
    for(var i = 0; i < quantity; i++){
      url = url.concat("GA");
      url = url.concat(",");
    }
  }else{
    url = url.concat("SET;");
    for(i = 0; i < quantity; i++){
      url = url.concat(section[i].value);
      url = url.concat(row[i].value);
      url = url.concat(seat[i].value);
      url = url.concat(",");
    }
  }
  return url;
}
function postorder(){
  var hasSubmitted = false;
  var sendprice;
  var seattype = "&seattype=";
  if(isMarketSell == 0){
    break;
  }
  if(isMarketSell == 1){
    if(isGA == 1){
    sendprice = marketprices;
    }else if(isGA == 2){
    for(var i = 0; i < quantity; i++){
    sendprice = sendprice.concat(marketprice[i]);
    sendprice = sendprice.concat(",");
    }
    }
  }else{
    for(var i = 0; i < quantity; i++){
    sendprice = sendprice.concat(priceboxes[i].value);
    sendprice = sendprice.concat(",");
    }
  }
  sendprice = sendprice.concat(";");
  seattype = seattype.concat(setSeatURL(url,quantity));
  seattype = seattype.concat(";");
  var eventid = location.search;
  eventid = eventid.slice(4,geteid.length);
  eventid = eventid.concat(";");
  var preurl = "PHP/sellserver.php?it=3&";
  var vpre = ['&sendprice=','&quantity=','eventid='];
  var url = preurl.concat(vpre[4].concat(eventid.concat(vpre[0].concat(sendprice.concat(vpre[1].concat(quantity.concat(";".concat(seattype))))))));
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
