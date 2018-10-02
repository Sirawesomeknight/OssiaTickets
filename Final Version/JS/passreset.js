function load(){
}

function checkPass(){
  var pass = document.getElementById("npass").value;
  if(document.getElementById("cnpass").value != "" && pass != "" && pass != document.getElementById("cnpass").value){
    return false;
    alert("Passwords do not match");
  }
  if(pass.length <= 7){
    return false;
    alert("Password needs to be greater than 7 characters!");
  }
  var cappatt = new RegExp("[A-Z]");
  var numpatt = new RegExp("[0-9]");
  if(!cappatt.test(pass) || !numpatt.test(pass)){
    return false;
    alert("Password requires 1 capital letter and 1 number");
  }
  return true;
}
