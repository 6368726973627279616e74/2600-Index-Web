<!--
  Links.php

  Author:      wrepp
  Date:        12/02/2008
  Description: links report
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
  if ($o == "")  $o = "NotValidated";

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

    <p class="section">Links</p>

    Option &nbsp;
    <select id="Option" name="Option" onchange="window.location = 'Links.php?o=' + '' + this.value + ''">
      <option value="Err" <?php if ($o == "Err")  echo "selected";?> >Errors</option>
      <option value="NotValidated" <?php if ($o == "NotValidated")  echo "selected";?> >Not Validated</option>
      <option value="Updated" <?php if ($o == "Updated")  echo "selected";?> >Updated</option>
      <option value="Archive" <?php if ($o == "Archive")  echo "selected";?> >Archive</option>
      <option value="Addendum" <?php if ($o == "Addendum")  echo "selected";?> >Addendum</option>
      <option value="Indexed" <?php if ($o == "Indexed")  echo "selected";?> >Indexed</option>
    </select>

  <table width="95%" cellspacing="1px" cellpadding="2px" >
  <thead>
  <tr bgcolor="LIGHTGREY">
    <td>Issue</td>
    <td>Article Title</td>
    <td>Status</td>
    <td>Updated?</td>
    <td>Link</td>
    <td>Validated</td>
    <td>..</td>
  </tr>
  </thead>

  <?php
    $sql = "SELECT *, DATE_FORMAT(Validated, '%m/%d/%Y') AS 'ValidatedFormat' " .
           "FROM Links l " .
           "LEFT JOIN vw_ArticleInfo ai ON l.ArticlesID = ai.ArticlesID ";
    switch ($o) {
      case "Err" :
           $sql .= "WHERE l.Status = 'Err' ";
           break;
      case "NotValidated" :
           $sql .= "WHERE l.Status = '?' ";
           break;
      case "Updated" :
           $sql .= "WHERE l.Updated = 'Y' ";
           break;
      case "Archive" :
           $sql .= "WHERE l.ArchiveLink IS NOT NULL ";
           break;
      case "Addendum" :
           $sql .= "WHERE l.Type = 'A' ";
           break;
      case "Indexed" :
           $sql .= "WHERE l.Type = 'I' ";
           break;
    };
    $sql .= "ORDER BY l.Status, ai.IssueYear, ai.`Quarter`, ai.Title;";
    #echo $sql;
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $nbr = mysqli_num_rows($result);
    if ($nbr > 0)  echo $nbr . " found.";

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
        <td align="center">
          <?php echo $row['Status'] ?>
        </td>
        <td align="center">
          <?php echo $row['Updated'] ?>
        </td>
        <td>
          <a href="<?php echo $row['Link'] ?>">
            <?php echo $row['Link'] ?>
          </a>
        </td>
        <td>
          <?php echo $row['ValidatedFormat'] ?>
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


