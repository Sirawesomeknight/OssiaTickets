var isLoggedIn = false;
function load(){
  document.getElementById("contactus").addEventListener("click",contactus);
  document.getElementById("logo").addEventListener("click",home);
  document.getElementById("faq").addEventListener("click",faq);
  document.getElementById("login").addEventListener("click",login);
  document.getElementById("totaltickets").innerHTML = "Not Logged In";
  document.getElementById("referralmoney").innerHTML = "";
  checklogin();
  var getEventData = new XMLHttpRequest();
  getEventData.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      var response = JSON.parse(this.responseText);
      document.body.style.backgroundImage = "url(" + "https://s3.us-east-2.amazonaws.com/csrfresources/" + response.backgroundimage + ")";
      if(response.usertotaltickets.length != 0 && response.userreferralmoney.length != 0){
      document.getElementById("totaltickets").innerHTML = response.usertotaltickets + " tickets";
      document.getElementById("referralmoney").innerHTML = "$" + response.userreferralmoney + " from referral rewards";
      }
    }
  }
  var params = "id=" + document.getElementById("eventid").value + "&loggedin=" + isLoggedIn + "&eventloc=" + document.getElementById("eventloc").innerHTML;
  getEventData.open("POST","../PHP/loadinfo",true);
  getEventData.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  getEventData.send(params);
}
function contactus(){
  location.href = "contactus";
}
function home(){
  location.href = "index";
}
function faq(){
  location.href = "faq";
}
function profile(){
  location.href = "profile";
}
function login(){
  location.href = "login";
}
function logout(){
  var logoutreq = new XMLHttpRequest();
  logoutreq.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      this.responseText;
      isLoggedIn = false;
      document.getElementById("totaltickets").innerHTML = "Not Logged In";
      document.getElementById("referralmoney").innerHTML = "";
      location.href = "index";
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
