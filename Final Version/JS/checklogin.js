var isLoggedIn = false;
var checksesh = new XMLHttpRequest();
checksesh.onreadystatechange = function(){
  if(this.readyState == 4 && this.status == 200){
    var response = this.responseText;
    if(response == true){
      document.getElementById("login").innerHTML = "Profile";
      document.getElementById("login").href = "profile";
      isLoggedIn = true;
    }
  }
}
checksesh.open("GET","../PHP/checklogin",true);
checksesh.send();
