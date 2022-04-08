<!--
  page.php

  Author:      wrepp
  Date:        10/13/2008
  Description: default page format for 2600 index web site
  Notes:       table editors only available if sub-directory found
-->

<?php

  $path = "";
  $cwd = getcwd ();
  #echo $cwd . "->" . substr ($cwd, -7) . "<br>";
  if (substr ($cwd, -12) == "TableEditors" ||
      substr ($cwd, -4) == "Util" ||
      substr ($cwd, -7) == "Reports" ||
      substr ($cwd, -10) == "OpenSource")  {
    $path = "../";
  }

?>

  <div id="head">
    <img src="<?=$path?>banner.jpg" alt="2600 Index" style="margin-top: 5px; font-size: 50%; color: white">
    <?php
      $filename = "message.txt";
      if (is_file ($filename))  {
        $f = fopen ($filename, "r");
        $tmp = fgets ($f);
        fclose ($f);
        $text = substr ($tmp, 0, strlen ($tmp)-1);  // remove line feed
        echo $text;
      }
      ?>
    <?php
      if (!isset ($_SERVER['HTTPS']) ) {
        echo '<a style="font-size: 30%; color: white" href="https:/www.2600index.info"> try using the 2600 Index SSL version</a>';
      }
     ?>
<!--
    <a href="http://americancensorship.org"><img style="width:300px; height:40px; " src="/2600/stop-censorship-small.png"></a>
-->
<!-- 11/01/2013 no long responds
    <a style="width:400px;height:70px;vertical-align:middle;text-align:center;background-color:#000;position:absolute;z-index:16;top:0px;left:250px;background-image:url(http://americancensorship.org/images/stop-censorship-small.png);background-position:center center;background-repeat:no-repeat;" href="http://americancensorship.org"></a>
-->

  </div>

  <div id="left">
    <dl>
      <dt>
        <a class="nav" href="<?php echo $path ?>index.php">Home</a>
      </dt>
      <dt>
        <a class="nav" href="<?php echo $path ?>article.php">Articles</a>
      </dt>
      <dt>
        <a class="nav" href="<?php echo $path ?>search.php">Search</a>
      </dt>
      <dt>
        <a class="nav" href="<?php echo $path ?>status.php">Status</a>
      </dt>
      <dt>
        <a class="nav" href="<?php echo $path ?>BestOf2600Book.php">Best of 2600</a>
      </dt>
      <dt>
        <a class="nav" href="<?php echo $path ?>keywords.php">Keywords</a>
      </dt>
      <dt>
        <a class="nav" href="<?php echo $path ?>links.php">Links</a>
      </dt>
      <dt>
        <a class="nav" href="<?php echo $path ?>mirrors.php">Mirrors</a>
      </dt>
    </dl>


  <?php
  /* only if subdirectory exists */
    if (is_dir ($path . "Reports"))  {
  ?>
<!--
      <dl>
        <dt>&nbsp;</dt>
      </dl>
-->
      <dl>
        <dt style="color:grey">
          <b>&nbsp; Reports</b>
        </dt>
      </dl>
      <dl>
        <dt>
          <a class="nav" href="<?php echo $path ?>Reports/ArticlesWithNoKeywords.php">Article/Keyword</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>Reports/KeywordsWithNoArticles.php">Keyword/Article</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>Reports/Links.php">Links</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>Reports/MissingData.php">Missing Data</a>
        </dt>
      </dl>

  <?php
    }
  /* only if subdirectory exists */
    if (is_dir ($path . "Util"))  {
  ?>
<!--
      <dl>
        <dt>&nbsp;</dt>
      </dl>
-->
      <dl>
        <dt style="color:grey">
          <b>&nbsp; Util</b>
        </dt>
      </dl>
      <dl>
        <dt>
          <a class="nav" href="<?php echo $path ?>Util/AssignArticles.php">Assign Articles</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>Util/ValidateLinks.php">Validate Links</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>Util/wgetLinks.php">wget Links</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>Util/writeStats.php">Write Stats</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>Util/writeBestOf2600Book.php">Write 2600 Book</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>Util/addIssue.php">Add Issue</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>Util/buildOpenSource.php">Build Open Source</a>
        </dt>
      </dl>

  <?php
    }
  /* only if subdirectory exists */
    if (is_dir ($path . "TableEditors"))  {
  ?>
<!--
      <dl>
        <dt>&nbsp;</dt>
      </dl>
-->
      <dl>
        <dt style="color:grey">
          <b>&nbsp; Table Editors</b>
        </dt>
      </dl>
      <dl>
        <dt>
          <a class="nav" href="<?php echo $path ?>TableEditors/TableEditor.php?a=reset">Table Editor</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>TableEditors/Addendums.php?a=reset">Addendums</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>TableEditors/Articles.php?a=reset">Articles</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>TableEditors/Authors.php?a=reset">Authors</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>TableEditors/Issues.php?a=reset">Issues</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>TableEditors/Keywords.php?a=reset">Keywords</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>TableEditors/KeywordXref.php?a=reset">KeywordXref</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>TableEditors/Links.php?a=reset">Links</a>
        </dt>
      </dl>

  <?php 
    }

  /* only if subdirectory exists */
    if (is_dir ($path . "OpenSource"))  {
  ?>
      <dl>
        <dt style="color:grey">
          <b>&nbsp; Open Source</b>
        </dt>
      </dl>
      <dl>
        <dt>
          <a class="nav" href="<?php echo $path ?>OpenSource/overview.php">Overview</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>OpenSource/directoryListing.php?o=SQL">SQL</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>OpenSource/directoryListing.php?o=PHP">PHP</a>
        </dt>
        <dt>
          <a class="nav" href="<?php echo $path ?>OpenSource/directoryListing.php?o=Data">Data</a>
        </dt>
      </dl>

  <?php 
    }
  ?>
  
  
  </div>
