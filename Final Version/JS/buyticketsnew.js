var whichStream = "";
var currentSeat = -3;
function load(){
  if(document.getElementById("eventid").value == ""){
    location.href = "index";
  }
  document.getElementById("Quantity").style.display = "flex";
    var getEventData = new XMLHttpRequest();
    getEventData.onreadystatechange = function(){
      if(this.status == 200 && this.readyState == 4){
        var response = this.responseText;
        if(response != ""){
          var eventdata = JSON.parse(response);
          document.getElementById("eventnamelabel").innerHTML = eventdata.eventname;
          document.getElementById("eventname").value = eventdata.eventname;
          document.getElementById("eventloclabel").innerHTML = eventdata.eventloc;
          document.getElementById("eventloc").value = eventdata.eventloc;
          document.getElementById("eventdatelabel").innerHTML = eventdata.eventdate;
          document.getElementById("eventdate").value = eventdata.eventdate;
          document.getElementById("rigatone").src = "https://s3.us-east-2.amazonaws.com/csrfresources/" + eventdata.eventprof;
          document.getElementById("dtype").innerHTML += eventdata.delivery;
          document.getElementById("delivery").value = eventdata.delivery;
        }else{
          location.href = "index";
        }
      }
    }
  getEventData.open("POST","../PHP/getEventData",true);
  var params = "eventid=" + document.getElementById("eventid").value;
  getEventData.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  getEventData.send(params);
  posttransact(document.getElementById("transacttype").innerHTML);

  document.getElementById("submit").addEventListener("click",function(){changeBox(1);});
}

function posttransact(transactiontype){
  whichStream = transactiontype;
  document.getElementById("back").addEventListener("click",function(){
    changeBox(-1);
  });
  document.getElementById("submit").style.visibility = "visible";
  loadInputs(whichStream);
  loadinteraction();
  textinput();
}

function seatingFunction(direction){
  if(whichStream == "SELL"){
    if(currentSeat >= 0 && currentSeat < document.getElementById("quantity").value){
      document.getElementById("Seating" + currentSeat).style.display = "none";
    }
    currentSeat += direction;
    if(currentSeat < 0 || currentSeat >= document.getElementById("quantity").value){
      return false;
    }else{
      document.getElementById("Seating" + currentSeat).style.display = "flex";
      return true;
    }
  }
}

function changeBox(direction){
  if(whichStream == "SELL" && checkLogin() == false){
    alert("You must be logged in to sell a ticket!");
    location.reload();
  }
  var currentBox = document.getElementById("orderdesc").children[0].id;
  var inputinteg = checkInputIntegrity(currentBox);
  var nextCondition = ((currentBox == "REVIEWBUY" || currentBox == "REVIEWSELL") && direction == 1);
  var backCondition = (direction == -1 && currentBox == "QUANTITY");
  var seatingCondition = (seatingFunction(direction) == true && currentBox == "SEATING");
  if(inputinteg == true || direction == -1){
    if(nextCondition == false && backCondition == false && seatingCondition == false || direction == 2){
      fadeout();
      setTimeout(function(){
        if(currentBox != "SEATING"){
          document.getElementById(capitalizeFirstLetter(currentBox)).style.display = "none";
        }
        retrieveHTML(direction,currentBox);
      }, 1200);
    }else if(backCondition == true){
      location.href = "index";
    }else if(nextCondition == true){
      document.getElementById("orderinfo").submit();
    }
  }else if(inputinteg == false){
    alert("You must fill in the required information!");
  }else if(inputinteg == 2){
    alert("Your max price must be higher than you initial price!");
  }else{
    alert("You cannot put in a price higher than $10,000!");
  }
}

function loadInputs(transaction){
  var conn = new XMLHttpRequest();
  conn.onreadystatechange = function(){
    if(this.status == 200 & this.readyState == 4){
      var response = this.responseText;
      var output = JSON.parse(response);
      document.getElementById("orderinfo").innerHTML += output.html;
      document.getElementById("orderinfo").action = output.action;
      /*if the user clicks anywhere outside the select box,
      then close all select boxes:*/
      document.addEventListener("click", closeAllSelect);
    }
  }
  var params = "direction=" + transaction + "&eventid=" + document.getElementById("eventid").value;
  conn.open("POST","../PHP/boxload",false);
  conn.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  conn.send(params);
}

function validPrice(event){
  return (allowNumbersOnly(event) == true && event.target.value.split(".")[1].length <= 1);
}

function makePrice(event){
  if(event.target.value != ""){
    event.target.value = parseFloat(event.target.value).toFixed(2);
  }
}

function retrieveHTML(direction,currentBox){
  var params = "direction=" + direction + "&stream=" + whichStream + "&currentBox=" + currentBox;
  var getHTML = new XMLHttpRequest();
  getHTML.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      var response = JSON.parse(this.responseText);
      document.getElementById("orderdesc").innerHTML = response.html;
      progress(response.progress);
      currentBox = document.getElementById("orderdesc").children[0].id;
      if(currentBox == "PRICE"){
        getEventInfo();
        seatingmode();
      }else{
        exitseatingmode();
      }
      var shouldSkip = false;
      switch(currentBox){
        case "MAXPRICE":
          if(document.getElementById("flexprice").value == "No"){
            document.getElementById("maxprice").value = document.getElementById("price").value;
            shouldSkip = true;
          }
        break;
        case "MERCH":
          if(document.getElementById("delivery").value != "Shipping"){
            shouldSkip = true;
          }
        case "PRICE":
        if(whichStream == "SELL"){
          if(document.getElementById("saletype").value == "Sell Guaranteed"){
            document.getElementById("price").value = 0;
            shouldSkip = true;
          }
        }        break;
        case "SELLTOGETHER":
        if(document.getElementById("quantity").value == 1){
          document.getElementById("selltogether").value = "Yes";
          shouldSkip = true;
        }
        break;
      }
      if(currentBox != "SEATING" && shouldSkip == false){
        document.getElementById(capitalizeFirstLetter(currentBox)).style.display = "flex";
      }else if(currentBox == "SEATING"){
        seatingFunction(0);
      }
      if(shouldSkip == false){
        fadein();
      }else{
        retrieveHTML(direction,currentBox);
      }
    }
  }
  if((currentBox == "SELLTOGETHER" || currentBox == "MAXPRICE") && direction == 1){
    if(currentBox == "SELLTOGETHER"){
      reviewsell();
    }else{
      reviewbuy();
    }
  }
  params = params + "&whichMode=" + whichStream;
  getHTML.open("POST","../PHP/boxload",true);
  getHTML.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  getHTML.send(params);
}
function checkLogin(){
  var checksesh = new XMLHttpRequest();
  var response = "";
  checksesh.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      response = this.responseText;
    }
  }
  checksesh.open("GET","../PHP/checklogin",false);
  checksesh.send();
  return response;
}

function progress(currentBox){
  var total = 0;
  if(whichStream == "BUY"){
    total = 8;
  }else{
    total = 7;
  }
  document.getElementById("progress").style.width = parseFloat(currentBox / total) * 100 + "%";
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

function seatingmode(){
  document.getElementById("content2").style.justifyContent = "space-around";
  document.getElementById("lastprices").style.display = "flex";
  //document.getElementById("seatingmap").style.display = "flex";
  document.getElementById("mainbox").style.width = "20vw";
  document.getElementById("lastprices").style.width = "20vw";
  //document.getElementById("seatingmap").style.width = "40vw";
}

function exitseatingmode(){
  document.getElementById("content2").style.justifyContent = "center";
  document.getElementById("lastprices").style.display = "none";
  //document.getElementById("seatingmap").style.display = "none";
  document.getElementById("mainbox").style.width = "30vw";
}

function fadein(){
  // setTimeout(function(){
  document.getElementById("mainbox").className = "infobox";
  document.getElementById("mainbox").className = "infobox animate-in";
  document.getElementById("lastprices").className = "infobox";
  document.getElementById("lastprices").className = "infobox animate-in";
  //document.getElementById("seatingmap").className = "infobox";
  //document.getElementById("seatingmap").className = "infobox animate-in";
  // }, 1200);
  setTimeout(function(){
    document.getElementById("mainbox").className = "infobox";
    document.getElementById("lastprices").className = "infobox";
    //document.getElementById("seatingmap").className = "infobox";
  }, 1200);
}

function fadeout(){
  // setTimeout(function(){
  document.getElementById("mainbox").className = "infobox animate-out";
  document.getElementById("lastprices").className = "infobox animate-out";
  //document.getElementById("seatingmap").className = "infobox animate-out";
  // }, 1200);
}

function getEventInfo(){
  var getTheEventInfo = new XMLHttpRequest();
  getTheEventInfo.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      var response = this.responseText;
      response = JSON.parse(response);
      if(response.venueimage != ""){
        document.getElementById("venueimage").src = "https://s3.us-east-2.amazonaws.com/csrfresources/" + response.venueimage;
      }
      if(response.pricetable != ""){
        document.getElementById("pricetable").innerHTML = response.pricetable;
      }
    }
  }
  var params = "id=" + document.getElementById("eventid").value + "&ticketclass=" + document.getElementById("ticketclass").value;
  getTheEventInfo.open("POST","../PHP/orders",true);
  getTheEventInfo.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  getTheEventInfo.send(params);
}

function checkInputIntegrity(currentBox){
  var priceinput = document.getElementById("price");
  switch(currentBox){
    case "PRICE":
    var saletype = "";
    if(whichStream == "SELL"){
      saletype = document.getElementById("saletype").value;
    }else if(parseFloat(priceinput.value) > 10000){
      return 3;
    }
    return (parseFloat(priceinput.value) > 0 || (whichStream == "SELL" && saletype == "Sell Guaranteed"));
    break;
    case "MAXPRICE":
    var maxprice = parseFloat(document.getElementById("maxprice").value);
    var listmax = document.getElementById("flexprice").value;
    if(maxprice <= parseFloat(priceinput.value)){
      return 2;
    }else if(maxprice > 10000){
      return 3;
    }
    return (maxprice > parseFloat(priceinput.value) && listmax == "Yes");
    break;
    case "SEATING":
    var sections = document.getElementsByClassName("section[]");
    var rows = document.getElementsByClassName("row[]");
    var seats = document.getElementsByClassName("seatnum[]");
    var proofbutton = document.getElementsByClassName("proofbutton");
    return (sections[currentSeat].value != "" && rows[currentSeat].value != "" && seats[currentSeat].value != "" && proofbutton[currentSeat].files.length > 0);
    break;
    case "QUANTITY":
    case "REVIEWSELL":
    case "REVIEWBUY":
    case "MERCH":
    return true;
    break;
    default:
    return (document.getElementById(currentBox.toLowerCase()).value != "");
    break;
  }
}

function capitalizeFirstLetter(string) {
  string = string.toLowerCase();
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function reviewsell(){
  document.getElementById("tquant").innerHTML = "Total Quantity: " + document.getElementById("quantity").value;
  document.getElementById("eventloclabel").style.display = "block";
  document.getElementById("eventdatelabel").style.display = "block";
  if(document.getElementById("price").value == 0){
    document.getElementById("ppt").innerHTML = "Price Per Ticket: Sell Guaranteed";
  }else{
    document.getElementById("ppt").innerHTML = "Price Per Ticket: $" + parseFloat(document.getElementById("price").value).toFixed(2);
  }
  document.getElementById("Reviewsell").style.display = "flex";
}

function reviewbuy(){
  document.getElementById("btquant").innerHTML = "Total Quantity: " + document.getElementById("quantity").value;
  document.getElementById("bppt").innerHTML = "Price Per Ticket: $" + parseFloat(document.getElementById("price").value).toFixed(2);
  document.getElementById("mmt").innerHTML = "Listed Max Price: " + ("$" + parseFloat(document.getElementById("maxprice").value).toFixed(2));
  document.getElementById("eventloclabel").style.display = "block";
  document.getElementById("eventdatelabel").style.display = "block";
  document.getElementById("Reviewbuy").style.display = "flex";
}

function allowNumbersOnly(event){
  var charCode = event.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46){
    return false;
  }else{
    if(charCode == 46){
      return (event.target.value.includes(".") == false);
    }
    return true;
  }
}
