<?php
require "PHP/getTicketData.php";
require "PHP/protocol.php";
require "config.php";
//ethanrboywr@gmail.com
//runkett73@gmail.com
//dalessio.sarah@gmail.con
//wrberem@vcu.edu
//johngogilava@gmail.con
//korikeene15@gmail.con
//adamsteinbauer@gnail.com
$query = "";
$email = openssl_encrypt("adamsteinbauer@gnail.com",'AES-256-CBC',$decryption->key,0,$decryption->IV);
$memes = $conn->query("DELETE FROM users WHERE email = '$email'");
while($row = mysqli_fetch_assoc($memes)){

}
$conn->close();
 ?>
