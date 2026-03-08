<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}
$title = 'Update News';
$breadcrumbs = ['News Bulletin' => 'news-list.php', 'Update News' => null];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update News - MyOMR CMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f6f8; }
        .main-content { padding: 2rem; }
    </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <?php include __DIR__ . '/admin-sidebar.php'; ?>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content" aria-label="Main content">
      <?php include __DIR__ . '/admin-header.php'; ?>
      <?php include __DIR__ . '/admin-breadcrumbs.php'; ?>
      <?php include __DIR__ . '/admin-flash.php'; ?>
      <form name="myForm" method="POST" action="updatenewsform.php" onsubmit="validateForm()" enctype="multipart/form-data">

<!---------------------- News Title ------------------------------------------> 
<div class="container">

  <div class="row" style="margin:0px; padding:0px;">
  <div class="col-sm-4" style="margin:0px; padding:5px;" ><label>News Title</label></div>
  <div class="col-sm-8" style="margin:0px; padding:5px;"><input type="text" name="lname" id="lname" onblur="lastname()" size="20" placeholder="News Title" class="field-long" onfocusout="validateForm()" required/><span id="lnameerr" style="color: #D8000C;"></span></div>
</div>
</div>

<!--------------------------Date of Update----------------------------------->
<div class="container">
<div class="row" style="margin:0px; padding:0px;">
<div class="col-sm-4" style="margin:0px; padding:5px;" ><label>Date Updated-</label></div>
<div class="col-sm-8" style="margin:0px; padding:5px;"><input type="date" name="date_updated" onblur="dateofbirth()" class="field-long" id="dob" size="20"/></div>
</div>
</div>

<!------------------------- News Content ------------------>
<div class="container">
<div class="row" style="margin:0px; padding:0px;">
<div class="col-sm-4" style="margin:0px; padding:5px;" ><label>News Content</label></div>
<div class="col-sm-8" style="margin:0px; padding:5px;"><textarea cols="40" rows="5" placeholder="updated news content here" name ="news_content" value="Address" id="Address" required> 
</textarea>
</div>
</div>
</div>
<!---------------------- Author name ------------------------------------------> 
<div class="container">

  <div class="row" style="margin:0px; padding:0px;">
  <div class="col-sm-4" style="margin:0px; padding:5px;" ><label>Name of the Author</label></div>
  <div class="col-sm-8" style="margin:0px; padding:5px;"><input type="text" name="author_name" id="lname" onblur="lastname()" size="20" placeholder="Name of The Author" class="field-long" onfocusout="validateForm()" required/><span id="lnameerr" style="color: #D8000C;"></span></div>
</div>
</div>

<!-------------------------- State ------------------------------------->
<div class="container">
<div class="row" style="margin:0px; padding:0px;">

<div class="col-sm-4" style="margin:0px; padding:5px;" ><label>News Locality</label></div>
    
<div class="col-sm-8" style="margin:0px; padding:5px;">
<select  id="inputState" name="state" >
                          <option value="OMR" selected>OMR</option>	
                          <option value="Thuraipakkam">Thuraipakkam</option>
                          <option value="Perungudi">Perungudi</option>
                          <option value="Sholinganallur">Sholinganallur/option>
                          <option value="Navalur">Navalur</option>
                          <option value="Karapakkam">Karapakkam</option>
                          <option value="SRP Tools">SRP Tools</option>

                         
</select>
</div>
</div>
</div>
  
<!----- -------------------- Pin Code-------------------------------------->
<div class="container">
  <div class="row" style="margin:0px; padding:0px;">
  <div class="col-sm-4" style="margin:0px; padding:5px;" ><label>Pincode:-</label></div>
  <div class="col-sm-8" style="margin:0px; padding:5px;"> <input type="text" name="pincode" onblur="code()" id="pincode" size="20" placeholder="pincode" class="field-long" required/><span id="codeerr" style="color: #D8000C;"></span>
</div>
</div>
</div>

 <!-----------------------NEWS CATEGORY---------------------------------------->
 <div class="container">
  <div class="row" style="margin:0px; padding:0px;">
  <div class="col-sm-4" style="margin:0px; padding:5px;" ><label>NEWS Category</label></div>
  <div class="col-sm-8" style="margin:0px; padding:5px;">
 
<input type="checkbox" name="qual"  value="" /> Business&emsp;&emsp;&emsp;&emsp;
<input type="checkbox" name="qual"  value="" /> Cars&emsp;&emsp;&emsp;&emsp;
<input type="checkbox" name="qual"  value="" /> Entertainment</br>
<input type="checkbox" name="qual"  value="" /> Family&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="qual"  value="" /> Health&emsp;&emsp;&emsp;
<input type="checkbox" name="qual"  value="" /> Politics</br>
<input type="checkbox" name="qual"  value="" /> Religion&emsp;&emsp;&emsp;&emsp;&nbsp;
<input type="checkbox" name="qual"  value="" /> Science&emsp;&emsp;&nbsp;&nbsp;
<input type="checkbox" name="qual"  value="" /> Sports</br>
<input type="checkbox" name="qual"  value="" /> Technology&emsp;&emsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="qual"  value="" /> Travel&emsp;&emsp;&emsp;
<input type="checkbox" name="qual"  value="" /> Video</br>
<input type="checkbox" name="qual"  value="" /> World</br>
  
</div>
</div>
</div>

<!---------------------------- Image ----------------------------------->


<div class="container">
  <div class="row" style="margin:0px; padding:0px;">
    <div class="col-sm-4" style="margin:0px; padding:5px;" ><label for="resumeupload">Upload Image for the Article:-</label></div>
    <div class="col-sm-8" style="margin:0px; padding:5px;"><input type="file" id="Upload News Image" name="news_image" size="40%" multiple>
</div>
  </div>
</div>

<!---------------------------Submit----------------------------------------->

<div class="container">
  <div class="row" style="margin:0px; padding:0px;">
  <div class="col-sm-8" style="margin:0px; padding:10px;">
   <center> <input type="submit" name="submit" value="submit" >
        <input type="reset" name="clear" value="clear">
</center>
</div>
</div>
</div>
</form>
    </main>
  </div>
</div>
</body>
</html>