<!--
  KeywordsWithNoArticles.php

  Author:      wrepp
  Date:        10/29/2008
  Description: keywords with no articles report
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
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>2600 Index</title>
    <?php include ($includepath . "header.php"); ?>
  </head>

  <body>

  <?php include ($includepath . "page.php"); ?>

  <div id="content">

    <p class="section">Keywords with no Articles</p>
  <table width="75%" cellspacing="1px" cellpadding="2px" >
  <thead>
  <tr bgcolor="LIGHTGREY">
    <td>Keyword</td>
    <td>Notes</td>
  </tr>
  </thead>

  <?php
    $sql = "SELECT Keyword, Notes " .
           "FROM Keywords k " .
           "LEFT JOIN KeywordXref kx ON k.KeywordsID = kx.KeywordsID " .
           "WHERE kx.ArticlesID IS NULL;";
    #echo $sql;
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $inbr = 0;
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
  ?>
      <tr bgcolor=<?php if ($inbr % 2)  echo "palegreen"; else  echo "HONEYDEW";?> >
        <td>
          <?php echo $row['Keyword'] ?>
        </td>
        <td>
          <?php echo $row['Notes'] ?>
        </td>
      </tr>

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
    <?php include ($includepath . "footer.php"); ?>
  </div>
  </body>
</html>


