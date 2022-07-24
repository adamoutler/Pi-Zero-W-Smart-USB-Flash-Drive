<?php
//$target_dir = "uploads/";
$target_dir = "/home/pi/usb_share/upload/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

$cmd = "sudo echo '" . serialize($_FILES) . "' > /home/pi/usb_share/flags/upload_data";
shell_exec($cmd);
echo($cmd);
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
//header( 'Location: /index.php' ) ;
?>