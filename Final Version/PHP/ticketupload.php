<html>
<body>
<form id="sellform" method="POST" action="../collateral">
  <input type="hidden" name="ticketclasssell" value="<?php echo $_POST['ticketclasssell'];?>">
  <input type="hidden" name="saletype" value="<?php echo $_POST['saletype'];?>">
  <input type="hidden" name="selltogether" value="<?php echo $_POST['selltogether'];?>">
  <input type="hidden" name="delivery" value="<?php echo $_POST['delivery'];?>">
  <input type="hidden" name="eventid" value="<?php echo $_POST['eventid'];?>">
  <input type="hidden" name="filepaths" id="filepaths">
  <input type="hidden" name="price" value="<?php echo $_POST['price'];?>">
  <input type="hidden" name="seatloc" id="seatloc">
</form>
<script>
var tktupl = "<?php

$typesValid = true;
$acceptableSize = true;
$notTooManyFiles = true;
$quantity = $_POST["quantity"];
$fileAmount = 0;

$output->paths = "";

for($i = 0; $i < count($_FILES["proof"]["error"]); $i++){
  if(empty($_FILES["proof"]["name"][$i])){
    break;
  }
  $filetype = strtolower(pathinfo($_FILES["proof"]["name"][$i],PATHINFO_EXTENSION));
  if($filetype != "pdf" && $filetype != "jpg" && $filetype != "png" && $filetype != "jpeg"){
    $typesValid = false;
  }
  if($_FILES["proof"]["size"][$i] > 5000000){
    $acceptableSize = false;
  }
  $fileAmount++;
}

if($fileAmount > $quantity){
  $notTooManyFiles = false;
}

if($typesValid == true && $acceptableSize == true && $notTooManyFiles == true){
  for($i = 0; $i < $fileAmount; $i++){
  if ($_FILES["proof"]["error"][$i] == UPLOAD_ERR_OK) {
      $tmp_name = $_FILES["proof"]["tmp_name"][$i];
      $targetfile = "../ticketuploads/" . basename($_FILES["proof"]["name"][$i]);
      if(file_exists($targetfile)){
        unlink($targetfile);
      }
      move_uploaded_file($tmp_name, $targetfile);
      $output->paths = $output->paths . "ticketuploads/" . basename($_FILES["proof"]["name"][$i]) . "#";
  }else{
    $output->paths = "uplerr" . $_FILES["proof"]["error"][$i];
  }
}
if(strpos($output->paths,"err") !== false){
  /*
  $url = 'http://ossiatickets.com/PHP/proofreaderv1';
  $data = array('paths' => $output->paths);

  // use key 'http' even if you send the request to https://...
  $options = array(
      'http' => array(
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'POST',
          'content' => http_build_query($data)
      ),
      ,
      "ssl" => array(
          "verify_peer"=> false,
          "verify_peer_name"=> false,
      )
  );
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  $output->seating = $result;
  */
}
echo $output->paths;
}else{
  if($typesValid == false){
    $output->paths = "vererr1";
  }else if($acceptableSize == false){
    $output->paths = "vererr2";
  }else if($notTooManyFiles == false){
    $output->paths = "vererr3";
  }
  echo $output->paths;
}
 ?>";
 var seatinfo = '<?php
 $seatobj = "";
 for($i = 0; $i < sizeof($_POST['section']); $i++){
   if(!empty($_POST['section'][$i])){
     $theseat->section = $_POST['section'][$i];
    $theseat->row = $_POST['row'][$i];
    $theseat->seatnum = $_POST['seatnum'][$i];
    $seatobj = $seatobj . json_encode($theseat) . "#";
  }
 }
echo $seatobj;
?>';
document.getElementById("seatloc").value = seatinfo;
if(tktupl == "vererr1"){
  location.href = "../index?error=invalidFileType";
}else if(tktupl == "vererr2"){
  location.href = "../index?error=tooBigFile";
}else if(tktupl == "vererr3"){
  location.href = "../index?error=tooManyFiles";
}else if(tktupl == ""){
  location.href = "../index?error=unkerrsell";
}else{
  document.getElementById("filepaths").value = tktupl;
  document.getElementById("sellform").submit();
}
</script>
</body>
</html>
