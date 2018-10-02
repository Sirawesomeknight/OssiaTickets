function load(){
  if(document.getElementById("tprice").innerHTML == ""){
    alert("Unfortunately, an error occurred. Please try again or contact Ossia support!");
    location.href = "index";
  }
  if(isLoggedIn == false){
    document.getElementById("whichMethod").parentElement.style.display = "none";
    document.getElementById("submitorder").style.display = "none";
    var labls = document.getElementsByClassName("useraddresslabel");
    for(i = 0; i < labls.length; i++){
      labls[i].style.display = "none";
    }
    getShipping();
  }else{
    document.getElementById("payment-form").style.display = "none";
    document.getElementById("submitorder").addEventListener("click",function(){
      // Send the token to your server
      var shippingCondition = 2;
      if(window.getComputedStyle(document.getElementById("requestShipping")).getPropertyValue("display") != "none"){
        document.getElementById("shippingOption").value = document.getElementById("shipping").value;
        shippingCondition = ((document.getElementById("shippingAddress").value != "" || document.getElementById("shipping").value == "wcall") && document.getElementById("shippingOption").value != "");
      }
      var infoCondition = 2;
      if(isLoggedIn == false){
        document.getElementById("stripeShippingName").value = document.getElementById("fullnameu").value;
        document.getElementById("stripeEmail").value = document.getElementById("emailu").value;
        infoCondition = (document.getElementById("fullnameu").value != "" && document.getElementById("emailu").value != "");
      }
      if(infoCondition != 0 && shippingCondition != 0){
        document.getElementById("buyform").submit();
      }else{
        alert("You must complete all fields to continue");
      }
    });
    getUserShipping();
    document.getElementById("whichMethod").parentElement.addEventListener("click",function(){
      if(document.getElementById("whichMethod").value == "chpayments"){
        document.getElementById("submitorder").style.display = "none";
        document.getElementById("payment-form").style.display = "block";
      }else{
        document.getElementById("submitorder").style.display = "block";
        document.getElementById("payment-form").style.display = "none";
      }
    });
  }
  var states = ["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi",
  "Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Carolina","North Dakota","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","Tennessee","Texas","Utah","Vermont","Virginia","Washington",
  "Washington D.C","West Virginia","Wisconsin","Wyoming"];
  for(i = 0; i < 50; i++){
  var aState = document.createElement("option");
  aState.value = states[i];
  aState.innerHTML = states[i];
  document.getElementById("state").appendChild(aState);
  }
  document.getElementById("validateaddr").addEventListener("click",validateAddr);
  document.getElementById("shippingButton").addEventListener("click",openShippingWindow);
  textinput();

    document.getElementById("shipping").parentElement.addEventListener("click",function(event){
      var displaytype = "block";
      if(document.getElementById("shipping").value == "wcall"){
        displaytype = "none";
      }
      document.getElementById("shippingButton").style.display = displaytype;
      if(isLoggedIn == true){
        var adlabls = document.getElementsByClassName("useraddresslabel");
        for(i = 0; i < adlabls.length; i++){
          adlabls[i].style.display = displaytype;
        }
      }
      document.getElementById("useraddress").style.display = displaytype;
      getShippingPricing(document.getElementById("shipping").value);
    });
}

function openShippingWindow(){
  document.getElementById("overlaylayer").style.display = "flex";
}

function validateAddr(){
  var shippingobj = {line1: document.getElementById("line1").value,line2: document.getElementById("line2").value,city: document.getElementById("city").value,state: document.getElementById("state").value,postal_code: document.getElementById("zipcode").value};
  document.getElementById("useraddress").innerHTML = createAddress(JSON.stringify(shippingobj));
  document.getElementById("overlaylayer").style.display = "none";
}

function loadinteraction(){
  var x, i, j, selElmnt, a, b, c;
  /*look for any elements with the class "custom-select":*/
  x = document.getElementsByClassName("custom-select");
  for(i = 0; i < x.length; i++){
    selElmnt = x[i].getElementsByTagName("select")[0];
    //updateDropInputs(selElmnt);
    /*for each element, create a new DIV that will act as the selected item:*/
    a = document.createElement("DIV");
    a.setAttribute("class", "select-selected");
    a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
    x[i].appendChild(a);
    /*for each element, create a new DIV that will contain the option list:*/
    b = document.createElement("DIV");
    b.setAttribute("class", "select-items select-hide");
    for (j = 1; j < selElmnt.length; j++) {
      /*for each option in the original select element,
      create a new DIV that will act as an option item:*/
      c = document.createElement("DIV");
      c.innerHTML = selElmnt.options[j].innerHTML;
      c.addEventListener("click", function(e) {
        /*when an item is clicked, update the original select box,
        and the selected item:*/
        var y, i, k, s, h;
        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        h = this.parentNode.previousSibling;
        for (i = 0; i < s.length; i++) {
          if (s.options[i].innerHTML == this.innerHTML) {
            s.selectedIndex = i;
            h.innerHTML = this.innerHTML;
            y = this.parentNode.getElementsByClassName("same-as-selected");
            for (k = 0; k < y.length; k++) {
              y[k].removeAttribute("class");
            }
            this.setAttribute("class", "same-as-selected");
            break;
          }
        }
        h.click();
      });
      b.appendChild(c);
    }
    x[i].appendChild(b);
    a.addEventListener("click", function(e) {
      /*when the select box is clicked, close any other select boxes,
      and open/close the current select box:*/
      e.stopPropagation();
      closeAllSelect(this);
      this.nextSibling.classList.toggle("select-hide");
      this.classList.toggle("select-arrow-active");
  });
  }
}

function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  for (i = 0; i < y.length; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < x.length; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}

function textinput(){
  (function() {
    // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
    if (!String.prototype.trim) {
      (function() {
        // Make sure we trim BOM and NBSP
        var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
        String.prototype.trim = function() {
          return this.replace(rtrim, '');
        };
      })();
    }

    [].slice.call( document.querySelectorAll( 'input.input_field' ) ).forEach( function( inputEl ) {
      // in case the input is already filled..
      if( inputEl.value.trim() !== '' ) {
        classie.add( inputEl.parentNode, 'input--filled' );
      }

      // events:
      inputEl.addEventListener( 'focus', onInputFocus );
      inputEl.addEventListener( 'blur', onInputBlur );
    } );

    function onInputFocus( ev ) {
      classie.add( ev.target.parentNode, 'input--filled' );
    }

    function onInputBlur( ev ) {
      if( ev.target.value.trim() === '' ) {
        classie.remove( ev.target.parentNode, 'input--filled' );
      }
    }
  })();
}

function zipCheck(event){
  return (allowNumbersOnly(event) == true && event.target.value.length <= 5);
}

function getUserShipping(){
  var conn = new XMLHttpRequest();
  conn.onreadystatechange = function(){
    if(this.status == 200 && this.readyState == 4){
      var response = this.responseText;
      if(response != 0){
      var usrshipping = JSON.parse(response);
        document.getElementById("addressdiv").display = "flex";
        if(usrshipping.getAddress != "none"){
          document.getElementById("useraddress").innerHTML = createAddress(usrshipping.getAddress);
        }else{
          document.getElementById("useraddresslabel").style.display = "none";
        }
        if(usrshipping.getCards != 0){
          document.getElementById("whichMethod").innerHTML += usrshipping.getCards;
        }else{
          document.getElementById("payment-form").style.display = "flex";
        }
      }
      getShipping();
    }
  }
  conn.open("POST","../PHP/addressCard",true);
  conn.send();
}

function allowNumbersOnly(event){
  var charCode = event.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)){
    return false;
  }else{
    return true;
  }
}

function createAddress(address){
  if(address != ""){
  var addrobj = JSON.parse(address);
  document.getElementById("shippingAddress").value = address;
  var addrstring = addrobj.line1 + "<br>";
  if(addrobj.line2 != ""){
    addrstring = addrstring + addrobj.line2 + "<br>";
  }
  addrstring = addrstring + addrobj.city + ", " + addrobj.state + "<br>";
  addrstring = addrstring + addrobj.postal_code;
  return addrstring;
}else{
  return "No Address On File";
}
}

function getShipping(){
  var pickupXML = new XMLHttpRequest();
  pickupXML.onreadystatechange = function(){
    if(this.status == 200 && this.readyState == 4){
      var response = this.responseText;
      if(response != "none"){
        var getShipInfo = JSON.parse(response);
        if(getShipInfo.shiptype != "none" && getShipInfo.shiptype != ""){
          document.getElementById("shipping").innerHTML += getShipInfo.shiptype;
        }
        if(getShipInfo.canPickup != ""){
          document.getElementById("shipping").innerHTML += getShipInfo.canPickup;
        }else{
          document.getElementById("shipping").children[0].selected = 0;
          document.getElementById("shipping").children[1].selected = 1;
        }
      }else{
        alert("Failed to get shipping info");
        location.href = "index";
      }
      loadinteraction();
      getShippingPricing(document.getElementById("shipping").value);
    }
  }
  pickupXML.open("POST","../PHP/getShipping",true);
  pickupXML.send();
}

function getShippingPricing(shipcode){
  var price = 0;
  switch(shipcode){
    case "expr":
      price = 24.99;
    break;
    case "prio":
      price = 9.99;
    break;
    case "reg":
      price = 2.50;
    break;
    case "wcall":
      price = 0;
    break;
    default:
      price = 0;
    break;
  }
  document.getElementById("shippingfee").innerHTML = "$" + parseFloat(price).toFixed(2);
  calculateTotal(parseFloat(price));
}

function calculateTotal(shippingprice){
  var tickettotal = parseFloat(document.getElementById("quant").innerHTML) * extractNumber(document.getElementById("tprice").innerHTML);
  var discounts = extractNumber(document.getElementById("discnt").innerHTML);
  document.getElementById("totalpay").innerHTML = "$" + parseFloat(tickettotal + discounts + shippingprice).toFixed(2);
}

function extractNumber(element){
  return parseFloat(parseFloat(element.substring(1,element.length)));
}
