function load(){
  var sellelements = document.getElementById("sellform").children;
  for(i = 0; i < sellelements.length; i++){
    if(sellelements[i].id != "Collateral" && sellelements[i].id != "recap" && sellelements.value == ""){
      alert("You have timed out");
      location.href = "index";
    }
  }
}
