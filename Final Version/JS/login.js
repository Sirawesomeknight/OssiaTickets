function load(){
  document.getElementById("loginpass").addEventListener("keyup",function(event){
    event.preventDefault();
    if (event.keyCode === 13) {
        loginsys();
    }
  });
}
function loginsys(){
  var conn = new XMLHttpRequest();
  conn.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
    var response = this.responseText;
    if(response == "empty"){
      alert("Please enter a valid username and password");
    }else if(response == "invalid"){
      alert("Email and password do not match");
    }else if(response == "notconfirmed"){
      alert("Account not confirmed! Please check your inbox. (Look in spam too)");
    }else if(response == "noexist"){
      alert("Account does not exist");
    }else if(response == "invalidformat"){
      alert("Invalid email format");
    }else if(response == "toomany"){
      alert("Too many login attempts, please try again later");
    }else{
      location.href = "profile";
    }
  }else if(this.status == 500){
    alert("PHP error");
  }
  }
  var params = "username=" + document.getElementById("username").value + "&password=" + document.getElementById("loginpass").value;
  conn.open("POST","../PHP/login",true);
  conn.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  conn.send(params);
}
