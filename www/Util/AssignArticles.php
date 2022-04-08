<!--
  AssignArticles.php

  Author:      wrepp
  Date:        10/29/2008
  Description: Assign article information.
  Notes:       test autosuggest logic from bsn
               see: http://www.brandspankingnew.net/archive/2006/08/ajax_auto-suggest_auto-complete.html
               
  05/18/2010 wre split -> preg_split.
  01/09/2012 wre added mysqli_real_escape_string,
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php

  $path = "../";

  include ($path . "db.php");

/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);

/* query parameters */
  $article = @$_GET["a"];
  #echo $article . "<br>";
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>2600 Index</title>
    <?php include ($path . "header.php"); ?>
    <link rel="stylesheet" href="bsn/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" >
  </head>

  <body>
  
  <script type="text/javascript" src="bsn/bsn.AutoSuggest_2.1.3.js"></script>

  <script type="text/javascript">
    /* set cursor to field to start from and highlight existing text */
    function focusSelect () {
      var fld = document.getElementById ('NomDePlume');
      if (fld != null)  {
        fld.focus ();
        fld.select ();
      }
    }

    function showKeywords (c)  {
      //window.showModalDialog ("../keywords?o=Popup", "", "dialogHeight: 800px; dialogWidth: 660px;");
      window.open ("../keywords.php?o=Popup", "Keywords", "Height=800px, Width=660px");
    }

    function addKeyword ()  {
      var fld = document.getElementById ('KeywordsID');
    /* ignore first item */
      if (fld.selectedIndex > 0)  {
        //alert (fld.selectedIndex + ', ' + fld.options [fld.selectedIndex].value + ', ' + fld.options [fld.selectedIndex].text);
        var fld2 = document.getElementById ('Keywords');
        fld2.options [fld2.length] = new Option (fld.options [fld.selectedIndex].text);
        //fld2.add (fld.options [fld.selectedIndex].text, null);
        setSelected ();
      }
    }

    function removeKeyword ()  {
      var fld = document.getElementById ('Keywords');
      //if (fld.selectedIndex >= 0)  fld.options [fld.selectedIndex].text = null;
      fld.remove (fld.selectedIndex);
      setSelected ();
    }

    function setSelected ()  {
      var fld = document.getElementById ('Keywords');
      //alert (fld.options.length);
      for (i = 0; i < fld.options.length; i++)  fld.options [i].selected = true;
    }

    window.onload = focusSelect;
  </script>

  <?php include ($path . "page.php"); ?>

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
      newF.action = parms[0] + '?a=edit&amp;recid=0';
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
    
    <p class="section">Assign Article Information</p>

<?php
  /* post back? */
    if (!isset($_POST['update']))  {

   /* no article use first 36 whose author is not assigned */
      if ($article == "")  {

    /* display number of articles for last ten issues */
      $sql = "SELECT Issue, COUNT(*) AS 'Nbr' " .
             "FROM vw_ArticleInfo " .
             "WHERE AuthorsID = 1 " .
             "GROUP BY Issue " .
             "ORDER BY IssueYear DESC, Quarter DESC " .
             "LIMIT 10;";
      #echo $sql . "<br>";
      $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
?>
      <div id="issueinfo">
      <table cellspacing="1px" cellpadding="2px">
      <thead>
      <tr bgcolor="lightgrey">
        <td>Next 10</td>
        <td>#</td>
      </tr>
      </thead>
      
<?php
      while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
?>
        <tr bgcolor="honeydew">
          <td>
            <?php echo $row['Issue'] ?>
          </td>
          <td>
            <?php echo $row['Nbr'] ?>
          </td>
        </tr>
 <?php
      }
?>
      </table>
      </div>


    <table width="75%" cellspacing="1px" cellpadding="2px" >
<?php
      $sql = "SELECT * "  .
             "FROM vw_ArticleInfo " .
             "WHERE AuthorsID = 1 " .
             "ORDER BY IssueYear DESC, `Quarter` DESC " .
             "LIMIT 36;";
      #echo $sql . "<br>";
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
            <?php echo "<a href='AssignArticles.php?a=" . $row['ArticlesID'] . "'>..</a>" ?>
          </td>
        </tr>
<?php
        $inbr++;

      }
?>
    </table>
    <?php
      if ($inbr < 1)  {
        echo "&nbsp;None found.<br>";
      }
    ?>
<?php
      }
      else  {

        $sql = "SELECT * " .
               "FROM vw_ArticleInfo " .
               "WHERE ArticlesID = " . $article . ";";
        #echo $sql . "<br>";
        $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
        $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
?>
        <p><a href="javascript:window.history.back ();">Back</a></p>

        <form method="post" action="">

        <input id="ArticlesID" name="ArticlesID" type="hidden" length="6" maxlength="6" value="<?php echo $row['ArticlesID'] ?>">
        <input id="AuthorsID" name="AuthorsID" type="hidden" length="6" maxlength="6" value="<?php echo $row['AuthorsID'] ?>">

        <table width="65%" cellspacing="1px" cellpadding="2px" >
          <tr>
            <td bgcolor="lightblue" width="18%">Issue</td>
            <td bgcolor="honeydew"><?php echo $row['Issue'] ?></td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Title</td>
            <td bgcolor="honeydew"><?php echo $row['Title'] ?></td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Page Title</td>
            <td bgcolor="honeydew">
              <input id="PageTitle" name="PageTitle" length="128" maxlength="128" type="text" style="width:400px" value="<?php echo $row['PageTitle'] ?>" />
            </td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Nom De Plume</td>
            <td bgcolor="honeydew">
              <input id="NomDePlume" name="NomDePlume" length="32" maxlength="32" type="text" value="<?php echo $row['NomDePlume'] ?>" />
            </td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Synopsis</td>
            <td bgcolor="honeydew">
              <textarea id="Synopsis" name="Synopsis" cols="70" rows="4" maxlength="2048"><?php echo $row['Synopsis'] ?></textarea>
            </td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Notes</td>
            <td bgcolor="honeydew">
              <textarea id="Notes" name="Notes" cols="70" rows="4" maxlength="1024"><?php echo $row['ArticleNotes'] ?></textarea>
            </td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Code</td>
            <td bgcolor="honeydew">
              <select name="Code" id="Code">
                <option value="A" <?php if ($row['Code'] == "A")  echo " selected"?> >In article</option>
                <option value="C" <?php if ($row['Code'] == "C")  echo " selected"?> >Code @ 2600</option>
                <option value="N" <?php if ($row['Code'] == "N")  echo " selected"?> >None</option>
                <option value="S" <?php if ($row['Code'] == "S")  echo " selected"?> >See links</option>
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Page</td>
            <td bgcolor="honeydew">
              <input id="Page" name="Page" type="text" length="4" maxlength="4" style="width:60px" value="<?php echo $row['Page']?>">
            </td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Article Pages</td>
            <td bgcolor="honeydew">
              <input id="ArticlePages" name="ArticlePages" type="text" length="6" maxlength="6" style="width:60px" value="<?php echo $row['ArticlePages']?>">
            </td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Keyword</td>
            <td bgcolor="honeydew" valign="top">
              <!--- <label style="vertical-align:top;">Select to add</label> -->
              <select name="KeywordsID" id="KeywordsID" style="vertical-align:top;" onchange="addKeyword ();" onblur="setSelected ();" >
                <option selected>Select to add...
                </option>
<?php
                $sql = "SELECT KeywordsID, Keyword " .
                       "FROM Keywords " .
                       "ORDER BY Keyword;";
                #echo $sql . "<br>";
                $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

                while ($tmpRow = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
?>
                  <option value="<?php echo $tmpRow['KeywordsID'] ?>"> <?php echo $tmpRow['Keyword'] ?>
                  </option>
<?php
                }
?>
              </select>
              <select name="Keywords[]" id="Keywords" size="4" style="width:150px" multiple ondblclick="removeKeyword ();">
<?php
            $sql = "SELECT k.Keyword " .
                   "FROM KeywordXref kx " .
                   "LEFT JOIN Keywords k ON kx.KeywordsID = k.KeywordsID " .
                   "WHERE kx.ArticlesID = " . $row['ArticlesID'] . ";";
            #echo $sql . "<br>";
            $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

            while ($tmpRow = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
?>
              <option selected><?php echo $tmpRow['Keyword']?>
              </option>
<?php
            }
?>
              </select>
              <a href="javascript:showKeywords ();">List..</a>
              <br>
              (double click to remove)
            </td>
          </tr>
          <tr>
<?php
            $sql = "SELECT LinksID, Link, Description " .
                   "FROM Links " .
                   "WHERE ArticlesID = " . $row['ArticlesID'] . ";";
            #echo $sql . "<br>";
            $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
            
          /* allow editing of links in database */
            if (mysqli_num_rows ($result) > 0)  {
?>
              <td bgcolor="lightblue" width="18%">Existing Links</td>
              <td>
                <table width="100%" cellspacing="1px" cellpadding="1px">
<?php
                while ($tmpRow = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
?>
                  <tr>
                    <td bgcolor="honeydew">
                      <?php echo $tmpRow['Description']; ?>
                    </td>
                    <td bgcolor="honeydew">
                      <a href="http://localhost/2600/TableEditors/Links.php?filter=<?php echo $tmpRow['LinksID'] ?>&amp;filter_field=LinksID" onclick="toPost(this.href); return false;"><?php echo "Edit &nbsp;" . $tmpRow['Link'] ?>..</a>
                    </td>
                  </tr>
<?php
                }
?>              
                </table>
              </td>
              <tr>
<?php
            }
            $links = "";
            $linksdescription = "";
            #while ($tmpRow = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
            #  $links .= $tmpRow['Link'] . "\n";
            #  $linksdescription .= $tmpRow['Description'] . "\n";
            #}
?>
            <td bgcolor="lightblue" width="18%">New Links</td>
            <td bgcolor="honeydew">
              <textarea id="Links" name="Links" cols="70" rows="4" maxlength="1024"><?php echo $links ?></textarea>
            </td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">Link Description</td>
            <td bgcolor="honeydew">
              <textarea id="LinksDescription" name="LinksDescription" cols="70" rows="4" maxlength="1024"><?php echo $linksdescription ?></textarea>
            </td>
          </tr>
          <tr>
            <td bgcolor="lightblue" width="18%">PageIn2600Book</td>
            <td bgcolor="honeydew">
              <input id="PageIn2600Book" name="PageIn2600Book" type="text" length="4" maxlength="4" style="width:60px" value="<?php echo $row['PageIn2600Book']?>">
            </td>
          </tr>
          
        </table>

        <p>
        <input type="submit" id="update" name="update" value="Update...">
        </p>
        </form>

<?php
      }
    }
    else  {
?>
      <p><a href="AssignArticles.php">Another</a></p>
<?php
      echo "<p>update!<p>";

      $ArticlesID = $_POST["ArticlesID"];
      $PageTitle = $_POST["PageTitle"];
      $NomDePlume = $_POST["NomDePlume"];
      $AuthorsID = $_POST["AuthorsID"];
      $Synopsis = $_POST["Synopsis"];
      $Notes = $_POST["Notes"];
      $Code = $_POST["Code"];
      $Page = $_POST["Page"];
      $ArticlePages = $_POST["ArticlePages"];
      #$KeywordsID = $_POST["KeywordsID"];
      $Keywords = $_POST["Keywords"];
      $Links = trim ($_POST["Links"]);
      $LinksDescription = trim ($_POST["LinksDescription"]);
      $PageIn2600Book = $_POST["PageIn2600Book"];

      echo "ArticlesID=" . $ArticlesID . "=<br>";
      echo "PageTitle=" . $PageTitle . "=<br>";
      echo "NomDePlume=" . $NomDePlume . "=<br>";
      echo "AuthorsID=" . $AuthorsID . "=<br>";
      echo "Synopsis=" . $Synopsis . "=<br>";
      echo "Notes=" . $Notes . "=<br>";
      echo "Code=" . $Code . "=<br>";
      echo "Page=" . $Page . "=<br>";
      echo "ArticlePages=" . $ArticlePages . "=<br>";
      #echo "KeywordsID=" . $KeywordsID . "=<br>";
      #var_dump ($_POST["Keywords"]);
      #echo "<br>";
      #echo count ($Keywords) . "<br>";
      for ($i = 0; $i < count ($Keywords); $i++)  echo "Keywords[" . $i . "]=" . $Keywords[$i] . "=<br>";
    /* check for something entered */
      #echo "Links=" . nl2br ($Links) . "=<br>";
      if (strlen ($Links) > 0)  {
        //$links = split ("\n", $Links);
        $links = preg_split ("/\n/", $Links);
        for ($i = 0; $i < count ($links); $i++)  echo "link[" . $i . "]=" . $links[$i] . "=<br>";
      }
      else  $links = null;
    /* check for something entered */
      #echo "LinksDescription=" . nl2br ($LinksDescription) . "=<br>";
      if (strlen ($LinksDescription) > 0)  {
        //$linksdescription = split ("\n", $LinksDescription);
        $linksdescription = preg_split ("/\n/", $LinksDescription);
        for ($i = 0; $i < count ($linksdescription); $i++)  echo "link[" . $i . "]=" . $linksdescription[$i] . "=<br>";
      }
      else  $linksdescription = null;
      echo "PageIn2600Book=" . $PageIn2600Book . "=<br>";
      echo "<br>";  // end of parameters

    /* validate */
      if ($Page == "")  {
        $Page = "0";
        echo "<p class='red'>Page not entered! Set to Zero!!</p>";
      }

    /* turn off auto-commit - ie. transaction */
      mysqli_autocommit ($conn, FALSE);

    /* is nom de plume on file? */
      $sql = "SELECT * FROM Authors WHERE NomDePlume = '" . mysqli_real_escape_string ($conn, $NomDePlume) . "';";
      echo $sql . "<br>";
      $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
      if ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  $AuthorsID = $row['AuthorsID'];
      else  $AuthorsID = 0;
      
    /* new author? add to Authors table */
      if ($AuthorsID == 0)  {
        $sql = "INSERT Authors (NomDePlume) VALUES ('" . $NomDePlume . "');";
        echo $sql . "<br>";
        $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
        $AuthorsID =  mysqli_insert_id ($conn) or die (mysqli_error ($conn));
      }

    /* update Articles table - after author logic for AuthorsID if record added */
      $sql = "UPDATE Articles " .
             "SET AuthorsID = " . $AuthorsID . ", " .
             "    PageTitle = '" . mysqli_real_escape_string ($conn, $PageTitle) . "', " .
             "    Synopsis = '" . mysqli_real_escape_string ($conn, $Synopsis) . "', " .
             "    Notes = '" . mysqli_real_escape_string ($conn, $Notes) . "', " .
             "    Code = '" . $Code . "', " .
             "    Page = " . $Page . ", " .
             "    Pages = " . $ArticlePages . ", " .
             "    PageIn2600Book = " . $PageIn2600Book . " " .
             "WHERE ArticlesID = " . $ArticlesID . ";";
      echo $sql . "<br>";
      $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));


    /* update KeywordsXref table - delete existing */
      $KeywordsIDs = "0";
      
      if (count ($Keywords) > 0)  {
      /*
        $sql = "DELETE FROM KeywordXref " .
               "WHERE ArticlesID = " . $ArticlesID . ";";
        echo $sql . "<br>";
        $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
        $nbr = mysqli_affected_rows ($conn);  // or die (mysqli_error ($conn));
        echo "nbr=" . $nbr . "<br>";
      */
      /* init */
        $comma = ", ";
        
      /* get KeywordIDs for all keywords */
        for ($i = 0; $i < count ($Keywords); $i++)  {
          $sql = "SELECT KeywordsID " .
                 "FROM Keywords " .
                 "WHERE Keyword = '" . $Keywords[$i] . "';";
          $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
          $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
          $KeywordsIDs .= $comma . $row['KeywordsID'];
        }  // for
      }  // if
      
    /* only delete records in KeywordsXref table that have been changed */
      $sql = "DELETE FROM KeywordXref " .
             "WHERE ArticlesID = " . $ArticlesID . " " .
             "  AND KeywordsID NOT IN (" . $KeywordsIDs . ");";
      echo $sql . "<br>";
      $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
      $nbr = mysqli_affected_rows ($conn);
      echo "nbr=" . $nbr . "<br>";
      
    /* update KeywordsXref table - add new */
      for ($i = 0; $i < count ($Keywords); $i++)  {
      
      /* get KeywordsID for keyword */
        $sql = "SELECT KeywordsID " .
               "FROM Keywords " .
               "WHERE Keyword = '" . $Keywords[$i] . "';";
        echo $sql . "<br>";
        $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
        $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
        $KeywordsID = $row['KeywordsID'];

      /* is article/keyword already in file? */
        $sql = "SELECT * " .
               "FROM KeywordXref " .
               "WHERE ArticlesID = " . $ArticlesID . " " .
               "  AND KeywordsID = " . $KeywordsID;
        echo $sql . "<br>";
        $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
        $nbr = mysqli_affected_rows ($conn);
        echo "nbr=" . $nbr . "<br>";

        if ($nbr < 1)  {
        /* add new keyword */
          $sql = "INSERT KeywordXref (KeywordsID, ArticlesID) VALUES (" . $KeywordsID . "," . $ArticlesID . ");";
          echo $sql . "<br>";
          $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
        }
      }

    /* don't delete any existing links because we may have a local copy etc.. this must be done manually now */
    /* update Links table - delete existing */
      #if (count ($links) > 0)  {
      #  $sql = "DELETE FROM Links " .
      #         "WHERE ArticlesID = " . $ArticlesID . ";";
      #  echo $sql . "<br>";
      #  echo "<p class='red'>Links NOT deleted because of additional info!!!</p>";
      #  ##### don't delete links - too much additional info ##### $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
      #  $nbr = mysqli_affected_rows ($conn);// or die (mysqli_error ($conn));
      #  echo "nbr=" . $nbr . "<br>";
      #}
    /* don't delete any existing links because we may have a local copy etc.. this must be done manually now */
      
    /* update Links table - add new */
      for ($i = 0; $i < count ($links); $i++)  {
        $l = trim ($links[$i]);  // remove spaces
        if (substr ($l, -1, 1) == '/')  $l = substr ($l, 0, -1);  // remove trailing backslash
        if ($i < count ($linksdescription))  {
          $d = trim ($linksdescription[$i]);  // remove spaces
          if (substr ($d, -1, 1) != '.')  $d .= ".";  // add trailing period if missing
        }
        else  $d = "";
        $sql = "INSERT Links (ArticlesID, Link, Description, OriginalLink) VALUES (" . $ArticlesID . ",'" . $l . "','" .
               mysqli_real_escape_string ($conn, $d) . "','" . $l . "');";
        echo $sql . "<br>";
        $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
      }

    /* testing */
      #mysqli_rollback ($conn);  // testing!!!
      mysqli_commit ($conn);
    /* testing */

      echo "<p class='green'>Database updated!</p>";
    }
?>
<script type="text/javascript">

  useBSNns = true;

  var options = {
    script:"getAuthors.php?json=true&",
    varname:"input",
    json:true,
    callback: function (obj) { document.getElementById('AuthorsID').value = obj.id; }
  };
  var as_json = new bsn.AutoSuggest('NomDePlume', options);
</script>

  </div>

<?php
  mysqli_close ($conn);
?>

  <div id="foot">
    <?php include ($path . "footer.php"); ?>
  </div>

  </body>
</html>


