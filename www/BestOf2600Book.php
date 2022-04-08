<!--
  status.php

  Author:      wrepp
  Date:        01/09/2010
  Description: Best of 2600 Book information for 2600 index web site
  Notes:
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php

/* database info */
  include ("db.php");

/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);
  
/* query parameters */
  $o = @$_GET["o"];
  if ($o == "")  $o = "byDate";

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
    
    <p class="section">Best of 2600 Book</p>
    
    <a href="#Chapters">see Chapters</a><br>
    <a href="#Articles">see Articles</a><br>
    
    <h3>Notes</h3>
    Complete articles are not always included in the book. See Summer 1987 [14:2] the News Summary/News Items <a href='article.php?i=38&amp;a=424'>..</a>, Spring 1998 News Update/News Items [14:1] <a href='article.php?i=41&amp;a=466'>..</a>, Spring 1991 What's Up?/EFF Lawsuit [8:1] <a href='article.php?i=13&amp;a=104'>..</a>, Spring 1998 [15:1] Message Sent/Our Financial State <a href='article.php?i=41&amp;a=450'>..</a>, Winter 1998 [15:4] The Victor Spoiled/Mitnick Update <a href='article.php?i=44&amp;a=501'>..</a>, News Items Summer 1995 [14:2] <a href='article.php?i=30&amp;a=311'>..</a>, Autumn 1998 [15:3] Progress <a href='article.php?i=44&amp;a=484'>..</a>
    <br>
    <br>
<?php
    echo "<h3>Stats</h3>";
    include "BestOf2600Book_Stats.html";

    echo '<h3 id="Chapters" name="Chapters">Chapters</h3>';
    include "BestOf2600Book_Index.html";

    echo '<h3 id="Articles" name="Articles">Articles</h3>';
    if ($o == "byDate")  {
      echo '<a href="BestOf2600Book.php?o=byPage">Sort by Page</a>';
      include "BestOf2600Book_byDate.html";
    }
    if ($o == "byPage")  {
      echo '<a href="BestOf2600Book.php?o=byDate">Sort by Date</a>';
      include "BestOf2600Book_byPage.html";
    }
?>
    
  </div>

<?php
  mysqli_close ($conn);
?>

  <div id="foot">
    <?php include ("footer.php"); ?>
  </div>

  </body>
</html>
