<!--
  article.php

  Author:      wrepp
  Date:        10/13/2008
  Description: article info page for 2600 index web site
  Notes:
  
  05/18/2010 wre split -> preg_split.
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php
  global $issue;
  global $article;

  include ("db.php");

function convertTinyUrl ($url)  {

#echo "url=" . $url . "=<br>";
  $tinyurl = "";
  if (preg_match ("*tinyurl*", $url))  {
    $headers = get_headers ($url, 1);
  
#print_r ($headers);
    if (preg_match ( '#^HTTP/.*\s+[(301)]+\s#i', $headers[0] ))  {
      $tinyurl = $headers['Location'];
    }
  }

  return $tinyurl;
}


/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);


/* default to latest issue/article */
  $sql = "SELECT * FROM Issues ORDER BY IssueYear DESC, Quarter DESC LIMIT 1;";
  $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

  $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
  $issue = $row["IssuesID"];

/* query parameters */
  $i = @$_GET["i"];  // specific issue
  if ($i == "")  $i = $issue;
  $issue = $i;

  $o = @$_GET["o"];  // order by
  $orderby = $o;
  
  $sql = "SELECT * FROM Articles WHERE IssuesID = " . $issue;
  if ($orderby == "")  $sql .= " ORDER BY Title LIMIT 1;";
  else  $sql .= " ORDER BY Page LIMIT 1;";
  #echo $sql;
  $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

  $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
  $article = $row["ArticlesID"];

/* query parameters */
  $a = @$_GET["a"];  // specific article
  if ($a == "")  $a = $article;
  $article = $a;

?>

<html>
  <head>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
  <title>2600 Article</title>
  <?php include ("header.php"); ?>
  </head>

  <body>

  <?php include ("page.php"); ?>

  <div id="content">

    <p class="section">Articles</p>

    &nbsp;
  <select id="Issue" name="Issue" onchange="window.location = 'article.php?i=' + this.value + '&amp;o=<?php echo $orderby ?>'">
<?php
  $sql = "SELECT IssuesID, Issue FROM vw_Issues ORDER BY IssueYear, `Quarter`;";
  #echo $sql;
  $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

  while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
    $id = $row["IssuesID"];
    $val = $row["Issue"];
    if ($row["IssuesID"] == $issue) {$selstr = " selected"; } else {$selstr = ""; }
?>
    <option value="<?php echo $id ?>"<?php echo $selstr ?>><?php echo $val ?></option>
<?php 
  }
?>
  </select>

    &nbsp;
<!--
  <select id="Article" name="Article" onchange="window.location = 'article.php?i=' + Issue.value + '&a=' + this.value">
-->
  <select id="Article" name="Article" onchange="window.location = 'article.php?i=' + document.getElementById('Issue').value + '&amp;a=' + this.value + '&amp;o=<?php echo $orderby ?>'">
<?php
  $sql = "SELECT ArticlesID, Title FROM vw_ArticleInfo WHERE IssuesID = '" . $issue . "' ";
  if ($orderby == "")  $sql .= "ORDER BY Title;";
  else  $sql .= "ORDER BY Page;";
  #echo $sql;
  $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

/* for prev/next article links */
  $previd = "";
  $nextid = "";
  $saveid = "";
  $selected = "";
  
/* select article in combobox */
  while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
    $id = $row["ArticlesID"];
    $val = $row["Title"];
    if ($row["ArticlesID"] == $article) {
      $selstr = " selected";
      $previd = $saveid;
      $selected = "ok";
    }
    else {
      $selstr = "";
      if ($selected == "ok")  {
        $nextid = $id;
        $selected = "";
      }
    }
    $saveid = $id;
?>
    <option value="<?php echo $id ?>"<?php echo $selstr ?>><?php echo $val ?></option>
<?php 
  }
?>
  </select>
  
<?php
/* prev/next article links only if there is one */
  if ($previd != "")  {
?>  
    <a href="article.php?i=<?php echo $i; ?>&a=<?php echo $previd; ?>&o=<?php echo $orderby ?>">prev</a>
<?php
  }
  if ($nextid != "")  {
?>  
    <a href="article.php?i=<?php echo $i; ?>&a=<?php echo $nextid; ?>&o=<?php echo $orderby ?>">next</a>
<?php
  }
?>

<?php
  $sql = "SELECT * FROM vw_ArticleInfo WHERE IssuesID = '" . $issue . "' AND ArticlesID = '" . $article . "'";
  #echo $sql;

  $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

  $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);


?>


  <br>
  <br>
  <div id="issueinfo">
    <img src="<?php echo $row['CoverLink'] ?>" alt="<?php echo $row['Issue']?> cover image" >
    <br>
    Volume <?php $volume = $row['Volume']; echo $volume ?><br>
    Number <?php $number = $row['Quarter']; echo $number ?><br>
    <?php
      if ($row['Pages'] > 0)  {
    ?>
        <?php echo $row['Pages']?> pages<br>
    <?php
      }
    ?>
    <?php
    /* only if file exists */
      #echo getcwd() . "<br>";
      #echo $row['BackCoverLink'] . "<br>";
      if (is_file ($row['BackCoverLink']))  {
    ?>
        <img src="<?php echo $row['BackCoverLink'] ?>" alt="<?php echo $row['Issue']?> back cover image" ><br>
    <?php
    }
      if ($row['Quote'] != '')  {
    ?>
        <b>Quote:</b> <?php echo $row['Quote'] ?><br>
    <?php
      }
      if ($row['IssueNotes'] != '')  {
    ?>
        <b>Notes:</b> <?php echo $row['IssueNotes'] ?><br>
    <?php
      }
  ?>
    <br>
  </div>


  <strong class="section">Author</strong><br>
  <table width="75%" cellspacing="1px" cellpadding="2px" >
    <tr>
      <td bgcolor="lightblue" width="18%">Nom de plume</td>
      <td bgcolor="honeydew"><?php echo $row['NomDePlume'] ?></td>
    </tr>
    <tr>
      <td bgcolor="lightblue">Name</td>
      <td bgcolor="honeydew"><?php echo $row['Name'] ?></td>
    </tr>
    <tr>
      <td bgcolor="lightblue">Email</td>
      <td bgcolor="honeydew">
        <!-- <?php echo "<a href='mailto:" . $row['Email'] . "'>" . $row['Email'] . "</a>" ?> -->
        <?php 
           //$emails = split("\r\n", $row['Email']);
           $emails = preg_split("/\r\n/", $row['Email']);
           for ($i = 0; $i < count($emails); $i++)  {
             echo "<a href='mailto:" . $emails[$i] . "'>" . $emails[$i] . "</a><br>";
           }
         ?>
      </td>
    </tr>
    <tr>
      <td bgcolor="lightblue">Notes</td>
      <td bgcolor="honeydew"><?php echo str_replace ("\r\n", "<br>", $row['AuthorNotes']); ?></td>
    </tr>
  </table>

  <br>
  <strong class="section">Aritcle</strong><br>
  <table width="75%" cellspacing="1px" cellpadding="2px" >
<?php
    if ($row['PageTitle'] != "")  {
?>
    <tr>
      <td bgcolor="lightblue" width="18%">Page Title</td>
      <td bgcolor="honeydew"><?php echo $row['PageTitle'] ?></td>
    </tr>
<?php
    }
?>
    <tr>
      <td bgcolor="lightblue" width="18%">Synopsis</td>
      <td bgcolor="honeydew"><?php echo $row['Synopsis'] ?></td>
    </tr>
    <tr>
      <td bgcolor="lightblue">Notes</td>
      <td bgcolor="honeydew"><?php echo $row['ArticleNotes'] ?></td>
    </tr>
    <tr>
      <td bgcolor="lightblue">Code</td>
      <td bgcolor="honeydew">
        <?php 
          if ($row['Code'] == "A")  echo "In article text";
          if ($row['Code'] == "C")  echo "@ <a href='http://www.2600.com/code/'>2600 code web site</a>";
          if ($row['Code'] == "N")  echo "None";
          if ($row['Code'] == "S")  echo "See Links";
         ?>
      </td>
    </tr>
    <tr>
      <td bgcolor="lightblue">Page</td>
      <td bgcolor="honeydew"><?php echo $row['Page'] ?></td>
    </tr>
    <tr>
      <td bgcolor="lightblue">Pages</td>
      <td bgcolor="honeydew">
      <?php
        $a = "";
        if (intval ($row['ArticlePages']) > 0)  {
          echo intval ($row['ArticlePages']);
          $a = " and ";
        }
        #testing: SELECT * FROM Articles WHERE Pages > 1
      /* get fractional portion of pages - we've already displayed the integer */
        $nbr = abs(intval($row['ArticlePages']) - $row['ArticlePages']);
        //$nbr = 0.66;
        $nbr = number_format ($nbr, 3);  // format to three decimal places
        switch ($nbr)  {
          case 0 :  // ignore
            break;
          case .125 :
            echo $a . "one eighth";
            break;
          case .25 :
            echo $a . "a quarter";
            break;
          case .33 :
            echo $a . "a third";
            break;
          case .375 :
            echo $a . "three eighths";
            break;
          case .5 :
            echo $a . "a half";
            break;
          case .625 :
            echo $a . "five eighths";
            break;
          case .66 :
            echo $a . "two thirds";
            break;
          case .75 :
            echo $a . "three quarters";
            break;
          case .825 :
            echo $a . "seven eighths";
            break;
          default :
            echo $a . $nbr;  // display fraction
        }
      ?>
      </td>
    </tr>
    <tr>
      <td bgcolor="lightblue">In Best of 2600 book?</td>
      <td bgcolor="honeydew">
        <?php
          if ($row['PageIn2600Book'] <= 0)  echo "No";
          else  {
            echo "Yes, page ";
            echo $row['PageIn2600Book'];
            
            $sql =  "SELECT s.Number AS 'Section', s.Name as 'SectionName', " .
                    "       c.Number AS 'Chapter', c.Name as 'ChapterName' " .
                    "FROM Chapters c " .
                    "LEFT JOIN Sections s ON c.SectionsID = s.SectionsID " .
                    "WHERE " . $row['PageIn2600Book'] . " >= c.Page " .
                    "  AND " . $row['PageIn2600Book'] . " < IFNULL((SELECT Page FROM Chapters ch WHERE c.Number+1 = ch.Number), 999);";
            #echo $sql . "<br>";
            $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
            $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
            echo ", Section " . $row['Section'] . " (" . $row['SectionName'] . "), Chapter " . $row['Chapter'] . " (" . $row['ChapterName'] . ")";
          }
        ?></td>
    </tr>
  </table>

  <br>
  <strong class="section">Links</strong><br>
  <?php
    $sql = "SELECT l.*, DATE_FORMAT(Validated, '%m/%d/%Y') AS 'ValidatedFormat', " .
           "       DATE_FORMAT(LocalCopyDate, '%m/%d/%Y') AS 'LocalCopyDateFormat' " .
           "FROM Links l WHERE ArticlesID = " . $article;
    #echo $sql;
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

/* no table if no adddendums */
    $nbr = mysqli_num_rows($result);
    if ($nbr > 0)  {
    

  ?>
    <table width="75%" cellspacing="1px" cellpadding="2px" >
  <?php
      $inbr = 0;
      while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
  ?>
        <tr bgcolor=<?php if ($inbr % 2)  echo "palegreen"; else  echo "HONEYDEW";?> >
        <?php
        $newlink = "yes";
        if ($newlink == "yes")  {
        ?>
          <td width="125px">
            <?php if ($row['Status'] == "?")  echo "Not validated"; ?>
            <?php if ($row['Status'] == "Ok")  echo "Ok on " . $row['ValidatedFormat']; ?>
            <?php if ($row['Status'] == "Err")  echo "<text class='red'> Error</text> on " . $row['ValidatedFormat']; ?>
          </td>
          <td>
            <a href="<?php echo $row['Link']?>" title="<?php echo $row['Link']?>">
               <?php if (strlen ($row['Description']) > 0) echo $row['Description'];
                     else  echo $row['Link'];
                ?>
            </a>
          </td>
          <td>
            <?php if ($row['Updated'] == "N")  echo "Original link";
              else  {
                echo "Original was ";
            ?>
            <a href="<?php echo $row['OriginalLink']?>" ><?php echo $row['OriginalLink']?>
            <?php
              }
            ?>
          </td>
        </tr>
        <tr bgcolor=<?php if ($inbr % 2)  echo "palegreen"; else  echo "HONEYDEW";?> >
          <td align="right">
            <?php if ($row['Type'] == "O")  echo "Original article"; ?>
            <?php if ($row['Type'] == "A")  {
                    echo "Addendum link";
                    $addendumlink = "yes";
                  }
                  else  {
                    $addendumlink = "";
                  }
             ?>
            <?php if ($row['Type'] == "I")  echo "Added when indexing"; ?>
          </td>
          <td>
            <?php if ($row['LocalCopy'] != "Y")  echo "No local copy";
                  else  {
                  
                  /* tinyurl? checks and gets actual */
                    //$tinyurl = convertTinyUrl ($row['Link']);

                    $url = $row['Link'];
                  /* ignore http:// or https:// or ftp etc... */
                    #$LocalCopyLink = substr ($row['Link'], 7); don't hard code because it could be https!
                    //$LocalCopyLink = substr ($row['Link'], strpos ($row['Link'],"//")+2);
                    $LocalCopyLink = substr ($url, strpos ($url,"//")+2);
                  #/* replace encoded question mark with actual question mark */
                  #  $LocalCopyLink = str_replace ("?", "%3F", $LocalCopyLink);
                  /* check if file name had .html added from wget - if no file extension see if it exists */
                    $ext = pathinfo ($LocalCopyLink, PATHINFO_EXTENSION);
                    $LocalCopyLink = "Links/" . $volume . "/" . $number . "/" . $LocalCopyLink;
                    #echo "=" . $LocalCopyLink . "=";
                    #echo "ext=" . $ext . "=";
                    #if (is_file ($LocalCopyLink . ".html")) echo "isfile<br>";
                    #if (file_exists ($LocalCopyLink . ".html")) echo "exists<br>";
                    if ($ext == "" && is_file ($LocalCopyLink . ".html"))  $LocalCopyLink .= ".html";
                    else  if ($ext != "html" && is_file ($LocalCopyLink . ".html"))  $LocalCopyLink .= ".html";
                  /* replace encoded question mark with actual question mark */
                    $LocalCopyLink = str_replace ("?", "%3F", $LocalCopyLink);
                  ?>
<!--
                    <a href="Links/<?php echo $volume . '/' . $number . '/' . $LocalCopyLink; ?>">
-->
                    <a href="<?php echo $LocalCopyLink; ?>">
                       Local copy on <?php echo $row['LocalCopyDateFormat'] ?></a>
                  <?php
                  }
            ?>
          </td>
          <td>
            <?php if ($row['ArchiveLink'] == "")  echo '<text class="note">No archive.org</text>';
                  else  {
                    ?>
                    <a href="<?php echo $row['ArchiveLink'] ?>"><?php echo $row['ArchiveLink'] ?></a>
                    <?php
                  }
            ?>
          </td>
        <?php
        }
        else  {
        ?>
        
          <td width="30px">
            <?php if ($row['Status'] == '?')  { ?>
                    <a title="Link never checked" href=""><?php echo $row['Status'] ?></a>
            <?php }
                  else  { ?>
                    <a title="Link last checked on <?php echo $row['ValidatedFormat']?>" href=""><?php if ($row['Status'] == "Err")  echo "<text class='red'>";
                                                                                                       echo $row['Status'];
                                                                                                       if ($row['Status'] == "Err")  echo "</text>";
                                                                                                  ?></a>
            <?php } ?>
          </td>
          <td width="10px">
            <?php if ($row['Updated'] == 'N')  { ?>
                    <a title="Original link" href=""><?php echo $row['Updated'] ?></a>
            <?php }
                  else  { ?>
                    <a title="Link changed from article" href=""><?php echo $row['Updated'] ?></a>
            <?php } ?>
          </td>
          <td>
            <a href="<?php echo $row['Link']?>" title="<?php echo $row['Link']?>">
               <?php if (strlen ($row['Description']) > 0) echo $row['Description'];
                     else  echo $row['Link'];
                ?>
            </a>
          </td>
        <?php
        }
        ?>
        </tr>
  <?php
        if ($row['Notes'] != "")  {
  ?>
          <tr bgcolor=<?php if ($inbr % 2)  echo "palegreen"; else  echo "HONEYDEW";?> >
          <td align="right">
            Note:
          </td>
          <td>
            <?php echo $row['Notes']; ?>
          <td>
          </tr>
          <td>
          </td>
  <?php
        }
  ?>
  <?php
        $inbr++;
      }
  ?>
    </table>
  <?php
    }
  ?>

  <br>
  <strong class="section">Addendum</strong><br>
  <?php
    $sql = "SELECT * FROM Addendums WHERE ArticlesID = " . $article;
    #echo $sql;
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
    
  /* no table if no adddendums */
    $nbr = mysqli_num_rows($result);
    if ($nbr > 0)  {
  ?>
      <table width="75%" cellspacing="1px" cellpadding="2px" >
  <?php
      $inbr = 0;
      while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
  ?>
        <tr bgcolor=<?php if ($inbr % 2)  echo "palegreen"; else  echo "HONEYDEW";?> >
          <td>
            <?php echo $row['Notes'] ?>
            <?php if ($addendumlink != "")  echo " (see addendum link above)"; ?>
          </td>
        </tr>
  <?php
        $inbr++;
      }
  ?>
      </table>
  <?php
    }
  ?>
  
  <br>
  <strong class="section">Keywords</strong><br>
  <table width="75%" cellspacing="1px" cellpadding="2px" >
  <?php
    $sql = "SELECT * FROM vw_ArticleKeywords WHERE ArticlesID = " . $article;
    #echo $sql;
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    $inbr = 0;
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
  ?>
      <tr bgcolor=<?php if ($inbr % 2)  echo "palegreen"; else  echo "HONEYDEW";?> >
        <td width="18%">
          <?php echo $row['Keyword'] ?>
        </td>
        <td>
          <?php 
            echo $row['Notes'];
            if ($row['WikipediaLink'] != '')  {
              # echo "&nbsp; &nbsp; <a href='" . $row['WikipediaLink'] . "'>(more..)</a>";
              //$links = split("\r\n", $row['WikipediaLink']);
              $links = preg_split("/\r\n/", $row['WikipediaLink']);
              for ($i = 0; $i < count($links); $i++)  {
                echo "&nbsp; &nbsp; <a href='" . $links[$i] . "'>(more..)</a>";
              }
            }
          ?>
        </td>
      </tr>

  <?php
      $inbr++;
    }
  ?>
  </table>

<?php
/* allow reassign of an article on home system only */
    if (is_dir ($path . "Util"))  {
?>
       <br>
       <a href='Util/AssignArticles.php?a=<?php echo $article?>'>Reassign..</a>
<?php       
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

