<!--
  status.php

  Author:      wrepp
  Date:        10/13/2008
  Description: status page for 2600 index web site
  Notes:       table editors only available if sub-directory found
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php
function showIssue ($row) {
  return $row['Period'] . " " . $row['IssueYear'] . " [" . $row['Volume'] . ":" . $row['Quarter'] . "]";
}

  include ("db.php");

/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);
?>

<html>
  <head>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8">  
  <title>2600 Index Status</title>
  <?php include ("header.php"); ?>
  </head>

  <body>

  <?php include ("page.php"); ?>

  <div id="content">
    <noscript>
    <p class='red'>This page requires Javascript to function properly!!</p>
    </noscript>

    <script language="javascript" type="text/javascript">
    // querystring to POST JavaScript
    // copyright 15th October 2007 by Stephen Chapman
    // permission to use this Javascript on your web page is granted
    // provided that all of the code in this script (including these
    // comments) is used without any alteration
    // see http://javascript.about.com/library/bltopost.htm
    function toPost(getString) {
      var parms = getString.split('?');
      var newF = document.createElement("form");
      newF.action = parms[0];
      newF.method = 'POST';
      var parms = parms[1].split('&');
      for (var i=0; i<parms.length; i++) {
        var pos = parms[i].indexOf('=');
        if (pos > 0) {
          var key = parms[i].substring(0,pos);
          var val = parms[i].substring(pos+1);  /*@cc_on @if (@_jscript)  var newH = document.createElement("<input name='"+key+"'>");  @else */
          var newH = document.createElement("input");
          newH.name = key; /* @end @*/
          newH.type = 'hidden';
          newH.value = val;
          newF.appendChild(newH);
        }
      }
      document.getElementsByTagName('body')[0].appendChild(newF);
      newF.submit();
    }
    </script>
    
    <p class="section">Status</p>

    <p>
     This web site is a work in progress, issues are being indexed from latest to earliest.<br>
     Issues from 1984 thru 1987 are not in the database.<br>
     The current issue will be indexed as soon as possible - please be patient as the initial info (cover jpg, etc) comes from <a href="http://store.2600.com/backissues.html">store.2600.com</a> and I also need time to receive, read and write the article information and update the host server.
    </p>

    <p class="section">Database</p>

    &nbsp;From issue
<?php
    $sql = "SELECT * FROM Issues ORDER BY IssueYear ASC, `Quarter` ASC LIMIT 1;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo showIssue($row);  //$row["Period"] . " " . $row['IssueYear'];
?>
    thru
<?php
    $sql = "SELECT * FROM Issues ORDER BY IssueYear DESC, `Quarter` DESC LIMIT 1;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo showIssue($row);  //$row["Period"] . " " . $row['IssueYear'] . ".";
?>
    <br>

    <br>
<?php
    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Issues;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    $nbrissue = $row['Nbr'];

    $sql = "SELECT COUNT(*) AS 'Nbr' " .
           "FROM Issues " .
           "WHERE Quote IS NOT NULL AND TRIM(Quote) != '';";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    $nbrquote = $row['Nbr'];

    echo "&nbsp;Number of issues: " . $nbrissue . " &nbsp;&nbsp;(" . $nbrquote . " with quote).";

?>
    <br>

    <br>
<?php
    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Articles;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    $nbrarticles = $row['Nbr'];
    echo "&nbsp;Number of articles: " . $nbrarticles . ".";
?>
    <br>

    <br>
<?php
    $sql = "SELECT COUNT(*) AS 'Nbr', " .
           "       (SELECT COUNT(*) FROM Keywords WHERE WikipediaLink IS NOT NULL) AS 'NbrLink' " .
           "FROM Keywords;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Number of keywords: " . $row['Nbr'] . "&nbsp; &nbsp (" . $row['NbrLink'] . " with link).";
?>
    <br>

    <br>
<?php
    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Authors;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Number of authors: " . $row['Nbr'] . ".";
?>
    <br>

    <br>
<?php
    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Links;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Number of links: " . $row['Nbr'] . ".";
/*
    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Links WHERE Status = 'Err';";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    $nbrErrors = $row['Nbr'];

    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Links WHERE Updated = 'Y'";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    $nbrUpdated = $row['Nbr'];

    echo "&nbsp;&nbsp;&nbsp;(" . $nbrUpdated . " updated, " . $nbrErrors . " with errors)";
*/
    $sql = "SELECT COUNT(*) AS 'Nbr', " .
           "SUM(IF (Status = 'Err', 1, 0)) AS 'NbrErrors', " .
           "SUM(IF (Updated = 'Y', 1, 0)) AS 'NbrUpdated', " .
           "SUM(IF (ArchiveLink IS NOT NULL, 1, 0)) AS 'NbrArchiveLink', " .
           "SUM(IF (LocalCopy = 'Y', 1, 0)) AS 'NbrLocalCopy', " .
           "SUM(IF (Type = 'A', 1, 0)) AS 'NbrAddendum', " .
           "SUM(IF (Type = 'I', 1, 0)) AS 'NbrIndex' " .
           "FROM Links;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    
    echo "&nbsp;&nbsp;&nbsp;(" . $row['NbrUpdated'] . " updated, " . $row['NbrErrors'] . " with an error, " . 
         $row['NbrArchiveLink'] . " with Archive.org link, " . $row['NbrLocalCopy'] . " with local copy, " .
         $row['NbrAddendum'] . " addendum link, " . $row['NbrIndex'] . " added when indexed)";
?>
    <br>

    <br>
<?php
    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Addendums;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Number of addendum: " . $row['Nbr'] . ".";
?>
    <br>

    <br>
<?php
    $sql = "SELECT COUNT(*) AS 'Nbr' FROM KeywordXref;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Number of keyword/article cross references: " . $row['Nbr'] . ".";
?>
    <br>

    <br>
<?php
    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Articles WHERE AuthorsID != 1;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Number of articles indexed: " . $row['Nbr'] . " or " . number_format ((($row['Nbr'] / $nbrarticles)*100), 2) . "%.";
?>
    <br>

    <br>
<?php
    $sql = "SELECT COUNT(*) AS 'Nbr' " .
           "FROM (SELECT a.IssuesID, COUNT(*) AS 'Nbr', " .
           "             (SELECT COUNT(*) " .
           "              FROM Articles a1 " .
           "              WHERE a.IssuesID = a1.IssuesID " .
           "                AND a1.AuthorsID != 1) AS 'Indexed' ".
           "FROM Articles a " .
           "GROUP BY IssuesID) AS t " .
           "WHERE t.Nbr = t.Indexed";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Number of issues indexed: " . $row['Nbr'] . " or " . number_format ((($row['Nbr'] / $nbrissue)*100), 2) . "%.";
?>
    <br>

    <br>
<?php
    $sql = "SELECT t.NbrArticle, t.NbrIssue, t.NbrArticle / t.NbrIssue AS 'AvgArticles' " .
           "FROM  (SELECT (SELECT COUNT(*) " .
           "               FROM Articles) AS 'NbrArticle', " .
           "              (SELECT COUNT(*) " .
           "               FROM Issues) AS 'NbrIssue') AS t";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Average number of articles per issue (all): " . number_format ($row['AvgArticles'], 1) . ".";

?>
    <br>
<!-- 01/13/2010 update -->
    <br>
<?php
    $sql = "SELECT t.NbrArticle, t.NbrIssue, t.NbrArticle / t.NbrIssue AS 'AvgArticles' " .
           "FROM  (SELECT (SELECT COUNT(*) " .
           "               FROM Articles a, " .
           "                    Issues i " .
           "               WHERE a.IssuesID = i.IssuesID " .
           "                 AND i.IssueYear < 1988) AS 'NbrArticle', " .
           "              (SELECT COUNT(*) " .
           "               FROM Issues " .
           "               WHERE IssueYear < 1988) AS 'NbrIssue' " .
           "      ) AS t";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Average number of articles per issue (before 1988 when 2600 was a monthly): " . number_format ($row['AvgArticles'], 1) . ".";
?>
    <br>
    
    <br>
<?php
    $sql = "SELECT t.NbrArticle, t.NbrIssue, t.NbrArticle / t.NbrIssue AS 'AvgArticles' " .
           "FROM  (SELECT (SELECT COUNT(*) " .
           "               FROM Articles a, " .
           "                    Issues i " .
           "               WHERE a.IssuesID = i.IssuesID " .
           "                 AND i.IssueYear >= 1988) AS 'NbrArticle', " .
           "              (SELECT COUNT(*) " .
           "               FROM Issues " .
           "               WHERE IssueYear >= 1988) AS 'NbrIssue' " .
           "      ) AS t";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Average number of articles per issue (since 1988 when 2600 became a quarterly): " . number_format ($row['AvgArticles'], 1) . ".";
?>
    <br>    
<!-- 01/13/2010 update -->

    <br>
<?php
    $sql = "SELECT t.NbrArticle, t.NbrIssue, t.NbrArticle / t.NbrIssue AS 'AvgArticles' " .
           "FROM  (SELECT (SELECT COUNT(*) " .
           "               FROM Articles a, " .
           "                    Issues i " .
           "               WHERE a.IssuesID = i.IssuesID " .
           "                 AND i.IssueYear > YEAR(CURDATE())- 5) AS 'NbrArticle', " .
           "              (SELECT COUNT(*) " .
           "               FROM Issues " .
           "               WHERE IssueYear > YEAR(CURDATE())- 5) AS 'NbrIssue' " .
           "      ) AS t";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Average number of articles per issue (last 5 years): " . number_format ($row['AvgArticles'], 1) . ".";

?>
    <br>

    <br>
<?php
    $sql = "SELECT Issue, COUNT(*) AS 'Nbr', Period, IssueYear, Volume, Quarter " .
           "FROM vw_ArticleInfo " .
           "GROUP BY Issue " .
           "ORDER BY Nbr DESC, IssueYear DESC " .
           "LIMIT 1";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Issue with the most articles (latest one): " . showIssue($row) . " (". $row['Nbr'] . ").";

?>
    <br>

    <br>
<?php
    $sql = "SELECT AVG(Pages) AS 'AvgPages' " . 
           "FROM Issues ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Average number of pages per issue (all): " . number_format ($row['AvgPages'], 1) . ".";

?>
    <br>
    
<!-- 01/13/2010 update -->
    <br>
<?php
    $sql = "SELECT AVG(Pages) AS 'AvgPages' " . 
           "FROM Issues " .
           "WHERE IssueYear < 1988 ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Average number of pages per issue (before 1988 when 2600 was a monthly): " . number_format ($row['AvgPages'], 1) . ".";
?>
    <br>
    <br>
<?php
    $sql = "SELECT AVG(Pages) AS 'AvgPages' " . 
           "FROM Issues " .
           "WHERE IssueYear >= 1988 ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Average number of pages per issue (since 1988 when 2600 became a quarterly): " . number_format ($row['AvgPages'], 1) . ".";    
?>
    <br>
    
<!-- 01/13/2010 update -->

    <br>
<?php
    $sql = "SELECT AVG(Pages) AS 'AvgPages' " . 
           "FROM Issues " .
           "WHERE IssueYear > YEAR(CURDATE()) - 5 ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Average number of pages per issue (last 5 years): " . number_format ($row['AvgPages'], 1) . ".";

?>
    <br>

    <br>
<?php
    $sql = "SELECT Pages, Issue, Period, IssueYear, Volume, Quarter " .
           "FROM vw_Issues i " .
           "ORDER BY Pages DESC, IssueYear DESC, Quarter DESC ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Issue with most pages (latest one): " . showIssue($row) . " (". $row['Pages'] . ").";

?>
    <br>

    <br>
<?php
    $sql = "SELECT l.ArticlesID, COUNT(*) AS Nbr, a.Title, " .
           "       i.IssueYear, i.Period, i.Volume, i.Quarter " .
           "FROM Links l, " .
           "     Articles a, " .
           "     Issues i " .
           "WHERE l.ArticlesID = a.ArticlesID " .
           "  AND a.IssuesID = i.IssuesID " .
           "GROUP BY l.ArticlesID " .
           "ORDER BY Nbr DESC, i.IssueYear DESC, i.Quarter DESC ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Article with most links (latest one): '" . $row['Title'] . "'  " . showIssue($row) . " (". $row['Nbr'] . ").";

?>
    <br>

    <br>
<?php
    $sql = "SELECT i.IssuesID, COUNT(*) AS Nbr, i.IssueYear, i.Period, i.Volume, i.Quarter " .
           "FROM Links l, " .
           "     Articles a, " .
           "     Issues i " .
           "WHERE l.ArticlesID = a.ArticlesID " .
           "  AND a.IssuesID = i.IssuesID " .
           "GROUP BY i.IssuesID " .
           "ORDER BY Nbr DESC, i.IssueYear DESC, i.Quarter DESC ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    echo "&nbsp;Issue with most links (latest one): " . showIssue($row) . " (". $row['Nbr'] . ").";

?>
    <br>

    <br>
<?php
    /* Ignore authors: 1=Not Assigned, 2=Emmanuel Goldstein
       15=Readers, 346 = No Author Specified
    */
    $sql = "SELECT a.AuthorsID, COUNT(*) AS 'Nbr', au.NomDePlume " .
           "FROM Articles a " .
           "LEFT JOIN Authors au ON a.AuthorsID = au.AuthorsID " .
           "WHERE a.AuthorsID NOT IN (1, 2, 15, 346) " .
           "GROUP BY a.AuthorsID " .
           "ORDER BY Nbr DESC, au.NomDePlume " .
           "LIMIT 10";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

  /* display results */
    echo "&nbsp;Top ten authors by articles written (duplicates in alphabetical order):";
    echo '<table width="25%" cellspacing="1px" cellpadding="2px" >';
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
?>
      <tr bgcolor=<?php if ($inbr % 2)  echo "palegreen"; else  echo "honeydew";?> >
        <td>
          <?php echo $row['NomDePlume'] ?>
        </td>
        <td>
          <?php echo $row['Nbr'] ?>
        </td>
        <td>
          <a href="search.php?search=search&amp;FromYear=0&amp;ToYear=9999&amp;Author=<?php echo $row['AuthorsID']?>" onclick="toPost(this.href); return false;">..</a>
        </td>
      </tr>
<?php
      $inbr++;
    }
    echo "</table>";
?>
    <br>

    <p class="note">(Note: I add a Letters article to each issue as I index)</p>
  </div>

<?php
  mysqli_close ($conn);
?>

  <div id="foot">
    <?php include ("footer.php"); ?>
  </div>

  </body>
</html>
