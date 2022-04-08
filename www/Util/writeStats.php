<!--
  writeStats.php

  Author:      wrepp
  Date:        12/30/2009
  Description: status page for 2600 index web site
  Notes:       started from status.php
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php
function showIssue ($row) {
  return $row['Period'] . " " . $row['IssueYear'] . " [" . $row['Volume'] . ":" . $row['Quarter'] . "]";
}

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

    <p class="section">Write Stats</p>

<?php
    $fh = fopen ("../stats.html", "w");

    fwrite ($fh, "Status as of : " . date ("m/d/Y h:i a T"));
    
    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");


    fwrite ($fh, "&nbsp;From issue ");

    $sql = "SELECT * FROM Issues ORDER BY IssueYear ASC, `Quarter` ASC LIMIT 1;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, showIssue ($row));

    fwrite ($fh, " thru ");

    $sql = "SELECT * FROM Issues ORDER BY IssueYear DESC, `Quarter` DESC LIMIT 1;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, showIssue ($row));

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

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

    fwrite ($fh, "&nbsp;Number of issues: " . $nbrissue . " &nbsp;&nbsp;(" . $nbrquote . " with quote)");

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Articles;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    $nbrarticles = $row['Nbr'];
    fwrite ($fh, "&nbsp;Number of articles: " . $nbrarticles);

    $sql = "SELECT COUNT(*) AS 'Nbr' " .
           "FROM Articles " .
           "WHERE PageTitle != Title " .
           "  AND PageTitle != '';";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, " &nbsp;&nbsp;(" . $row['Nbr'] . " with title different than index)");

    fwrite ($fh, "<br>" . "\n");
/*
    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT COUNT(*) AS 'Nbr' " .
           "FROM vw_ArticleInfo " .
           "WHERE PageIn2600Book != 0 ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    
    fwrite ($fh, "Number of artices in Best of 2600 Book: " . $row['Nbr'] . "  (so far - I'm on ");

    $sql = "SELECT MAX(PageIn2600Book) AS 'PageIn2600Book' " .
           "FROM Articles";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    
    fwrite ($fh, "page " . $row['PageIn2600Book'] . "  of 831 or " . number_format ((($row['PageIn2600Book'] / 831)*100), 2) . "%) ");

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

    fwrite ($fh, "From " . $first . " thru " . $last . ".");

    fwrite ($fh, "<br>" . "\n");
*/
    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT COUNT(*) AS 'Nbr', " .
           "       (SELECT COUNT(*) FROM Keywords WHERE WikipediaLink IS NOT NULL) AS 'NbrLink' " .
           "FROM Keywords;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Number of keywords: " . $row['Nbr'] . "&nbsp; &nbsp; (" . $row['NbrLink'] . " with link)");

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Authors;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Number of authors: " . $row['Nbr']);
    
    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Authors WHERE Name != '';";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp; &nbsp; (" . $row['Nbr'] . " with name,");

    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Authors WHERE Email != '';";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp; " . $row['Nbr'] . " with email)");

    #fwrite ($fh, ".");
    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Links;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Number of links: " . $row['Nbr']);

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
    
    fwrite ($fh, "&nbsp;&nbsp;&nbsp;(" . $row['NbrUpdated'] . " updated, " . $row['NbrErrors'] . " with an error, " . 
                 $row['NbrArchiveLink'] . " with Archive.org link, " . $row['NbrLocalCopy'] . " with local copy, " .
                 $row['NbrAddendum'] . " addendum link, " . $row['NbrIndex'] . " added when indexed)");

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Addendums;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Number of addendum: " . $row['Nbr']);

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT COUNT(*) AS 'Nbr' FROM KeywordXref;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Number of keyword/article cross references: " . $row['Nbr']);

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT COUNT(*) AS 'Nbr' FROM Articles WHERE AuthorsID != 1;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Number of articles indexed: " . $row['Nbr'] . " or " . number_format ((($row['Nbr'] / $nbrarticles)*100), 2) . "%");

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

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
    fwrite ($fh, "&nbsp;Number of issues indexed: " . $row['Nbr'] . " or " . number_format ((($row['Nbr'] / $nbrissue)*100), 2) . "%");

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT t.NbrArticle, t.NbrIssue, t.NbrArticle / t.NbrIssue AS 'AvgArticles' " .
           "FROM  (SELECT (SELECT COUNT(*) " .
           "               FROM Articles) AS 'NbrArticle', " .
           "              (SELECT COUNT(*) " .
           "               FROM Issues) AS 'NbrIssue') AS t";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Average number of articles per issue (all): " . number_format ($row['AvgArticles'], 1));

    fwrite ($fh, "<br>" . "\n");
    
#<!-- 01/13/2010 update -->
    fwrite ($fh, "<br>" . "\n");

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
    fwrite ($fh, "&nbsp;Average number of articles per issue (before 1988 when 2600 was a monthly): " . number_format ($row['AvgArticles'], 1));
    
    fwrite ($fh, "<br>" . "\n");
    
    fwrite ($fh, "<br>" . "\n");

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
    fwrite ($fh, "&nbsp;Average number of articles per issue (since 1988 when 2600 became a quarterly): " . number_format ($row['AvgArticles'], 1));

    fwrite ($fh, "<br>" . "\n");
#<!-- 01/13/2010 update -->

    fwrite ($fh, "<br>" . "\n");

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
    fwrite ($fh, "&nbsp;Average number of articles per issue (last 5 years): " . number_format ($row['AvgArticles'], 1));

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT Issue, COUNT(*) AS 'Nbr', Period, IssueYear, Volume, Quarter " .
           "FROM vw_ArticleInfo " .
           "GROUP BY Issue " .
           "ORDER BY Nbr DESC, IssueYear DESC " .
           "LIMIT 1";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Issue with the most articles (latest one): " . showIssue($row) . " (". $row['Nbr'] . ")");

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT AVG(Pages) AS 'AvgPages' " . 
           "FROM Issues ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Average number of pages per issue (all): " . number_format ($row['AvgPages'], 1));

    fwrite ($fh, "<br>" . "\n");
    
#<!-- 01/13/2010 update -->
    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT AVG(Pages) AS 'AvgPages' " . 
           "FROM Issues " .
           "WHERE IssueYear < 1988 ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Average number of pages per issue (before 1988 when 2600 was a monthly): " . number_format ($row['AvgPages'], 1));

    fwrite ($fh, "<br>" . "\n");
    
    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT AVG(Pages) AS 'AvgPages' " . 
           "FROM Issues " .
           "WHERE IssueYear >= 1988 ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Average number of pages per issue (since 1988 when 2600 became a quarterly): " . number_format ($row['AvgPages'], 1));

    fwrite ($fh, "<br>" . "\n");
#<!-- 01/13/2010 update -->

    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT AVG(Pages) AS 'AvgPages' " . 
           "FROM Issues " .
           "WHERE IssueYear > YEAR(CURDATE()) - 5 ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Average number of pages per issue (last 5 years): " . number_format ($row['AvgPages'], 1));

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

    $sql = "SELECT Pages, Issue, Period, IssueYear, Volume, Quarter " .
           "FROM vw_Issues i " .
           "ORDER BY Pages DESC, IssueYear DESC, Quarter DESC ";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Issue with most pages (latest one): " . showIssue($row) . " (". $row['Pages'] . ")");

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

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
    fwrite ($fh, "&nbsp;Article with most links (latest one): '" . $row['Title'] . "'  " . showIssue($row) . " (". $row['Nbr'] . ")");

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");

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
    fwrite ($fh, "&nbsp;Issue with most links (latest one): " . showIssue($row) . " (". $row['Nbr'] . ")");

    fwrite ($fh, "<br>" . "\n");

    fwrite ($fh, "<br>" . "\n");


    /* total number of pages */
    $sql = "SELECT SUM(Pages) AS Nbr FROM Issues;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    $TotalPages = $row['Nbr'];
    fwrite ($fh, "&nbsp;Total number of Issue pages (including front, back, index, marketplace, etc): " . $TotalPages);
    fwrite ($fh, "<br>" . "\n");
    fwrite ($fh, "<br>" . "\n");
     
    /* total number of article pages */
    $sql = "SELECT SUM(Pages) AS Nbr FROM Articles;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Total number of Article pages (including Letters/Fiction): " . $row['Nbr'] . 
            "&nbsp;&nbsp (" . number_format (($row['Nbr'] / $TotalPages) * 100, 2) . "%)");
    fwrite ($fh, "<br>" . "\n");
    fwrite ($fh, "<br>" . "\n");
    
    /* total number of letters pages */
    $sql = "SELECT SUM(a.Pages) AS Nbr " .
           "FROM Articles a " .
           "LEFT JOIN KeywordXref kx ON a.ArticlesID = kx.ArticlesID " .
           "LEFT JOIN Keywords k ON kx.KeywordsID = k.KeywordsID " .
           "WHERE k.Keyword = 'Letters';";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Total number of Letter pages (part of Article pages): " . $row['Nbr'] .
            "&nbsp;&nbsp (" . number_format (($row['Nbr'] / $TotalPages) * 100, 2) . "%)");
    fwrite ($fh, "<br>" . "\n");
    fwrite ($fh, "<br>" . "\n");

    /* total number of fiction pages */
    $sql = "SELECT SUM(a.Pages) AS Nbr " .
           "FROM Articles a " .
           "LEFT JOIN KeywordXref kx ON a.ArticlesID = kx.ArticlesID " .
           "LEFT JOIN Keywords k ON kx.KeywordsID = k.KeywordsID " .
           "WHERE k.Keyword = 'Fiction';";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Total number of Fiction pages (part of Article pages): " . $row['Nbr'] .
            "&nbsp;&nbsp (" . number_format (($row['Nbr'] / $TotalPages) * 100, 2) . "%)");
    fwrite ($fh, "<br>" . "\n");
    fwrite ($fh, "<br>" . "\n");

    /* average number of pages per article */
    #$sql = "SELECT (SELECT SUM(Pages) FROM Articles) / (SELECT COUNT(*) FROM Articles) AS Nbr";
    $sql = "SELECT (SELECT SUM(Pages) FROM ( " .
                   "SELECT DISTINCT a.ArticlesID, a.Pages FROM Articles a " .
                   "LEFT JOIN KeywordXref kx ON a.ArticlesID = kx.ArticlesID " .
                   "LEFT JOIN Keywords k ON kx.KeywordsID = k.KeywordsID " .
                   "WHERE k.Keyword NOT IN ('Letters', 'Fiction') " .
                   ") AS t) " .
                   " / " .
                   "(SELECT COUNT(*) FROM ( " .
                   "SELECT DISTINCT a.ArticlesID FROM Articles a " .
                   "LEFT JOIN KeywordXref kx ON a.ArticlesID = kx.ArticlesID " .
                   "LEFT JOIN Keywords k ON kx.KeywordsID = k.KeywordsID " .
                   "WHERE k.Keyword NOT IN ('Letters', 'Fiction') " .
                   ") AS t) AS Nbr;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    fwrite ($fh, "&nbsp;Average number of pages per article (excluding Letters/Fiction): " . number_format ($row['Nbr'], 3));
    fwrite ($fh, "<br>" . "\n");
    fwrite ($fh, "<br>" . "\n");
    
    /* top 10 article pages */
    $sql = "SELECT DISTINCT v.Issue, v.Title, v.ArticlePages, v.ArticlesID, v.IssuesID " .
           "FROM vw_ArticleInfo v " .
           "LEFT JOIN KeywordXref kx ON v.ArticlesID = kx.ArticlesID " .
           "LEFT JOIN Keywords k ON kx.KeywordsID = k.KeywordsID " .
           "WHERE k.Keyword != 'Letters' " .
           "ORDER BY v.ArticlePages DESC, v.Title ASC LIMIT 10";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
    
    fwrite ($fh, "&nbsp;Top ten articles by number of pages:");
    fwrite ($fh, '<table width="50%" cellspacing="1px" cellpadding="2px" >');
    $inbr = 0;
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {

      fwrite ($fh, "<tr>\n");
        fwrite ($fh, "<tr bgcolor="); if ($inbr % 2) fwrite ($fh, "palegreen"); else fwrite ($fh, "honeydew"); fwrite ($fh, ">\n");
        fwrite ($fh, "<td>");
          fwrite ($fh, $row['Issue']);
        fwrite ($fh, "</td>");
        fwrite ($fh, "<td>");
          fwrite ($fh, $row['Title']);
        fwrite ($fh, "</td>");
        fwrite ($fh, "<td>");
          fwrite ($fh, $row['ArticlePages']);
        fwrite ($fh, "</td>");
        fwrite ($fh, "<td>");
          fwrite ($fh, '<a href="article.php?i=' . $row['IssuesID'] . '&amp;a=' . $row['ArticlesID'] . '">..</a>');
        fwrite ($fh, "</td>");
      fwrite ($fh, "</tr>\n");
      $inbr++;
    }
    fwrite ($fh, "</table>\n");
    fwrite ($fh, "<br>" . "\n");
    
    /* Ignore authors: 1=Not Assigned, 2=Emmanuel Goldstein
                      15=Readers, 346=No Author Specified
    */
    $sql = "SELECT a.AuthorsID, COUNT(*) AS 'Nbr', au.NomDePlume " .
           "FROM Articles a " .
           "LEFT JOIN Authors au ON a.AuthorsID = au.AuthorsID " .
           "WHERE a.AuthorsID NOT IN (1, 15) " .
           "GROUP BY a.AuthorsID " .
           "ORDER BY Nbr DESC, au.NomDePlume " .
           "LIMIT 12";  // will ignore 2 and 346
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

  /* display results */
    $inbr = 0;
    $nbr2 = 0;
    $nbr346 = 0;
    fwrite ($fh, "&nbsp;Top ten authors by articles written (ties in alphabetical order):");
    fwrite ($fh, '<table width="25%" cellspacing="1px" cellpadding="2px" >');
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {

      if ($row['AuthorsID'] == 2)  $nbr2 = $row['Nbr'];
      else  if ($row['AuthorsID'] == 346)  $nbr346 = $row['Nbr'];
      else  {
        fwrite ($fh, "<tr bgcolor="); if ($inbr % 2) fwrite ($fh, "palegreen"); else fwrite ($fh, "honeydew"); fwrite ($fh, ">\n");
          fwrite ($fh, "<td>");
            fwrite ($fh, $row['NomDePlume']);
          fwrite ($fh, "</td>");
          fwrite ($fh, "<td>");
            fwrite ($fh, $row['Nbr']);
          fwrite ($fh, "</td>");
          fwrite ($fh, "<td>");
  // http://localhost/2600/search.php?search=search&FromYear=0&ToYear=9999&Author=770        
            fwrite ($fh, '<a href="search.php?search=search&amp;FromYear=0&amp;ToYear=9999&amp;Author=' . $row['AuthorsID'] . '" onclick="toPost(this.href); return false;">..</a>');
          fwrite ($fh, "</td>");
        fwrite ($fh, "</tr>\n");
        $inbr++;
      }
    }
    fwrite ($fh, "</table>\n");

    //fwrite ($fh, "<br>" . "\n");
    
    fwrite ($fh, '<text class="note">Not on this list are "Emmanual Goldstein" with ' . $nbr2 . ' and "No Author Specified" with ' . $nbr346 . '.</text>');
    fwrite ($fh, "<br>" . "\n");
    fwrite ($fh, "<br>" . "\n");

  /* top ten keywords */
    $sql = "SELECT IFNULL(k.Keyword, 'Not Assigned') AS 'Keyword', COUNT(*) AS 'Nbr' " .
           "FROM Articles a " .
           "LEFT JOIN KeywordXref kx ON a.ArticlesID = kx.ArticlesID " .
           "LEFT JOIN Keywords k ON kx.KeywordsID = k.KeywordsID " .
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
    fwrite ($fh, "</table>");
    fwrite ($fh, "<br>" . "\n");


    echo "<br> Done! <br><br>";
?>
    <a href=<?php echo $path ?>stats.html>stats.html</a>
  </div>

<?php
  fclose ($fh);
  mysqli_close ($conn);
?>

  <div id="foot">
    <?php include ($path . "footer.php"); ?>
  </div>

  </body>
</html>
