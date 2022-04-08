<!--
  search.php

  Author:      wrepp
  Date:        10/13/2008
  Description: search page for 2600 index web site
  Notes:       table editors only available if sub-directory found
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php
  global $firstyear;
  global $lastyear;

  include ("db.php");

/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);

?>

<html>
  <head>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
  <title>2600 Search</title>
  <?php include ("header.php"); ?>
  </head>

  <body>

  <?php include ("page.php"); ?>

  <div id="content">
    <noscript>
    <p class='red'>This page requires Javascript to function properly!!</p>
    </noscript>

    <script language="javascript" type="text/javascript">
    function showKeywords (c)  {
      //window.showModalDialog ("keywords.php?o=Popup", "", "dialogHeight: 800px; dialogWidth: 660px;");
      window.open ("keywords.php?o=Popup", "Keywords", "Height=800px, Width=660px");
    }
    </script>

    <p class="section">Search Articles</p>

<?php
  /* post back? */
    if (isset($_POST['search']))  {

      $FromYear = $_POST["FromYear"];
      $ToYear = $_POST["ToYear"];
      $TitleOption = $_POST["TitleOption"];
      $TitleText = $_POST["TitleText"];
      $Synopsis = $_POST["Synopsis"];
      $Keyword = $_POST["Keyword"];
      $Author = $_POST["Author"];
      $Code = $_POST["Code"];

/*
      echo "FromYear=" . $FromYear . "=<br>";
      echo "ToYear=" . $ToYear . "=<br>";
      echo "TitleOption=" . $TitleOption . "=<br>";
      echo "TitleText=" . $TitleText . "=<br>";
      echo "Synopsis=" . $Synopsis . "=<br>";
      echo "Keyword=" . $Keyword . "=<br>";
      echo "Author=" . $Author . "=<br>";
      echo "Code=" . $Code . "=<br>";
*/
?>
      <p>
      <a href="search.php">New search</a>
      </p>

<?php

    /* validate search text - escape special chars */
      $FromYear = intval ($FromYear);

      $ToYear = intval ($ToYear);

      $TitleOption = intval ($TitleOption);

      $TitleText = substr ($TitleText, 0, 16);
      #$TitleText = str_replace ("'", "\'", $TitleText);
      #$TitleText = str_replace ('"', '\"', $TitleText);
      #echo "TitleText=" . $TitleText . "=<br>";

      $Synopsis = substr ($Synopsis, 0, 16);

      $Keyword = intval ($Keyword);

      $Author = intval ($Author);

      $Code = substr ($Code, 0, 1);

    /* build search sql */
      $sql = "SELECT DISTINCT Issue, Title, PageTitle, IssuesID, ArticlesID " .
             "FROM vw_Search " .
             "WHERE IssueYear BETWEEN " . $FromYear . " AND " . $ToYear . " ";
      if (strlen ($TitleText) > 0)  {
        if ($TitleOption == 1)  {
          $sql .= "AND Title LIKE '%" . $TitleText . "%' ";
        }
        if ($TitleOption == 2)  {
          $sql .= "AND Title LIKE '" . $TitleText . "%' ";
        }
      }
      if (strlen ($Synopsis) > 0)  {
        $sql .= " AND Synopsis LIKE '%" . $Synopsis . "%' ";
      }
      if ($Keyword > 0)  {
        $sql .= " AND KeywordsID = '" . $Keyword . "' ";
      }
      if ($Author > 0)  {
        $sql .= " AND AuthorsID = '" . $Author . "' ";
      }
      if (strlen ($Code) > 0)  {
        $sql .= " AND Code = '" . $Code . "' ";
      }

      $sql .= "ORDER BY IssueYear, `Quarter`, Title LIMIT 100";
      #echo $sql . "<br>";

      $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

      $nbr = mysqli_num_rows($result);
      if ($nbr > 15 && $nbr < 100)  echo $nbr . " results found.";
      else if ($nbr >=  100)  echo "100 or more found (maximum returned is 100).";
      echo "<br>";
      echo "<br>";

  /* display results */
      echo '<table width="75%" cellspacing="1px" cellpadding="2px" >';
      $inbr = 0;
      while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
        #echo $row["IssueYear"] . " " . $row['Title'] . "<br>";
?>
      <tr bgcolor=<?php if ($inbr % 2)  echo "palegreen"; else  echo "HONEYDEW";?> >
        <td>
          <?php echo $row['Issue'] ?>
        </td>
        <td>
          <?php 
            echo $row['Title'];
            //if ($row['PageTitle'] != "")  echo "&nbsp; &nbsp; &lt;or&gt; &nbsp; &nbsp; " . $row['PageTitle'];
           ?>
        </td>
        <td>
          <?php
            if ($row['PageTitle'] != "")  echo $row['PageTitle'];
          ?>
        </td>
        <td>
          <?php echo "<a href='article.php?i=" . $row['IssuesID'] . "&a=" . $row['ArticlesID'] . "'>..</a>" ?>
        </td>
      </tr>
<?php
        $inbr++;
      }
      echo "</table>";

    /* display number of results */
      echo "<br>";
      if ($inbr == 0)  echo "No results found.";
      else if ($inbr == 1)  echo "1 result found.";
      else if ($inbr >=  100)  echo "100 or more found (maximum returned is 100).";
      else  echo $inbr . " results found.";
      echo "<br>";
    }
    else  {
?>
    <form method="post" action="">
    <p>
    &nbsp;Search years from 
    <select id="FromYear" name="FromYear">
<?php
    $sql = "SELECT DISTINCT IssueYear FROM Issues ORDER BY IssueYear ASC;";
    #echo $sql;
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
      $id = $row["IssueYear"];
      $val = $row["IssueYear"];
?>
      <option value="<?php echo $id ?>"><?php echo $val ?></option>
<?php 
    }
?>
    </select>

    thru 
    <select id="ToYear" name="ToYear">
<?php
    $sql = "SELECT DISTINCT IssueYear FROM Issues ORDER BY IssueYear DESC;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
      $id = $row["IssueYear"];
      $val = $row["IssueYear"];
?>
      <option value="<?php echo $id ?>"><?php echo $val ?></option>
<?php 
    }
?>
    </select>
    (inclusive)
    </p>

    <p>
    &nbsp;Where title
    <select id="TitleOption" name="TitleOption">
      <option value="1" selected>
        Contains
      </option>
      <option value="2">
        Starts with
      </option>
    </select>
    <input id="TitleText" name="TitleText" type="text" size="16" maxlength="16">
    </p>

    <p>
    And synopsis contains
    <input id="Synopsis" name="Synopsis" type="text" size="16" maxlength="16">
    </p>

    <p>
    &nbsp;With keyword 
    <select id="Keyword" name="Keyword">
      <option value="0" selected>All</option>
<?php
    $sql = "SELECT KeywordsID, Keyword FROM Keywords ORDER BY Keyword ASC;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
      $id = $row["KeywordsID"];
      $val = $row["Keyword"];
?>
      <option value="<?php echo $id ?>"><?php echo $val ?></option>
<?php 
    }
?>
    </select>
    <a href="javascript:showKeywords ();">List..</a>
    </p>

    <p>
    &nbsp;For Author
    <select id="Author" name="Author">
      <option value="0" selected>All</option>
<?php
    $sql = "SELECT AuthorsID, NomDePlume FROM Authors ORDER BY NomDePlume ASC;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
      $id = $row["AuthorsID"];
      $val = $row["NomDePlume"];
?>
      <option value="<?php echo $id ?>"><?php echo $val ?></option>
<?php 
    }
?>
    </select>
    </p>

    <p>
    &nbsp;For Code
    <select id="Code" name="Code">
      <option value="" selected>All</option>
      <option value="A">In article text</option>
      <!--<option value="C">@ <a href="http://www.2600.com/code/">2600 code web site</a></option>-->
      <option value="C">@ 2600 code web site</a></option>
      <option value="N">None</option>
      <option value="S">See Links</option>
    </select>

    <p>
    <input type="submit" id="search" name="search" value="Search...">
    </p>
    </form>
    &nbsp;(only the information on this site is searched, this is NOT a full article text search)

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


