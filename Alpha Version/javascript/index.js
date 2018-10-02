/*
Add Searchbar
Fix Session_start
Add Login page
Add Register page

*/
function load(){
document.getElementById("about").addEventListener("click", goAbout);
document.getElementById("selltix").addEventListener("click", goSellTix);
document.getElementById("faq").addEventListener("click", goFAQ);
document.getElementById("contact").addEventListener("click",goContact);
document.getElementById("logobox").addEventListener("click", goHome);
document.getElementById("searchbar").addEventListener("click", clear);
}

function goAbout(){
  location.href = "about.html";
}
function goBuyTix(){
  location.href = "buytix.html";
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
function clear(){
  document.getElementById("searchbar").value = "";
}
function search(){
  if(event.key == "Enter"){
    var searchterms = document.getElementById("searchbar").value;
        var preurl = "search.php?searchterms=";
        var url = preurl.concat(searchterms);
        location.href = url;
  }
}
function goBuyTix(){
  location.href = "venue.php";
}
