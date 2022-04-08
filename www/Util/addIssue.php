<!--
  addIssue.php

  Author:      wrepp
  Date:        01/13/2010
  Description: add issue and articles for 2600 index web site
  Notes:

  05/18/2010 wre split -> preg_split.
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php
  $path = "../";

  include ($path . "db.php");

/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);
?>

<html>
  <head>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8">  
  <title>2600 Index Status</title>
  <?php include ($path . "header.php"); ?>
  </head>

  <body>

  <?php include ($path . "page.php"); ?>

  <div id="content">

    <p class="section">Add Issue</p>

<?php

  /* post back? */
    if (!isset($_POST['add']))  {
?>
      <form method="post" action="">

        <table width="65%" cellspacing="1px" cellpadding="2px" >
            <td bgcolor="lightblue" width="18%">Period</td>
            <td bgcolor="honeydew">
              <input id="Period" name="Period" length="9" maxlength="9" type="text" style="width:150px" value="" />
            </td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Issue Year</td>
            <td bgcolor="honeydew">
              <input id="IssueYear" name="IssueYear" length="4" maxlength="4" type="text" style="width:80px" value="" />
            </td>
          </tr>
            <td bgcolor="lightblue" width="18%">Quarter (Period)</td>
            <td bgcolor="honeydew">
              <input id="Quarter" name="Quarter" type="text" length="4" maxlength="4" style="width:50px" value="">
            </td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Volume</td>
            <td bgcolor="honeydew">
              <input id="Volume" name="Volume" type="text" length="4" maxlength="4" style="width:50px" value="">
            </td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Pages</td>
            <td bgcolor="honeydew">
              <input id="Pages" name="Pages" type="text" length="4" maxlength="4" style="width:50px" value="">
            </td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Articles  (Titles)</td>
            <td bgcolor="honeydew">
              <textarea id="Articles" name="Articles" cols="70" rows="10" maxlength="1024"></textarea>
            </td>
          </tr>
        </table>

        <p>
        <input type="submit" id="add" name="add" value="Add...">
        </p>
      </form>
<?php
    }
    else  {

      echo "<p>add!<p>";

      $Period = $_POST["Period"];
      $IssueYear = $_POST["IssueYear"];
      $Quarter = $_POST["Quarter"];
      $Volume = $_POST["Volume"];
      $Pages = $_POST["Pages"];
      $Articles = $_POST["Articles"];
      
      echo "Period=" . $Period . "<br>";
      echo "IssueYear=" . $IssueYear . "<br>";
      echo "Quarter=" . $Quarter . "<br>";
      echo "Volume=" . $Volume . "<br>";
      echo "Pages=" . $Pages . "<br>";
      
      //$articles = split ("\n", $Articles);
      $articles = preg_split ("/\n/", $Articles);
      for ($i = 0; $i < count ($articles); $i++)  {
        if (strlen ($articles[$i]) > 0)  {
          echo "article[" . $i . "]=" . $articles[$i] . "=<br>";
        }
      }
      echo "Articles=" . count($articles) . "<br>";

    /* validation */
      if (
          (strlen ($Period) < 1)  ||
          ($IssueYear < 1984 || $IssueYear > 2050) ||
          ($Quarter < 1 || $Quarter > 12) ||
          ($Volume < 1 || $Volume > 50) ||
          ($Pages < 1 || $Pages > 100) ||
          (count($articles) == 1 && strlen ($articles[1]) < 1)
         )  die ("<p class='red'>Invalid parameter!</p>");
         
    /* turn off auto-commit - ie. transaction */
      mysqli_autocommit ($conn, FALSE);

    /* make sure issue is not already on file */
      $sql = "SELECT * " .
             "FROM Issues " .
             "WHERE IssueYear = " . $IssueYear . " " .
             "  AND Period = '" . $Period . "'";
      echo $sql . "<br>";
      $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
      if ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  $IssuesID = $row['IssuesID'];
      else  $IssuesID = 0;

      if ($IssuesID != 0)  {
?>
        <p class='red'>!!!Already on file!!! <br></p>
<?php
      }
      else  {
      
      /* build front/back issue files names */
        $FrontCoverLink = "FrontCoverImages/" . $Period . $IssueYear . ".gif";
        $BackCoverLink = "BackCoverImages/" . $Period . $IssueYear . ".gif";
        
      /* add issue, get IssusesID */
        $sql = "INSERT INTO Issues (Period, IssueYear, CoverLink, BackCoverLink, Quarter, Volume, Pages) " .
               "VALUES ('" . $Period . "'," . $IssueYear . ",'" . $FrontCoverLink . "','" . $BackCoverLink . "'," .
                        $Quarter . "," . $Volume . "," . $Pages . ")";
        echo $sql . "<br>";
        $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
        $IssuesID =  mysqli_insert_id ($conn) or die (mysqli_error ($conn));
        
      /* add articles */
        for ($i = 0; $i < count ($articles); $i++)  {
          if (strlen ($articles[$i]) > 0)  {
            $sql = "INSERT INTO Articles (IssuesID, AuthorsID, Title, Synopsis) " .
                  "VALUES (" . $IssuesID . ",1,'" . $articles[$i] . "','')";
            echo $sql . "<br>";
            $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
          }
        }
      }
    /* testing */
      #mysqli_rollback ($conn);  // testing!!!
      mysqli_commit ($conn);
    /* testing */

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
