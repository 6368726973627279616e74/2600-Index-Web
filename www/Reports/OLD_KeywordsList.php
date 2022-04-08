<!--
  KeywordsList.php

  Author:      wrepp
  Date:        10/14/2009
  Description: keywords list report
  Notes:
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php

  $includepath = "../";
#echo $includepath . "<br>";

  include ($includepath . "db.php");

/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);
  
/* query parameters */
  $o = @$_GET["o"];
  #echo "o=" . $o . "=<br>";
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>2600 Index</title>
    <?php #include ($includepath . "header.php"); 
      if ($o == "")  include ($includepath . "header.php");
      else  {
    ?>
      <style type="text/css">
        .section  {
          font-size:14;
          font-weight:bold;
          font-family:arial, times new roman, serif;
        }
        .red  {
          color: #FF0000;
        }
        body {font-size:75%}
      </style>
    <?php
      }
    ?>
  </head>

  <body>

  <?php if ($o == "")  include ($includepath . "page.php");
     #echo $includepath;
     #$include = include $includepath . "page.php";
     #echo "include=" . $include;
     #if ($o == "")  echo $include;
  ?>

  <div id="content">
    <noscript>
    <p class='red'>This page requires Javascript to function properly!!</p>
    </noscript>

    <p class="section">Keywords List</p>
  <table width="75%" cellspacing="1px" cellpadding="2px" >
  <thead>
  <tr bgcolor="lightgrey">
    <td>Keyword</td>
    <td>Keyword</td>
    <td>Keyword</td>
    <td>Keyword</td>
    <td>Keyword</td>
    <td>Keyword</td>
  </tr>
  </thead>

  <?php
    $sql = "SELECT * "  .
           "FROM Keywords " .
           "ORDER BY Keyword;";
    #echo $sql;
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
    
    #$nbr = mysqli_num_rows($result);

    $inbr = 0;  // number for records
    $cnbr = 0;  // number for color
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
    
    /* first time thru and every six items */
      if ($inbr == 0 || ($inbr % 6) == 0)  {
  ?>
      <tr bgcolor=<?php if ($cnbr % 2)  echo "palegreen"; else  echo "honeydew";?> >
  <?php
      }
      #echo $inbr % 6;
      $href = "";
      if ($row['WikipediaLink'] != "")  {
        
      /* get first one if multiple links */
        $links = split("\r\n", $row['WikipediaLink']);
        if (count($links) > 0)  {
           $href = $links[0];
        }
        else  $href = $row['WikipediaLink'];
      }
  ?>      
        <td>
          <a style="text-decoration: none" title="<?php echo $row['Notes']?>" 
             href="<?php echo $href ?>">
            <?php echo $row['Keyword'] ?>
          </a>
        </td>
  <?php
    /* ignore first time, then after every six items */
      if ($inbr != 0 && ($inbr % 6) == 5)  {
  ?>      
      </tr>
  <?php
        $cnbr++;
      }
  ?>      
  <?php
      $inbr++;
    }
    if ($inbr < 1)  {
      echo "<tr><td>None found.<td></tr>";
    }
  ?>
  </table>

  </div>

<?php
  mysqli_close ($conn);
?>

  <div id="foot">
    <?php if ($o == "")  include ($includepath . "footer.php");
          else  {
     ?>
            <a HREF="JavaScript:void(0);" onClick="JavaScript:window.close();return false">
              Close
            </a>
    <?php
          };
    ?>
  </div>
  </body>
</html>


