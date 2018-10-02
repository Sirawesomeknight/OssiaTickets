<!DOCTYPE html>
<html>
<head>
  <title>Ossia</title>
  <link rel="stylesheet" href="CSS/profile.css">
  <link rel="stylesheet" href="CSS/topbar.css">
  <link rel="stylesheet" href="CSS/bottom.css">
  <link rel="stylesheet" href="CSS/textinput.css">
  <link rel="stylesheet" href="CSS/dropinput.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="JS/profile.js"></script>
  <script src="JS/loadbackground.js" defer></script>
  <script src="JS/checklogin.js" defer></script>
  <script src="JS/classie.js" defer></script>
</head>
<body onload="load()">
<div id="content">
  <div id="tint">
    <div id="navbar">
      <a class="navbutton" href="login" id="login">Login</a>
      <a class="logocont" href="index"><img src="logonotext.png" class="logo"></a>
      <a class="navbutton" href="faq">FAQ</a>
    </div>
    <div id="profilecontent">
      <div id="orderbox">
        <h1>Orders</h1>
        <table id="orderhistory">
        </table>
        <br>
        <br>
      </div>
      <hr>
      <div id="money">
        <h1>Ossia Credit</h1>
        <table>
          <tr>
            <th><h3>Cash Owed:</h3></th>
            <th><h3 id="cashowed"></h3></th>
          </tr>
          <!-- <tr>
            <th><h3>Referral Dollars:</h3></th>
            <th><h3 id="rdollars"></h3></th>
          </tr> -->
        </table>
        <p>Your cash owed will automatically be deposited in your account within 5 days after the event</p>
        <br>
      </div>
      <br>
      <hr>
      <div id="userinfobox">
        <h1>User Information</h1>
        <div id="uinfo">
        <div class="uinfobox">
        <table>
          <tr>
            <th><p>Email:</p></th>
            <th><p id="email"</p></th>
            </tr>
            <tr>
              <th><p>Name:</p></th>
              <th><p id="fullname"></p></th>
            </tr>
            <!-- <tr>
              <th><p>Referral Code:*</p></th>
              <th><p id="referralcode"></p></th>
            </tr> -->
          <tr>
            <th><p>Address: </p></th>
            <th><p id='address'></p></th>
          </tr>
          <tr>
            <th><p>Payment Methods:</p></th>
            <th><div class="uinfobox">
              <div class='custom-select'>
                <select id="paymentmethods">
                  <option selected disabled>Payment Methods</option>
                </select>
              </div>
            </div></th>
            <th><button type="button" id="deletepay">Remove Payment Method</button></th>
          </tr>
          </table>
          </div>
          </div>
          <!-- <p>*Give your friends this code when you sign up and receive $5 off your next ticket purchase!</p> -->
          <br>
          <button type="button" onclick="changeemail()">Change Email</button>
          <button type="button" onclick="changepassword()">Change Password</button>
          <button type="button" onclick="changeaddress()">Change Address</button>
          <button type="button" onclick="logout()">Logout</button>
          <!-- <button type="button" onclick="addpaymentmethod()">Add Payment Method</button> -->
          <br>
          <br>
        </div>
      </div>
    </div>
  </div>
  <div id="bottom">
    <div id="bottom-cont">
      <div id="bottom-left">
        <a href="contact">Contact</a>
        <a href="privacy">Privacy</a>
        <a href="terms">Terms</a>
      </div>
      <div id="bottom-right">
        <a href="https://www.instagram.com/ossiatickets/"><i class="fa fa-instagram"></i></a>
        <a href="https://www.facebook.com/ossiatickets/"><i class="fa fa-facebook"></i></a>
        <p>&copy Warp10 LLC 2018 </p>
      </div>
    </div>
  </div>
  <div id="overlay">
    <div class="overlaydiv" id="changepassword">
      <h1>Change Password</h1>
      <form>
        <span id='profilespan' class='input input-text'>
        <input type='password' id='cpass' class='input_field input_field-text' name='cpass'>
        <label class='input_label input_label-text' for='cpass'>
          <span class='input_label-content input_label-content-text'>Current Password</span>
        </label>
        <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
          <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
        </svg>
        </span>
        <span id='profilespan' class='input input-text'>
        <input type='password' id='passn' class='input_field input_field-text' name='passn'>
        <label class='input_label input_label-text' for='passn'>
          <span class='input_label-content input_label-content-text'>New Password</span>
        </label>
        <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
          <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
        </svg>
        </span>
        <span id='profilespan' class='input input-text'>
        <input type='password' id='passn1' class='input_field input_field-text' name='passn1'>
        <label class='input_label input_label-text' for='passn1'>
          <span class='input_label-content input_label-content-text'>Confirm New Password</span>
        </label>
        <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
          <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
        </svg>
        </span>
        <h3>Passwords must include:</h3>
          <p>More than 7 characters</p>
          <p>At least 1 capital letter</p>
          <p>At least 1 number</p>
        <button class="stdbutton" type="button" onclick="submitpass()">Submit</button>
      </form>
    </div>
  <div class="overlaydiv" id="changeemail">
    <h1 style="margin-bottom:0;">Change Email</h1>
    <form>
      <span id='profilespan' class='input input-text' style="margin-top:0;">
      <input type='password' id='cpassemail' class='input_field input_field-text' name='cpassemail'>
      <label class='input_label input_label-text' for='cpassemail'>
        <span class='input_label-content input_label-content-text'>Current Password</span>
      </label>
      <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
        <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
      </svg>
      </span>
      <span id='profilespan' class='input input-text' style="margin-top:0;">
      <input type='input' id='nemail' class='input_field input_field-text' name='nemail'>
      <label class='input_label input_label-text' for='nemail'>
        <span class='input_label-content input_label-content-text'>New Email</span>
      </label>
      <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
        <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
      </svg>
      </span>
      <button style="margin-top:10px;"class="stdbutton" type="button" onclick="submitemail()">Submit</button>
    </form>
  </div>
  <div class="overlaydiv" id="addpaymentmethod">
    <h1>Add Payment Method</h1>
    <span id='profilespan' class='input input-text'>
    <input type='input' id='credit_card_num' class='input_field input_field-text' name='credit_card_num'>
    <label class='input_label input_label-text' for='credit_card_num'>
      <span class='input_label-content input_label-content-text'>Credit Card Number</span>
    </label>
    <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
      <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
    </svg>
    </span>
    <span id='profilespan' class='input input-text'>
    <input type='input' id='credit_card_hold' class='input_field input_field-text' name='credit_card_hold'>
    <label class='input_label input_label-text' for='credit_card_hold'>
      <span class='input_label-content input_label-content-text'>Credit Card Holder</span>
    </label>
    <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
      <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
    </svg>
    </span>
    <span id='profilespan' class='input input-text'>
    <input type='input' id='expr_mon' class='input_field input_field-text' name='expr_mon'>
    <label class='input_label input_label-text' for='expr_mon'>
      <span class='input_label-content input_label-content-text'>Expiration Month</span>
    </label>
    <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
      <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
    </svg>
    </span>
    <span id='profilespan' class='input input-text'>
    <input type='input' id='expr_yr' class='input_field input_field-text' name='expr_yr'>
    <label class='input_label input_label-text' for='expr_yr'>
      <span class='input_label-content input_label-content-text'>Expiration Year</span>
    </label>
    <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
      <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
    </svg>
    </span>
    <span id='profilespan' class='input input-text'>
    <input type='input' id='CSC' class='input_field input_field-text' name='CSC'>
    <label class='input_label input_label-text' for='CSC'>
      <span class='input_label-content input_label-content-text'>CSC</span>
    </label>
    <p>PUT AN I CIRCLE HERE AND SHOW WHAT THE CSC IS</p>
    <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
      <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
    </svg>
    </span>
    <span id='profilespan' class='input input-text'>
    <input type='input' id='zip' class='input_field input_field-text' name='zip'>
    <label class='input_label input_label-text' for='zip'>
      <span class='input_label-content input_label-content-text'>Zip Code</span>
    </label>
    <svg class='graphic graphic-text' width='300%'' height='100%'' viewBox='0 0 1200 60' preserveAspectRatio='none'>
      <path d='M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0'/>
    </svg>
    </span>
  </div>
  <div class="overlaydiv" id="changeaddress">
    <h1>Change Address</h1>
    <br>
    <span class="input input-text" style="margin-top:0;">
      <input class="input_field input_field-text" type="text" id="line1">
      <label class="input_label input_label-text" for="line1">
        <span class="input_label-content input_label-content-text">Address Line 1</span>
      </label>
      <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
        <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
      </svg>
    </span>
    <!-- <input type="input" class="inputbox" name="line1" id="line1" placeholder="Address Line 1" required><br><br> -->
    <span class="input input-text" style="margin-top:0;">
      <input class="input_field input_field-text" type="text" id="line2">
      <label class="input_label input_label-text" for="line2">
        <span class="input_label-content input_label-content-text">Address Line 2</span>
      </label>
      <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
        <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
      </svg>
    </span>
    <!-- <input type="input" class="inputbox" name="line2" id="line2" placeholder="Address Line 2"><br><br> -->
    <span class="input input-text" style="margin-top:0;">
      <input class="input_field input_field-text" type="text" id="city">
      <label class="input_label input_label-text" for="city">
        <span class="input_label-content input_label-content-text">City</span>
      </label>
      <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
        <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
      </svg>
    </span>
    <!-- <input type="input" class="inputbox" name="city" id="city" placeholder="City" required><br><br> -->
    <div class='custom-select'>
    <select name="state" id="state">
      <option disabled selected>State</option>
    </select></div><br><br>
    <span class="input input-text" style="margin-top:0;">
      <input class="input_field input_field-text" type="text" id="zipcode" onkeypress="return zipCheck(event)">
      <label class="input_label input_label-text" for="zip">
        <span class="input_label-content input_label-content-text">Zip Code</span>
      </label>
      <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
        <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
      </svg>
    </span>
    <span class="input input-text" style="margin-top:0;">
      <input class="input_field input_field-text" type="password" id="addresspass">
      <label class="input_label input_label-text" for="loginpass">
        <span class="input_label-content input_label-content-text">Password</span>
      </label>
      <svg class="graphic graphic-text" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
        <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
      </svg>
    </span>
    <!-- <input type="input" class="inputbox" name="zipcode" id="zipcode" placeholder="Zip Code" required><br><br> -->
    <hr>
    <button class="stdbutton" id="validateaddr" type="button" onclick="submitAddress()">Change Address</button>
  </div>
  <div id="loadoverlay">
    <img id="loadingicon" src="https://s3.us-east-2.amazonaws.com/csrfresources/loadingicon.gif">
  </div>
</div>
</body>
</html>
