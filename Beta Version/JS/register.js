function load(){
  document.getElementById("stix").addEventListener("click",selltix);
  document.getElementById("abt").addEventListener("click",faq);
  document.getElementById("ctus").addEventListener("click",contactus);
  document.getElementById("logo").addEventListener("click",home);
  document.getElementById("registerbutt").addEventListener("click",signin);
}
function signin(){
  location.href="signupregister.html";
}
function selltix(){
  location.href="selltix.html";
}
function faq(){
  location.href= "faq.html";
}
function contactus(){
  location.href="contactus.html";
}
function home(){
  location.href = "index.html";
}
