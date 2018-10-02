function load(){
  document.getElementById("logo").addEventListener("click",home);
  document.getElementById("contactus").addEventListener("click",contactus);
  document.getElementById("faq").addEventListener("click",faq);
  document.getElementById("login").addEventListener("click",login);
  checklogin();
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
function profile(){
  location.href = "profile";
}
function logout(){
  var logoutreq = new XMLHttpRequest();
  logoutreq.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      this.responseText;
    }
  }
  logoutreq.open("GET","../PHP/checklogin?shouldLogout=1",true);
  logoutreq.send();
  location.reload();
}

var user = "";
function checklogin(){
  var loginreq = new XMLHttpRequest();
  loginreq.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      var response = this.responseText;
      if(response != 0){
      document.getElementById("login").style.display = "none";
      var login = document.getElementById("loggedin");
      login.style.display = "block";
      document.getElementById("name").innerHTML = "Welcome " + response;
      login.removeEventListener("click",login);
      login.addEventListener("click",profile);
      isLoggedIn = true;
      }
    }
  }
  loginreq.open("GET","../PHP/checklogin?shouldLogoout=0",true);
  loginreq.send();
}
