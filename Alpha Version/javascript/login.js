function load(){
document.getElementById("about").addEventListener("click", goAbout);
document.getElementById("selltix").addEventListener("click", goSellTix);
document.getElementById("faq").addEventListener("click", goFAQ);
document.getElementById("contact").addEventListener("click",goContact);
document.getElementById("logobox").addEventListener("click", goHome);
}

function goAbout(){
  location.href = "about.html";
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
  location.href = "index.php";
}
function login(){
  var conn = new XMLHttpRequest();
  conn.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      var response = this.responseText;
      if(this.responseText == "success"){

      }else{

      }
    }
  }
  var preurl = "PHP/loginprocess.php?=";
  conn.open("GET",,true);
  conn.send();
}
function register(){
  location.href = "register.php"
}
