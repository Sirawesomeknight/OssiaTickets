function load(){
document.getElementById("about").addEventListener("click", goAbout);
document.getElementById("selltix").addEventListener("click", goSellTix);
document.getElementById("faq").addEventListener("click", goFAQ);
document.getElementById("contact").addEventListener("click",goContact);
document.getElementById("logobox").addEventListener("click", goHome);
search();
}
function search(){
var query = window.location.search;
var processing = new XMLHttpRequest();
processing.onreadystatechange = function(){
  if(this.readyState == 4 && this.status == 200){
    document.getElementById("searchlist").innerHTML = this.responseText;
  }
}
var preurl = "PHP/search.php";
var url = preurl.concat(query);
processing.open("GET",url,true);
processing.send();
}
function goAbout(){
  location.href = "about.html";
}
function goBuyTix(){
  location.href = "venue.php";
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
  location.href = "index.html";
}
function goToEvent(id){
  var prestring = "venue.php?id=";
  var url = prestring.concat(id);
  location.href = url;
}
