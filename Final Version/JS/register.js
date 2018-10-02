function load(){
  document.getElementById("pass").addEventListener("focus",function(){
    document.getElementById("passpointers").style.display = "block";
  });
  document.getElementById("pass").addEventListener("focusout",function(){
    document.getElementById("passpointers").style.display = "none";
  });
  //necessary if shipping is collected
  // document.getElementById("shippingButton").addEventListener("click",openShippingWindow);
  document.getElementById("registbutton").addEventListener("click",checkPass);

  var states = ["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi",
  "Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Carolina","North Dakota","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","Tennessee","Texas","Utah","Vermont","Virginia","Washington",
  "Washington D.C","West Virginia","Wisconsin","Wyoming"];
  for(i = 0; i < 50; i++){
  var aState = document.createElement("option");
  aState.value = states[i];
  aState.innerHTML = states[i];
  document.getElementById("state").appendChild(aState);
  }
  loadinteraction();
  textinput();
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

function home(){
  location.href = "index";
}
function faq(){
  location.href = "faq";
}
function login(){
  location.href = "login";
}
function contactus(){
  location.href = "contactus";
}

function checkPass(){
  var pass = document.getElementById("pass").value;
  var acceptablePass = true;
  if(document.getElementById("confpass").value != "" && pass != "" && pass != document.getElementById("confpass").value){
    acceptablePass = false;
    alert("Passwords do not match");
  }
  if(pass.length <= 7){
    acceptablePass = false;
    alert("Password needs to be greater than 7 characters!");
  }
  var cappatt = new RegExp("[A-Z]");
  var numpatt = new RegExp("[0-9]");
  if(!cappatt.test(pass) || !numpatt.test(pass)){
    acceptablePass = false;
    alert("Password requires 1 capital letter and 1 number");
  }
  if(acceptablePass == true){
    document.getElementById("registform").submit();
  }
}
/*
function openShippingWindow(){
  document.getElementById("overlaylayer").style.display = "flex";
}

function validateAddr(){
  var shippingobj = {line1: document.getElementById("line1").value,line2: document.getElementById("line2").value,city: document.getElementById("city").value,state: document.getElementById("state").value,postal_code: document.getElementById("zipcode").value};
  document.getElementById("shippingAddress").value = JSON.stringify(shippingobj);
  document.getElementById("overlaylayer").style.display = "none";
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
*/
