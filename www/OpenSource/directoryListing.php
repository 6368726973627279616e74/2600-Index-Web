<!--
  directoryListing.php

  Author:      wrepp
  Date:        12/31/2009
  Description: directory for Source files/data of 2600 index web site
  Notes:       until I come up with something better.
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php
  $path = "../";

  include ($path . "db.php");

/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);
  
/* query parameters */
  $o = @$_GET["o"];
  
?>

<html>
  <head>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8">  
  <title>Open Source - <?php echo $o ?></title>
  <?php include ($path . "header.php"); ?>
  </head>

  <body>

  <?php include ($path . "page.php"); ?>

  <div id="content">

    <p class="section">Open Source - <?php echo $o ?></p>

    <p>
     My intent is to open source this entire web site. This page here is just an experiment whose only purpose was to make the data available now in case any one might find it useful. The Data and SQL are just mysqldumps, the PHP is source text exported to html. My first priority is entering the data so it may be some time before I clean this up and figure out a better way of presenting it.
    </p>
    
    <p class="section">Files</p>
    
    <table width="50%" cellspacing="1px" cellpadding="2px">
      <thead>
        <tr bgcolor="LIGHTGREY">
          <td>
            View (in new tab)
          </td>
          <td>
            Download (Save Link As..)
          </td>
        </tr>
      </thead>
<?php
    #$dir = getcwd ();
    #echo $dir . "<br>";
    
    $dh = opendir ($o);
    $files = array ();
    $dirs = array ();

    while ($file = readdir ($dh)) {

    /* save directories seperately */
      $dir = $o . "/" . $file;
      if (is_dir ($dir))  {
        if ($file != "." && $file != "..")  $dirs[] = $file;
      }
      else  {
        $files[] = $file;
      }
    }  // while
    closedir ($dh);
    
    #chdir ($dir);
    #echo getcwd () . "<br>";
    
  /* display in sorted order */
    sort ($files);
    
    $inbr = 0;
    foreach ($files as $file) {
      #echo $inbr . ", " . $inbr % 2 . "<br>";
      
      if ($inbr == 0 || ($inbr % 2) == 0) {
        if (($inbr % 4) == 0 || ($inbr % 4) == 1)  echo '<tr bgcolor="HONEYDEW">';
        else  echo '<tr bgcolor="PALEGREEN">';
      }
?>
        <td>
          <a href=<?php echo $o . "/" . $file ?> target=_blank><?php echo $file ?></a>
        </td>
<?php
      if ($inbr != 0 && ($inbr % 2) == 1)  {
        echo "</tr>";
      }
      $inbr++;
    }
    
?>
    </table>

    <br>
<?php
    foreach ($dirs as $dir)  {
       echo '<a href="directoryListing.php?o=' . $o . "/" . $dir . '"' . '>' . $dir . '</a><br>';
    }

    $i = strpos ($o, "/");
    if ($i > 0) {
      echo '<a href="directoryListing.php?o=' . substr ($o, 0, $i) . '"' . '>back</a><br>';
    }
?>

  </div>

<?php
  mysqli_close ($conn);
?>

  <div id="foot">
    <?php include ($path . "footer.php"); ?>
  </div>

  </body>
</html>
