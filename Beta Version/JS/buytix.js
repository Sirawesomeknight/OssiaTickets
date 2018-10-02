function load(){
  document.getElementById("stix").addEventListener("click",selltix);
  document.getElementById("abt").addEventListener("click",faq);
  document.getElementById("ctus").addEventListener("click",contactus);
  document.getElementById("logo").addEventListener("click",home);
  document.getElementById("registerbutt").addEventListener("click",signin);
  oreq = new XMLHttpRequest();
  iteration = 0;
  oreq.onreadystatechange = function() {
    if(this.readyState == 4 && this.status == 200){
      if(iteration == 0){
      document.getElementById("artisttitle").innerHTML = this.responseText;
      iteration = 1;
    }else if(iteration == 1){
      document.getElementById("loctime").innerHTML = this.responseText;
      iteration = 2;
    }else if(iteration == 2){

    }
    if(iteration != 2){
      callPHP();
    }
    }
  };
  callPHP();
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
