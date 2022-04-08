<!--
  ArticlesWithNoKeywords.php

  Author:      wrepp
  Date:        10/13/2008
  Description: articles with no keywords report
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

    <p class="section">Indexed Articles with no Keywords</p>
  <table width="75%" cellspacing="1px" cellpadding="2px" >
  <thead>
  <tr bgcolor="LIGHTGREY">
    <td>Issue</td>
    <td>Article Title</td>
    <td>..</td>
  </tr>
  </thead>

  <?php
    $sql = "SELECT CONCAT(Period, ' ', IssueYear) AS 'Issue', Title, a.IssuesID, a.ArticlesID "  .
           "FROM Articles a " .
           "LEFT JOIN Issues i ON a.IssuesID = i.IssuesID " .
           "LEFT JOIN  KeywordXref k ON a.ArticlesID = k.ArticlesID " .
           "WHERE a.AuthorsID > 1 " .
           "  AND k.ArticlesID IS NULL;";
    #echo $sql;
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $inbr = 0;
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
  ?>
      <tr bgcolor=<?php if ($inbr % 2)  echo "palegreen"; else  echo "HONEYDEW";?> >
        <td>
          <?php echo $row['Issue'] ?>
        </td>
        <td>
          <?php echo $row['Title'] ?>
        </td>
        <td>
          <?php echo "<a href='../article.php?i=" . $row['IssuesID'] . "&a=" . $row['ArticlesID'] . "'>..</a>" ?>
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


