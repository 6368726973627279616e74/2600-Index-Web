<!--
  overview.php

  Author:      wrepp
  Date:        01/05/2010
  Description: overview documentation for 2600 index project.
  Notes:       until I come up with something better.
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
  <title>Open Source</title>
  <?php include ($path . "header.php"); ?>
  </head>

  <body>

  <?php include ($path . "page.php"); ?>

  <div id="content">

    <p class="section">Overview</p>

    <p>
     The 2600 index web site has a php/html/css user interface, a mysql database containing all of the issue/article information, a standalone Java program that gets the initial issue and article title information from the 2600 store web site and scripts used to update the public web site. It has two parts - a public internet facing web site accessable to anyone and a private web site run on an internal intranet used to enter 2600 indexing information and maintain the web site. This page is a preliminary document only created to show my intention of open sourcing this project, it is far from complete and will probably evolve into something completely different. My main focus now is completing the data entry.
    </p>

    <p class="section">Public</p>
    <ul>
      <li>
        Main
        <ul>
          <li>
            Home <br>  (index.php)
          </li>
          <li>
            Articles <br>  (article.php)
          </li>
          <li>
            Search <br>  (search.php)
          <br>
          <li>
            Status <br>  (status.php)
          </li>
          <li>
            Best of 2600  <br> ( BestOf2600Book.php)
          </li>
          <li>
            Keywords <br>  (keywords.php)
          </li>
          <li>
            Links <br>  (links.php)
          </li>
        </li>
      </ul>
      <li>
        Open Source
        <ul>
          <li>
            Overview <br>  (overview.php)
          </li>
          <li>
            SQL <br>  (directoryListing.php)
          </li>
          <li>
            PHP <br>  (directoryListing.php)
          </li>
          <li>
            Data <br>  (directoryListing.php)
          </li>
        </ul>
      </li>
    </ul>

    <p class="section">Private</p>
    
    <ul>
      <li>
        Reports
        <ul>
          <li>
            Article/Keyword <br> List articles that don't have any keywords assigned to it.  (ArticlesWithNoKeywords.php)
          </li>
          <li>
            Keyword/Article <br> List keywords that aren't assigned to any articles.  (KeywordsWithNoArticles.php)
          </li>
          <li>
            Links <br> List links by option/status. (Links.php)
          </li>
          <li>
            Missing Data <br> List articles missing Author/Synopsis/Page. (MissingData.php)
          </li>
        </ul>
      </li>
      <li>
        Util
        <ul>
          <li>
            Assign Articles <br> (AssignArticles.php)
          </li>
          <li>
            Validate Links <br> (validateLink.php)
          </li>
          <li>
            wget Links <br> (wgetLinks.php)
          </li>
          <li>
            Write Stats <br> (writeStats.php)
          </li>
          <li>
            Write 2600 Book <br> (writeBestOf2600Book.php)
          </li>
          <li>
            Add Issue <br> (addIssue.php)
          </li>
          <li>
            Build Open Source <br> (buildOpenSource.php)
          </li>
        </ul>
      </li>
      <li>
        Table Editors
        <ul>
          <li>
            Addendums
          </li>
          <li>
            Articles
          </li>
          <li>
            Authors
          </li>
          <li>
            Issues
          </li>
          <li>
            Keywords
          </li>
          <li>
            KeywordXref
          </li>
          <li>
            Links
          </li>
        </ul>
      </li>
      <li>
        Java
        <ul>
          <li>
            <a href="<?=$path?>OpenSource/Load2600Issue.java.html" target=_blank>**!!outdated - original no longer works!!** View old Load 2600 Issue (in new tab)</a> <br> (Load2600Issue.java)
          </li>
        </ul>
      </li>
      <li>
        Python
        <ul>
          <li>
            <a href="<?=$path?>OpenSource/2600.py.html" target=_blank>View new load 2600 issue (in new tab)</a> <br> (2600.py)
          </li>
        </ul>
      </li>
    </ul>

    <p class="section">Tools</p>
    
    <ul>
    <?php include "tools.html"; ?>
    </ul>

    <p class="note">Note: this is from the development server, the production server is most likely different and many of the tools aren't used there.</p>
    
  </div>

<?php
  mysqli_close ($conn);
?>

  <div id="foot">
    <?php include ($path . "footer.php"); ?>
  </div>

  </body>
</html>
