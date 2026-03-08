<?php require_once "../core/omr-connect.php"; ?>
<?php
 
if(isset($_POST['submit']))
{
     $news_category = $_POST['news_category'];
     $news_title = $_POST['news_title'];
     $dob = $_POST['date_updated'];
     $Address = $_POST['author_name'];
     $pincode = $_POST['pincode'];
	 $qual = $_POST['news_locality'];
     $Course = $_POST['news_category'];
     $resumeupload = $_FILES['news_image']["name"];

$target_dir = "news_images/";
$target_file = $target_dir . basename($_FILES["news_images"]["name"]);
$uploadOk = 1;
$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


// Check if file already exists
if (file_exists($target_file)) {
  echo "<br>";
  echo "Sorry, file already exists.";
  echo "please rename your file and upload again";

  echo "<br>";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["resumeupload"]["size"] > 500000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($FileType != "jpg" && $FileType != "png" && $FileType != "gif" ) {
  echo "Sorry only pdf,doc,docx files are allowed.";

  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "<br>";
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {

  if($update_Result)
  {
    $sql = "UPDATE basicform1 SET fname='$fname',lname='$lname',email='$email',mobileno='$mobileno',Gender='$Gender',dob='$dob',Address='$Address',state='$state',district='$district',pincode='$pincode',qual='$qual',Course='$Course',resumeupload ='$resumeupload'WHERE email='$email' ";

    if (mysqli_query($conn, $sql)) 
      {
    header("location:profile.php");  
  }
 else{
      echo 'Data Not Updated';
      } 
      mysqli_close($conn);
    }
  if (move_uploaded_file($_FILES["resumeupload"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["resumeupload"]["name"])). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
?>


