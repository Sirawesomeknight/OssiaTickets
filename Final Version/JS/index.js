isHovering = false;
function load(){
  var basestring = "https://s3.us-east-2.amazonaws.com/csrfresources/";
  document.getElementById("searchbar").addEventListener("keyup",function(event){
    suggestions(event);
  });
  document.getElementById("searchbar").addEventListener("focusout",plain);
  document.getElementById("searchbar").addEventListener("change",checkBlank);
  getLocation();
  fadeinload();
}

function revealSearch(event){
  document.getElementById("saletype").innerHTML = event.target.id.toUpperCase();
  fadeout();
  fadein();
}

function regist(){
  location.href = "register";
}

function fadeinload(){
  document.getElementById("firsttitle").className = "animate-in"
  document.getElementById("msg").className = "animate-inpt2";
  document.getElementById("buy").className = "transtype animate-inpt3";
  document.getElementById("sell").className = "transtype animate-inpt2";
  document.getElementById("regist").className = "transtype animate-inpt3";
  setTimeout(function(){
    document.getElementById("firsttitle").style.opacity = 1;
    document.getElementById("firsttitle").className = ""
  }, 1200);
  setTimeout(function(){
    document.getElementById("msg").style.opacity = 1;
    document.getElementById("msg").className = "";
    document.getElementById("sell").style.opacity = 1;
    document.getElementById("sell").className = "transtype";
  },1700);
  setTimeout(function(){
    document.getElementById("buy").style.opacity = 1;
    document.getElementById("buy").className = "transtype";
    document.getElementById("regist").style.opacity = 1;
    document.getElementById("regist").className = "transtype";
  },2200);
}

function fadein(){
  document.getElementById("labelcontainer").style.display = "flex";
  document.getElementById("ossiatitle").className = "animate-inpt3";
  document.getElementById("searchbar").className = "animate-in";
  setTimeout(function(){
    document.getElementById("ossiatitle").className = "";
    document.getElementById("ossiatitle").style.opacity = 1;
  }, 2400);
  setTimeout(function(){
    document.getElementById("searchbar").className = "";
    document.getElementById("searchbar").style.opacity = 1;
  },1200);
}

function fadeout(){
  document.getElementById("welcomecontainer").className = "animate-out";
  document.getElementById("welcomecontainer").style.display = "none";
}

function checkBlank(){
  if(document.getElementById("searchbar").value == ""){
    document.getElementById("searchsuggestions").innerHTML = "";
    document.getElementById("searchsuggestions").style.visibility = "hidden";
  }
}
function plain(){
  if(isHovering == false){
  var searchbar = document.getElementById("searchbar");
  searchbar.style.backgroundColor = "white";
  searchbar.style.color = "black";
  document.getElementById("searchsuggestions").innerHTML = "";
  document.getElementById("searchsuggestions").style.visibility = "hidden";
  }
}

function suggestions(event){
  var conn = new XMLHttpRequest();
  conn.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      var response = this.responseText;
      if(document.getElementById("searchbar").value.length >= 2 && response != 0){
        document.getElementById("searchsuggestions").style.visibility = "visible";
        document.getElementById("searchsuggestions").innerHTML = "";
        var responseElements  = response.split("#");
        for(i = 0; i < responseElements.length - 1; i += 3){
          var bardiv = document.createElement("div");;
          var innerdiv = document.createElement("div")
          var sugimg = document.createElement("img");
          bardiv.className = "bardiv";
          bardiv.style.cursor = "pointer";
          sugimg.className = "sugimg";
          sugimg.style.borderRadius = "10px";
          sugimg.style.borderStyle = "solid";
          sugimg.style.borderWidth = "5px";
          sugimg.style.borderColor = "rgb(17, 45, 58)";
          sugimg.src = "https://s3.us-east-2.amazonaws.com/csrfresources/" + responseElements[i + 2];
          bardiv.appendChild(sugimg);
          innerdiv.innerHTML += responseElements[i];
          innerdiv.style.textAlign = "right";
          innerdiv.style.margin = "20px";
          bardiv.value = responseElements[i + 1];
          bardiv.addEventListener("mouseover",function(){
            isHovering = true;
          });
          bardiv.addEventListener("mouseout",function(){
            isHovering = false;
          });
          bardiv.appendChild(innerdiv);
          document.getElementById("searchsuggestions").appendChild(bardiv);
          bardiv.addEventListener("click",function(){
            if(this.value != 29 ||  !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
              location.href = "buytickets?eventid=" + this.value + "&saletype=" + document.getElementById("saletype").innerHTML;
            }else{
              location.href = "mia";
            }
          });
        }
        if(parseInt(event.keyCode) == 13){
          document.getElementById("searchsuggestions").childNodes[0].click();
        }
      }else if(response == 0){
        document.getElementById("searchsuggestions").style.visibility = "visible";
        document.getElementById("searchsuggestions").innerHTML = "<p>No Events Found!</p>";
        document.getElementById("searchsuggestions").style.color = "white";
        document.getElementById("searchsuggestions").style.fontfamily = "sitefont";
        document.getElementById("searchsuggestions").style.size = "100px";
        document.getElementById("searchsuggestions").style.padding = "10px 20px";
      }
    }
  }
  var search = "search=" + document.getElementById("searchbar").value + "&location=" + document.getElementById("currentLoc").value;
  conn.open("POST","../PHP/searchevents",true);
  conn.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  conn.send(search);
}

function getLocation() {
  /*
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(findCity);
    }
    */
    document.getElementById("currentLoc").selected = 1;
}

function findCity(position) {
    var coords = [[41.8781,87.6298],[39.9526,75.1652]];
    var closest = [-1,0];
    for(i = 0; i < coords.length; i++){
      var y = Math.sin(coords[i][1]);
      var x = Math.cos(coords[i][0]);
      var distance = Math.sqrt((position.coords.latitude - x) * (position.coords.latitude - x) + (position.coords.longitude - y) * (position.coords.longitude - y));
      if(distance == -1 || distance <= closest[0]){
        closest[0] = distance;
        closest[1] = i;
      }
    }
    document.getElementById("currentLoc").selected = closest[1];
    //Need to address multiple cities with location ranking
}
