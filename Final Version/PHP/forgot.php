<?php
require_once "getTicketData.php";
require_once "protocol.php";

$email = openssl_encrypt($_POST["email"],"AES-256-CBC",$decryption->key,0,$decryption->IV);

$preparedstatement = $conn->prepare("SELECT email FROM users WHERE email = ?");
$preparedstatement->bind_param("s",$email);
$preparedstatement->execute();
$email = mysqli_fetch_assoc($preparedstatement->get_result())["email"];

  if(!empty($email)){
    require_once '../vendor/autoload.php';

    $resetcode = uniqid(substr($email,6),true);

    $conn->query("UPDATE users SET resetcode = '$resetcode' WHERE email = '$email'");

    putenv('GOOGLE_APPLICATION_CREDENTIALS=client_key.json');
    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->setSubject("tickets@ossiatickets.com");
    $client->setScopes(Google_Service_Gmail::GMAIL_MODIFY);
    $client->setApplicationName('ticketdatabase');
    $service = new Google_Service_Gmail($client);

      $message = (new Swift_Message('Password Reset'))
        ->setFrom(['tickets@ossiatickets.com' => 'Ossia Tickets'])
        ->setTo([$_POST["email"]]);
        $logo = $message->embed(Swift_Image::fromPath('https://s3.us-east-2.amazonaws.com/csrfresources/logo_email.png'));
          $message->setBody("<html lang='en' dir='ltr'>" .
          "<head>" .
          "<meta charset='utf-8'>" .
          "</head>" .
          "<style>" .
          "p {margin-top:0.5em;" .
          "margin-bottom:0.5em;}" .
          "</style>" .
          "<body style='font-family: Tahoma, Geneva, sans-serif; display: block; width:100vw; height:100vh;'>" .
          "<div id='content' style='display:block; width:50%; padding:16px; text-align:center; border: 1px solid black;'>" .
          "<img src='" . $logo . "' style='width:calc(750px / 3); margin-right:500px; height:calc(220px / 3);' id='logo'>" .
          "<h1 style='margin-bottom:0.2em;'>Forgot your Password?</h1>" .
          "<br>" .
          "<br>" .
          "<h2>No Worries!</h2>" .
          "<a href='https://ossiatickets.com/passreset?resetcode=$resetcode' style='font-size:24px;'>Reset your Password</a>" .
          "<br>" .
          "<br>" .
          "<br>" .
          "<br>" .
          "<br>" .
          "<br>" .
          "<div id='social' style='width: 100%; display:inline;'>" .
          "<a style='padding-right:30px; padding-left:30px;' href='https://open.spotify.com/user/commander_jamslo/playlist/5JAKeOWzrBBExc1HEjlhT0?si=HxZlj3alS-2mpEB1n1MpPA'>Spotify</a>" .
          "<a style='padding-right:30px; padding-left:30px;' href='https://www.facebook.com/ossiatickets/'>Facebook</a>" .
          "<a style='padding-right:30px; padding-left:30px;' href='https://www.instagram.com/ossiatickets/'>Instagram</a>" .
          "</div>" .
          "<br>" .
          "<p>Questions? Concerns? Call us at 1-(773)-750-6005 or send us an email at tickets@ossiatickets.com</p>" .
          "</div>" .
          "</body>" .
          "</html>","text/html");

      $emailmessage = new Google_Service_Gmail_Message();
      $message = strtr(base64_encode($message), array('+' => '-', '/' => '_'));
      $emailmessage->setRaw($message);

      $service->users_messages->send("me",$emailmessage);

    echo "success";

  }else{
    echo "email not found";
  }
$conn->close();
?>
