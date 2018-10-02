function load(){

}

function forgotsys(){
  var email = document.getElementById("username").value;
  var forgotcom = new XMLHttpRequest();
  forgotcom.onreadystatechange = function(){
    var response = this.responseText;
      if(response == "invalid email"){
        alert("You did not input a valid email address");
      }else if(response == "email not found"){
        alert("The email you entered was not found");
      }else if(response == "success"){
        alert("Success! You should receive an email from us soon");
        location.href = "login";
      }
  }
  var params = "email=" + email;
  forgotcom.open("POST","../PHP/forgot",true);
  forgotcom.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  forgotcom.send(params);
}
