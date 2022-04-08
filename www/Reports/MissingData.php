<!--
  MissingData.php

  Author:      wrepp
  Date:        01/13/2009
  Description: missing data reports
  Notes:
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php

  $includepath = "../";
#echo $includepath . "<br>";

  include ($includepath . "db.php");

/* query parameters */
  $o = @$_GET["o"];
  if ($o == "")  $o = "All";

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

    <p class="section">Missing Data</p>

    <br>
    Missing &nbsp;
    <select id="Option" name="Option" onchange="window.location = 'MissingData.php?o=' + '' + this.value + ''">
      <option value="All" <?php if ($o == "All")  echo "selected";?> >All</option>
      <option value="Author" <?php if ($o == "Author")  echo "selected";?> >Author</option>
      <option value="Synopsis" <?php if ($o == "Synopsis")  echo "selected";?> >Synopsis</option>
      <option value="Page" <?php if ($o == "Page")  echo "selected";?> >Page</option>
    </select>


    <table width="95%" cellspacing="1px" cellpadding="2px" >
    <thead>
    <tr bgcolor="LIGHTGREY">
      <td>Issue</td>
      <td>Article Title</td>
      <td>..</td>
    </tr>
    </thead>
    
  <?php
    $sql = "";
    if ($o == "Author" || $o == "All")  {
      $sql .= "SELECT * " .
              "FROM vw_ArticleInfo " .
              "WHERE AuthorsID = 1 " .
              "  AND (TRIM(Synopsis) != '' OR Page != 0) ";
    }
    if ($o == "Synopsis" || $o == "All")  {
      if ($o == "All")  $sql .= " UNION ";
      $sql .= "SELECT * " .
              "FROM vw_ArticleInfo " .
              "WHERE TRIM(Synopsis) = '' " .
              "  AND (AuthorsID != 1 OR Page != 0) ";
    }
    if ($o == "Page" || $o == "All")  {
      if ($o == "All")  $sql .= " UNION ";
      $sql .= "SELECT * " .
              "FROM vw_ArticleInfo " .
              "WHERE Page = 0 " .
              "  AND (TRIM(Synopsis) != '' OR AuthorsID != 1) ";
    }
    $sql .= "ORDER BY IssueYear, `Quarter`, Title;";
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


