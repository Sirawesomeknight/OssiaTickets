function load(){
  updatetable();
  document.getElementById("overlay").addEventListener("click",function(event){
    if(event.target.id == "overlay"){
    document.getElementById("changeemail").style.display = "none";
    // document.getElementById("addpaymentmethod").style.display = "none";
    document.getElementById("changepassword").style.display = "none";
    document.getElementById("overlay").style.display = "none";
    }
  });
  var states = ["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi",
  "Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Carolina","North Dakota","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","Tennessee","Texas","Utah","Vermont","Virginia","Washington",
  "Washington D.C","West Virginia","Wisconsin","Wyoming"];
  for(i = 0; i < 50; i++){
  var aState = document.createElement("option");
  aState.value = states[i];
  aState.innerHTML = states[i];
  document.getElementById("state").appendChild(aState);
  }
  textinput();
  document.getElementById("deletepay").addEventListener("click",function(){
    removePaymentMethod();
  });
  document.getElementById("paymentmethods").parentElement.addEventListener("click",function(){
    document.getElementById("deletepay").style.display = "block";
  });
}

function changeemail(){
  document.getElementById("overlay").style.display = "flex";
  document.getElementById("changeemail").style.display = "flex";
}

function changepassword(){
  document.getElementById("overlay").style.display = "flex";
  document.getElementById("changepassword").style.display = "flex";
}

// function addpaymentmethod(){
//   document.getElementById("overlay").style.display = "flex";
//   document.getElementById("addpaymentmethod").style.display = "flex";
// }

function cancelorder(event){
  var cancelconn = new XMLHttpRequest();
  cancelconn.onreadystatechange = function(){
    if(cancelconn.readyState == 4 && cancelconn.status == 200){
      var response = this.responseText;
      document.getElementById("loadoverlay").style.display = "none";
      document.body.style.cursor = "default";
      if(response == 0){
        alert("Error");
      }else{
        alert("Order canceled");
        location.reload();
      }
    }
  }
  document.getElementById("loadoverlay").style.display = "flex";
  document.body.style.cursor = "progress";
  var params = encodeURI("cancelorder=" + event.target.value);
  cancelconn.open("POST","../PHP/cancelorder",true);
  cancelconn.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  cancelconn.send(params);
}

function validPassword(){
 if(document.getElementById("passn").value == document.getElementById("passn1").value && document.getElementById("passn").value != ""){
   if(document.getElementById("passn").value.length >= 7 && /[A-Z]/.test(document.getElementById("passn").value) && /[1-9]/.test(document.getElementById("passn").value)){
     return true;
   }else{
     alert("Passwords must have more than 7 characters, contain at least 1 capital letter, and have at least 1 number.");
     return false;
   }
 }else{
   alert("Passwords do not match!");
   return false;
 }
}

function submitpass(){
  if(validPassword()){
    var conn = new XMLHttpRequest();
    conn.onreadystatechange = function(){
      if(conn.readyState == 4 && conn.status == 200){
        var response = this.responseText;
        if(response == 0){
          alert("Error");
        }else if(response == 3){
          alert("Incorrect Password!");
        }else if(response == 4){
          alert("Password Requirements Not Met");
        }else{
          alert("Password Updated");
          location.reload();
        }
      }
    }
    var params = "npass=" + document.getElementById("passn").value + "&cpass=" + document.getElementById("cpass").value;
    conn.open("POST","../PHP/changeinfo",true);
    conn.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    conn.send(params);
  }
}

function submitemail(){
  var conn = new XMLHttpRequest();
  conn.onreadystatechange = function(){
    if(conn.readyState == 4 && conn.status == 200){
      var response = this.responseText;
      if(response == 0){
        alert("Error");
      }else if(response == 3){
        alert("Incorrect Password!");
      }else if(response == 5){
        alert("New Email is Invalid!");
      }else{
        alert("Email Updated");
        location.reload();
      }
    }
  }
  var params = "nemail=" + document.getElementById("nemail").value + "&cpass=" + document.getElementById("cpassemail").value;
  conn.open("POST","../PHP/changeinfo",true);
  conn.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  conn.send(params);
}

function updatetable(){
  var getInfo = new XMLHttpRequest();
  getInfo.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      var data = JSON.parse(this.responseText);
      document.getElementById("email").innerHTML = data.email;
      document.getElementById("fullname").innerHTML = data.fullname;
      var address = "";
      if(data.address == "None stored on file"){
        address = data.address;
      }else{
        address = createAddress(data.address);
      }
      document.getElementById("address").innerHTML = address;
      document.getElementById("cashowed").innerHTML = "$" + parseFloat(data.moneyowed).toFixed(2);
      //document.getElementById("rdollars").innerHTML = "$" + parseFloat(data.rdollars).toFixed(2);
      //document.getElementById("referralcode").innerHTML = data.referralcode;
      document.getElementById("paymentmethods").innerHTML += data.paymentmethods;
      document.getElementById("orderhistory").innerHTML = data.orderhistory;
      loadinteraction();
    }
  }
  getInfo.open("POST","../PHP/getProfileInfo",true);
  getInfo.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  getInfo.send();
}

function loadinteraction(){
  var x, i, j, selElmnt, a, b, c;
  /*look for any elements with the class "custom-select":*/
  x = document.getElementsByClassName("custom-select");
  for(i = 0; i < x.length; i++){
    selElmnt = x[i].getElementsByTagName("select")[0];
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

function logout(){
  var conn = new XMLHttpRequest();
  conn.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      location.href = "index";
    }
  }
  conn.open("POST","../PHP/checklogin",true);
  conn.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  conn.send("logout=1");
}

function changeaddress(){
  document.getElementById("overlay").style.display = "flex";
  document.getElementById("changeaddress").style.display = "flex";
}

function submitAddress(){
  var shippinginfo = ["line1","city","state","zipcode"];
  var canSubmit = true;
  for(i = 0; i < shippinginfo.length; i++){
    if(document.getElementById(shippinginfo[i]).value == ""){
      alert("Please fill in the required information");
      canSubmit = false;
    }
  }
  if(canSubmit == true){
  var shippingobj = {line1: document.getElementById("line1").value,line2: document.getElementById("line2").value,city: document.getElementById("city").value,state: document.getElementById("state").value,zip: document.getElementById("zipcode").value};
  var conn = new XMLHttpRequest();
  conn.onreadystatechange = function(){
    if(conn.readyState == 4 && conn.status == 200){
      var response = this.responseText;
      if(response == 0){
        alert("Error");
      }else if(response == 1){
        alert("Invalid Password");
      }else{
        alert("Address Changed");
        location.reload();
      }
    }
  }
  var params = "naddress=" + JSON.stringify(shippingobj) + "&pass=" + document.getElementById("addresspass").value;
  conn.open("POST","../PHP/changeaddress",true);
  conn.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  conn.send(params);
  }
}

function zipCheck(event){
  return (allowNumbersOnly(event) == true && event.target.value.length <= 5);
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

function removePaymentMethod(){
  var removeXML = new XMLHttpRequest();
  removeXML.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      var response = this.responseText;
      if(response == 1){
        alert("Success!");
      }else{
        alert("An Error has Occurred");
      }
    }
  }
  var params = "paymentid=" + document.getElementById("paymentmethods").value;
  removeXML.open("POST","../PHP/removePayment",true);
  removeXML.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  removeXML.send(params);
}
