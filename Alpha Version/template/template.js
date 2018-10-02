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
function goBuyTix(){
  location.href = "buytix.html";
}
function goSellTix(){
  location.href = "selltix.html";
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
