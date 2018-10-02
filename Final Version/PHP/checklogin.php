<?php
$sessionid = $_COOKIE["loginid"];
require "getTicketData.php";
if(isset($_POST["logout"])){
  $preparing = $conn->prepare("UPDATE users SET sessionid = '' WHERE sessionid = ?");
  $preparing->bind_param("s",$sessionid);
  $preparing->execute();
}else{
$preparing = $conn->prepare("SELECT sessionid FROM users WHERE sessionid = ?");
$preparing->bind_param("s",$sessionid);
$preparing->execute();
$isValid = mysqli_fetch_assoc($preparing->get_result())["sessionid"];
if(isset($isValid) && !empty($sessionid) && !empty($isValid)){
  echo true;
}else{
  echo false;
}
$conn->close();
}
?>
