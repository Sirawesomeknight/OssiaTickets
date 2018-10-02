<!DOCTYPE html>
<html>
<head>
<title>Ossia</title>
<script src="JS/registered.js" defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="CSS/registered.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/bottom.css">
<script src="JS/loadbackground.js" defer></script>
<script src="JS/checklogin.js" defer></script>
</head>
<body onload="load()">
  <script>
  var validRegistry = <?php
  require_once 'vendor/autoload.php';
  require_once 'PHP/getTicketData.php';
  require_once 'PHP/protocol.php';
  require_once 'config.php';


  $email = $_POST["email"];
  $firstname = ucfirst(strtolower($_POST["firstname"]));
  $lastname = ucfirst(strtolower($_POST["lastname"]));

  $url = 'https://www.google.com/recaptcha/api/siteverify';
  $data = array('secret' => '6Ld0alEUAAAAAIidotF6dAKzaMqDUPlc6ilHuAYM', 'response' => $_POST["g-recaptcha-response"]);

  // use key 'http' even if you send the request to https://...
  $options = array(
    'http' => array(
      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      'method'  => 'POST',
      'content' => http_build_query($data)
    ),
    "ssl" => array(
        "verify_peer"=> false,
        "verify_peer_name"=> false,
    )
  );
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);

  $encryptedemail = openssl_encrypt($email ,"AES-256-CBC",$decryption->key,0,$decryption->IV);

  if($result["success"] == true){

    putenv('GOOGLE_APPLICATION_CREDENTIALS=client_key.json');
    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->setSubject("tickets@ossiatickets.com");
    $client->setScopes(Google_Service_Gmail::GMAIL_MODIFY);
    $client->setApplicationName('ticketdatabase');
    $service = new Google_Service_Gmail($client);

    $message = (new Swift_Message('Confirm Registration'))
    ->setFrom(['tickets@ossiatickets.com' => 'Ossia Tickets'])
    ->setTo([$email => "$firstname $lastname"]);
    $logo = $message->embed(Swift_Image::fromPath('https://s3.us-east-2.amazonaws.com/csrfresources/logo2.png'));
      $message->setBody("<html>" .
      "<body>" .
      "<img src='" . $logo . "' style='width:200px;'>" .
      "<h1>Confirm Registration</h1>" .
      "<br>" .
      "<br>" .
      "<p>Please confirm your email by clicking the link below.</p>" .
      "<a href='http://ossiatickets.com/confirm?conf=" . $encryptedemail . "'>Confirm Email</a>" .
      "</body>" .
      "</html>","text/html");

      $emailmessage = new Google_Service_Gmail_Message();
      $message = strtr(base64_encode($message), array('+' => '-', '/' => '_'));
      $emailmessage->setRaw($message);

      $result = $service->users_messages->send("me",$emailmessage);
      echo true;
    }else{
      $theemail = $conn->prepare("SELECT customerid FROM users WHERE email = ?");
      $theemail->bind_param("s",$encryptedemail);
      $theemail->execute();
      $stripecustomer = mysqli_fetch_assoc($theemail->get_result())["customerid"];
      $stripecustomer = \Stripe\Customer::retrieve($stripecustomer);
      $stripecustomer->delete();
      $deleteuser = $conn->prepare("DELETE FROM users WHERE email = ?");
      $deleteuser->bind_param("s",$encryptedemail);
      $deleteuser->execute();
      echo false;
    }
    $conn->close();
    ?>;
    if(validRegistry != true){
      alert("Invalid User!");
      location.href = "register.php";
    }
    </script>
    <div id="content">
      <div id="tint">
        <div id="navbar">
          <a class="navbutton" href="login" id="login">Login</a>
          <a class="logocont" href="index"><img src="logonotext.png" class="logo"></a>
          <a class="navbutton" href="faq">FAQ</a>
        </div>
          <div id="loginbox">
            <h1>You are registered!</h1>
            <p>Before signing in, check your inbox to confirm your email address!</p>
            <p>If its not in your inbox, check your spam</p>
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
  </body>
  </html>
