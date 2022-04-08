<!--
  header.php
-->

<?php

/* work with vhost or subfolder - locate stylesheet */
  if (is_file ("style.css"))  {
    $fullpath = "";
  }
  elseif (is_file ("/style.css"))  {
    $fullpath = "/";
  }
  elseif (is_file ("../style.css"))  {
    $fullpath = "../";
  }
  
?>

<link rel="shortcut icon" href="<?php echo $fullpath ?>WREpp_x.ico">

<link href="<?php echo $fullpath ?>style.css" rel="stylesheet" type="text/css">
