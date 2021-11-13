<?php
  require __DIR__.'/Sidebar.php';
  require __DIR__.'/SidebarDetails.php';
  require __DIR__.'/DisplayProfile.php';
  require __DIR__.'/Login.php';
  session_start(); 
  $conn = mysqli_connect('localhost', 'root', '') or die(mysqli_error($conn)); 
  mysqli_select_db($conn, 'dms') or die(mysqli_error($conn)); 

  $res=null;

  if(isset($_GET['sidebarCategory']))  $sidebarCategory = $_GET['sidebarCategory'];
  else $sidebarCategory = 'student';
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0", shrink-to-fit=no>
    <title>DMS Website</title>

    <!-- Bootstrap CDN 
    <link rel="stylesheet" href="css/style.css">-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- JQuery CDN-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
</head>

<body>
  
  <?php 

  if(isset($_POST['signOutBtn']))
  {

    unset($_SESSION['userType']);
    unset($_SESSION['userEmail']);
    unset($_POST['signOutBtn']);
    echo "<script>window.history.pushState({}, '', '/dms/');</script>";

  }
  
  if(!isset($_SESSION['userType']))
  {

    Login($conn);

  }
  elseif(isset($_POST['profileBtn']))
  {
    ?>
     <div style="display:flex">
     
    <div style="width:250px;">
      <?php Sidebar($conn, $_SESSION['userType'], $_SESSION['userEmail'], $sidebarCategory) ?>
    </div>

    <div class="verticalLine" style="border-left: 1px solid #b1b1b1;"></div>
  
    <?php DisplayProfile($_SESSION['userType'], $_SESSION['userEmail']); ?>
    </div>
    <?php

  }
  else 
  {

  ?>
  <div style="display:flex">

    <div style="width:250px;">
    <?php Sidebar($conn, $_SESSION['userType'], $_SESSION['userEmail'], $sidebarCategory) ?>
    </div>

    <div class="verticalLine" style="border-left: 1px solid #b1b1b1;"></div>

    <?php SidebarDetails($_SESSION['userType'], $sidebarCategory, $res, $conn) ?>

  </div>

  <?php
  }
  ?>

</body>
</html>