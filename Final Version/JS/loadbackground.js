if(document.currentScript.getAttribute("param") == "body"){
  document.body.style.backgroundImage = "url('https://s3.us-east-2.amazonaws.com/csrfresources" + location.pathname + ".jpg')";
}else if(location.pathname == "/"){
  document.getElementById("content").style.backgroundImage = "url('https://s3.us-east-2.amazonaws.com/csrfresources/index.jpg')";
}else{
  document.getElementById("content").style.backgroundImage = "url('https://s3.us-east-2.amazonaws.com/csrfresources" + location.pathname + ".jpg')";
}
