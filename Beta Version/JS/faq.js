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
function sellsell(){
  location.href = "selltix2.html"
}
var openbox = 0;
function dropdown(whichQuestion){
 var answer;
 switch(whichQuestion){
   case 1:
   answer = "Children are strange creatures that should not exist ever. The world should be a strange place.";
   break;
   case 2:
   answer = "baby";
   break;
   case 3:
   answer = "baby";
   break;
   default:
   alert("error with dropdown");
   break;
 }
var question = "question";
question = question.concat(whichQuestion);
var h = document.getElementById(question).offsetHeight;
if(h == 64){
document.getElementById(question).style.WebkitAnimationName = "extendQuestion";
document.getElementById(question).style.WebkitAnimationDuration = "1s";
document.getElementById(question).style.WebkitAnimationIterationCount = "1";
document.getElementById(question).style.WebkitAnimationFillMode = "forwards";
document.body.style.height = 1040 + "px";
document.getElementById("bottom").style.marginTop = 940 + "px";
document.getElementById("content").style.height = 895 + "px";
setTimeout(function(){var nanswer = document.createElement('p'); nanswer.id = "nanswer"; nanswer.innerHTML = answer; document.getElementById(question).appendChild(nanswer);},1000);
if(openbox != 0){
  oldquestion = "question";
  oldquestion = oldquestion.concat(openbox);
  document.getElementById(oldquestion).removeChild(document.getElementById("nanswer"));
  document.getElementById(oldquestion).style.WebkitAnimationName = "reduceQuestion";
  document.getElementById(oldquestion).style.WebkitAnimationDuration = "1s";
  document.getElementById(oldquestion).style.WebkitAnimationIterationCount = "1";
  document.getElementById(oldquestion).style.WebkitAnimationFillMode = "forwards";
}
openbox = whichQuestion;
}
}
