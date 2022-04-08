<!--
  writeStats.php

  Author:      wrepp
  Date:        01/09/2010
  Description: write BestOf2600Book.html for 2600 index web site
  Notes:
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php
function showIssue ($row) {
  return $row['Period'] . " " . $row['IssueYear'] . " [" . $row['Volume'] . ":" . $row['Quarter'] . "]";
}

function writeBestOf2600Book ($fh, $conn, $opt)  {

  #fwrite ($fh, "<br>\n");
      
/* articles */        
  $sql = "SELECT IssuesID, ArticlesID, Title, PageTitle, Volume, Quarter, IssueYear, Period, PageIn2600Book " .
          "FROM vw_ArticleInfo " .
          "WHERE PageIn2600Book != 0 ";
  #echo $opt . "<br>";
  if ($opt == "byDate")  $sql .= "ORDER BY Volume, Quarter";
  if ($opt == "byPage")  $sql .= "ORDER BY PageIn2600Book";
  #echo $sql . "<br>";
  $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
  
  fwrite ($fh, "<br>\n");
  
/* display results */
  fwrite ($fh, '<table name="tblBestOf2600Book" id="tblBestOf2600Book" width="95%" cellspacing="1px" cellpadding="2px" >' . "\n");
  fwrite ($fh, "<thead>");
  fwrite ($fh, '  <tr bgcolor="lightgrey">');
  fwrite ($fh, "    <td>");
  fwrite ($fh, "      Year");
  fwrite ($fh, "    </td>");
  fwrite ($fh, "    <td>");
  fwrite ($fh, "      Period");
  fwrite ($fh, "    </td>");
  fwrite ($fh, "    <td>");
  fwrite ($fh, "      Issue");
  fwrite ($fh, "    </td>");
  fwrite ($fh, "    <td>");
  fwrite ($fh, "      Title (from Index)");
  fwrite ($fh, "    </td>");
  fwrite ($fh, "    <td>");
  fwrite ($fh, "      Page Title (if different - used by Best of 2600 Book)");
  fwrite ($fh, "    </td>");
  fwrite ($fh, "    <td>");
  fwrite ($fh, "      Page");
  fwrite ($fh, "    </td>");
  fwrite ($fh, "    <td>");
  fwrite ($fh, "      ..");
  fwrite ($fh, "    </td>");
  fwrite ($fh, "  </tr>");
  fwrite ($fh, "</thead>");

  $inbr = 0;
  while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {

    fwrite ($fh, "<tr bgcolor="); if ($inbr % 2) fwrite ($fh, "palegreen"); else fwrite ($fh, "honeydew"); fwrite ($fh, ">\n");
    fwrite ($fh, "  <td>");
    fwrite ($fh, $row['IssueYear']);
    fwrite ($fh, "  </td>");
    fwrite ($fh, "  <td>");
    fwrite ($fh, $row['Period']);
    fwrite ($fh, "  </td>");
    fwrite ($fh, "  <td>");
    fwrite ($fh, "[" . $row['Volume'] . ":" . $row['Quarter'] . "]");
    fwrite ($fh, "  </td>");
    fwrite ($fh, "  <td>");
    fwrite ($fh, $row['Title']);
    fwrite ($fh, "  </td>");
    fwrite ($fh, "  <td>");
    fwrite ($fh, $row['PageTitle']);
    fwrite ($fh, "  </td>");
    fwrite ($fh, "  <td>");
    fwrite ($fh, $row['PageIn2600Book']);
    fwrite ($fh, "  </td>");
    fwrite ($fh, "  <td>");
// http://localhost/2600/articles.php?i=<IssuesID>&a=<ArticlesID>
    fwrite ($fh, '    <a href="article.php?i=' . $row['IssuesID'] . '&a=' . $row['ArticlesID'] . '">..</a>');
    fwrite ($fh, "  </td>");
    fwrite ($fh, "</tr>\n");
    $inbr++;
  }
  
  fwrite ($fh, "</table>\n");

  #fwrite ($fh, "<br>" . "\n");
    
/* done */
  fclose ($fh);
}  // writeBestOf2600Book
?>

<?php
  $path = "../";

/* database info */
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

    <p class="section">Write Stats</p>

<?php
  /*************************/
  /* section/chapter index */
    $fh = fopen ("../BestOf2600Book_Index.html", "w");
    
    #fwrite ($fh, "<br>\n");

  /* book sections/chapters */
    $sql = "SELECT s.Number AS 'Section', s.Name as 'SectionName', " .
            "       c.Number AS 'Chapter', c.Name as 'ChapterName', c.Page " .
            "FROM Chapters c " .
            "LEFT JOIN Sections s ON c.SectionsID = s.SectionsID " .
            "ORDER BY c.Page;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    fwrite ($fh, '<table name="tblContents" id="tblContents" width="55%" cellspacing="1px" cellpadding="2px" >' . "\n");
    fwrite ($fh, "<thead>");
    fwrite ($fh, '  <tr bgcolor="lightgrey">');
    fwrite ($fh, "    <td>");
    fwrite ($fh, "      Section");
    fwrite ($fh, "    </td>");
    fwrite ($fh, "    <td>");
    fwrite ($fh, "      Chapter");
    fwrite ($fh, "    </td>");
    fwrite ($fh, "    <td>");
    fwrite ($fh, "      Page");
    fwrite ($fh, "    </td>");
    fwrite ($fh, "  </tr>");
    fwrite ($fh, "</thead>");
    
    $inbr = 0;
    $lastSection = "";
    $Section = "";
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
      fwrite ($fh, "<tr bgcolor="); if ($inbr % 2) fwrite ($fh, "palegreen"); else fwrite ($fh, "honeydew"); fwrite ($fh, ">\n");
      fwrite ($fh, "  <td>");
      switch ($row['Section'])  {
        case 1 :
          $Section = "I";
          break;
        case 2 :
          $Section = "II";
          break;
        case 3 :
          $Section = "III";
          break;
      }      
      $Section .= " - " . $row['SectionName'];
      if ($Section != $lastSection)  {
        fwrite ($fh, $Section);
        $lastSection = $Section;
      }
      else  {
        fwrite ($fh, " ");
      }
      fwrite ($fh, "  </td>");
      fwrite ($fh, "  <td>");
      fwrite ($fh, $row['Chapter'] . ". " . $row['ChapterName']);
      fwrite ($fh, "  </td>");
      fwrite ($fh, "  <td>");
      fwrite ($fh, $row['Page']);
      fwrite ($fh, "  </td>");
      $inbr++;
    }
    fwrite ($fh, "</table>\n");
    
    #fwrite ($fh, "<br>\n");
    fclose ($fh);


  /**************/
  /* book stats */
    $fh = fopen ("../BestOf2600Book_Stats.html", "w");

    fwrite ($fh, "First edition published in 2008.<br>");
    
    fwrite ($fh, "834 pages of articles and commentary, 36 pages in the index (835-871).<br>");

  /* number of articles, first/last issue */
    $sql = "SELECT COUNT(*) AS 'Nbr' " .
           "FROM vw_ArticleInfo " .
           "WHERE PageIn2600Book != 0 ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    
    fwrite ($fh, "Number of articles: " . $row['Nbr'] . ",  ");
    
    #fwrite ($fh, "Number of articles: " . $row['Nbr'] . "  (so far - I'm on ");
/*
    $sql = "SELECT MAX(PageIn2600Book) AS 'PageIn2600Book' " .
           "FROM Articles";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    
    fwrite ($fh, "page " . $row['PageIn2600Book'] . "  of 831 or " . number_format ((($row['PageIn2600Book'] / 831)*100), 2) . "%) ");
*/    
    $sql = "SELECT Volume, Quarter, IssueYear, Period " .
           "FROM vw_ArticleInfo " .
           "WHERE PageIn2600Book != 0 " .
           "ORDER BY Volume ASC, Quarter ASC " .
           "LIMIT 1";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    $first = showIssue ($row);

    $sql = "SELECT Volume, Quarter, IssueYear, Period " .
           "FROM vw_ArticleInfo " .
           "WHERE PageIn2600Book != 0 " .
           "ORDER BY Volume DESC, Quarter DESC " .
           "LIMIT 1";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    $last = showIssue ($row);

    fwrite ($fh, "from " . $first . " thru " . $last . ".");

    fwrite ($fh, "<br>" . "\n");
    
  /* number of authors */
    $sql = "SELECT DISTINCT AuthorsID " .
           "FROM Articles " .
           "WHERE PageIn2600Book != 0 ";
    #echo $sql . "<br>";

    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
    $nbr = mysqli_num_rows ($result);
    fwrite ($fh, $nbr . " different authors contributed.<br>");

  /* top ten authors */
    $sql = "SELECT a.AuthorsID, COUNT(*) AS 'Nbr', au.NomDePlume " .
           "FROM Articles a " .
           "LEFT JOIN Authors au ON a.AuthorsID = au.AuthorsID " .
           "WHERE a.PageIn2600Book != 0 " .
           "GROUP BY a.AuthorsID " .
           "ORDER BY Nbr DESC, au.NomDePlume " .
           "LIMIT 10";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

  /* display results */
    fwrite ($fh, "Top ten authors by articles written (ties in alphabetical order):");
    fwrite ($fh, '<table width="25%" cellspacing="1px" cellpadding="2px" >');
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {

      fwrite ($fh, "<tr bgcolor="); if ($inbr % 2) fwrite ($fh, "palegreen"); else fwrite ($fh, "honeydew"); fwrite ($fh, ">");
        fwrite ($fh, "<td>");
          fwrite ($fh, $row['NomDePlume']);
        fwrite ($fh, "</td>");
        fwrite ($fh, "<td>");
          fwrite ($fh, $row['Nbr']);
        fwrite ($fh, "</td>");
      fwrite ($fh, "</tr>");
      $inbr++;
    }
    fwrite ($fh, "</table><br>");

  /* top ten keywords */
    $sql = "SELECT IFNULL(k.Keyword, 'Not Assigned') AS 'Keyword', COUNT(*) AS 'Nbr' " .
           "FROM Articles a " .
           "LEFT JOIN KeywordXref kx ON a.ArticlesID = kx.ArticlesID " .
           "LEFT JOIN Keywords k ON kx.KeywordsID = k.KeywordsID " .
           "WHERE a.PageIn2600Book != 0 " .
           "GROUP BY kx.KeywordsID " .
           "ORDER BY Nbr DESC, k.Keyword " .
           "LIMIT 10;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

  /* display results */
    fwrite ($fh, "Top ten keywords:");
    fwrite ($fh, '<table width="25%" cellspacing="1px" cellpadding="2px" >');
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {

      fwrite ($fh, "<tr bgcolor="); if ($inbr % 2) fwrite ($fh, "palegreen"); else fwrite ($fh, "honeydew"); fwrite ($fh, ">");
        fwrite ($fh, "<td>");
          fwrite ($fh, $row['Keyword']);
        fwrite ($fh, "</td>");
        fwrite ($fh, "<td>");
          fwrite ($fh, $row['Nbr']);
        fwrite ($fh, "</td>");
      fwrite ($fh, "</tr>");
      $inbr++;
    }
    fwrite ($fh, "</table><br>");


  /* book stats done */
    #fwrite ($fh, "<br>\n");
    fclose ($fh);
 

  /*******************************/
  /* article info sorted by date */
    $fh = fopen ("../BestOf2600Book_byDate.html", "w");
    writeBestOf2600Book ($fh, $conn, "byDate");


  /*******************************/
  /* article info sorted by page */
    $fh = fopen ("../BestOf2600Book_byPage.html", "w");
    writeBestOf2600Book ($fh, $conn, "byPage");


  /**************/
    echo "<br> Done! <br><br>";
?>

    <a href=<?php echo $path ?>BestOf2600Book_Index.html>BestOf2600Book_Index.html</a>
    <br>
    <a href=<?php echo $path ?>BestOf2600Book_Stats.html>BestOf2600Book_Stats.html</a>
    <br>
    <a href=<?php echo $path ?>BestOf2600Book_byDate.html>BestOf2600Book_byDate.html</a>
    <br>
    <a href=<?php echo $path ?>BestOf2600Book_byPage.html>BestOf2600Book_byPage.html</a>
    
  </div>

<?php
  mysqli_close ($conn);
?>

  <div id="foot">
    <?php include ($path . "footer.php"); ?>
  </div>

  </body>
</html>
