<!--
  TableEditor_Table.php

  Author:      wrepp
  Date:        01/14/2009
  Description: Table page for TableEditor web site
  Notes:
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php

/* query parameters */
/* table name */
  $t = @$_GET["t"];
  $tablename = $t;

/* page number */
  $p = @$_GET["p"];
  if ($p == "" || $p < 0)  $p = 0;
  $page = $p;

/* where field */
  $wf = @$_GET["wf"];
  if ($wf == "")  $wf = "none";  // all records

/* where value */
  $wv = @$_GET["wv"];
  if ($wf == "none")  $wv = "";  // all records
  
/* defaults */
  $nbrpage = 20;
  $orderby = "1";  // assumed ID column
  if ($wf == "none")  $wherefilter = "1=1";
  else  $wherefilter = $wf . " LIKE '%" . $wv . "%'";
  
  #echo "tablename=" . $tablename . "<br>";
  #echo "page=" . $page . "<br>";
  #echo "wherefilter=" . $wherefilter . "<br>";
?>


<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>TableEditor_Table</title>
    <?php include ("header.php"); ?>
  </head>

  <body>

    <noscript>
    <p class='red'>This page requires Javascript to function properly!!</p>
    </noscript>

    <h2>
    Table Editor - <?php echo $tablename ?>
    </h2>

<!--    <p class="section">Records for '<?php #echo $tablename ?>' table</p>
-->

<?php
    include ("db.php");


  /* paging */
    $firstrec = $page * $nbrpage;
    $lastrec = $firstrec + $nbrpage;

  /* mysql */
    $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
    mysqli_select_db ($conn, $dbname);
?>

      <a href="TableEditor.php">select another table</a>
    <br>


    <a href="TableEditor_Record.php?t=<?php echo $tablename ?>&amp;id=0&amp;o=add">add</a>
    <br>
    

      <form method="get" action="">

      <select name="wf" id="wf">
      <option  value="none">&lt;none&gt;</option>
<?php
    /* column names for find */
      $sql = "SELECT * " .
             "FROM INFORMATION_SCHEMA.COLUMNS " .
             "WHERE TABLE_NAME = '" . $tablename . "' " .
           "  AND TABLE_SCHEMA = '" . $dbname . "' " .
             "ORDER BY ORDINAL_POSITION;";
      #echo $sql . "<br>";

      $resultCol = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));
      while ($rowCol = mysqli_fetch_array ($resultCol, MYSQLI_ASSOC))  {
?>
        <option value="<?php echo $rowCol['COLUMN_NAME'] ?>"
                       <?php if ($rowCol['COLUMN_NAME'] == $wf)  echo " selected ";  ?> > <?php echo $rowCol['COLUMN_NAME'] ?>
<?php
      }
?>
      </select>
      <input name="wv" id="wv" size="10" maxlength="10" value="<?php echo $wv; ?>">
      <input name="t" id="t" type="hidden" value="<?php echo $tablename ?>">
      <input type="submit" id="submit" name="submit" value="find">
      </form>
    <br>

<!--  data -->
    <table width="100%" cellspacing="1px" cellpadding="2px">
    <thead>
    <tr bgcolor="lightgrey">
      <th>u</th>
      <th>d</th>

<?php
  /* get table info if any */
    $sql = "SELECT * " .
           "FROM TableEditor_Tables " .
           "WHERE TableName = '" . $tablename . "';";
    #echo $sql . "<br>";

    $tableid = "";
    $result = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
      $tableid = $row['TablesID'];
      $nbrpage = $row['NumberPerPage'];
      if ($row['OrderBy'] != "")  $orderby = $row['OrderBy'];
    }

  /* get column names */
    $sql = "SELECT * " .
           "FROM INFORMATION_SCHEMA.COLUMNS " .
           "WHERE TABLE_NAME = '" . $tablename . "' " .
           "  AND TABLE_SCHEMA = '" . $dbname . "' ;";
    #echo $sql . "<br>";

    $result = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));

    $nbrcols = mysqli_num_rows($result);
    $columns = "";
    $comma = "";
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
      echo "<th>" . $row['COLUMN_NAME'] . "</th>";

    /* is column a lookup? */
      $columnname = $row['COLUMN_NAME'];
      $columnsql = $columnname;
      if ($tableid != "")  {
        $sql = "SELECT * " .
               "FROM TableEditor_Lookups " .
               "WHERE TablesID = " . $tableid . " " .
               "  AND ColumnName = '" . $columnsql . "';";
        #echo $sql . "<br>";

        $wheresql = $columnsql;
        $result2 = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));
        while ($row2 = mysqli_fetch_array ($result2, MYSQLI_ASSOC))  {
          $columnsql =  "(SELECT " . $row2['LookupColumnValue'] . " " .
                        " FROM " . $row2['LookupTableName'] . " l ";
          if ($row2['LookupNumber'] > 0) $columnsql .= " WHERE l." . $row2['LookupColumnID'] . " = " . $row['COLUMN_NAME'] . " " .
                                                       "   AND l.LookupNumber = " . $row2['LookupNumber'] . " ";
          else {
            $columnsql .= " WHERE t." . $row2['ColumnName'] . " = l." . $row2['LookupColumnID'] . " ";
            if ($row2['LookupColumnWhere'] != "")  $columnsql .= "AND " . $row2['LookupColumnWhere'];
          }
          $wheresql = $columnsql . ")";
          $columnsql .= ") AS " . $row['COLUMN_NAME'];
        }
        if ($columnname == $wf)  {
          #echo "cn=" . $columnname . "," . $wf . "<br>";
          $wherefilter = $wheresql . substr ($wherefilter, strlen ($wf));
        }
      }
      $columns .= $comma . $columnsql;
      $comma = ", ";
    }
?>
    </tr>
    </thead>
    
<?php

  /* page thru records */
    $sql = "SELECT " . $columns . " " .
           "FROM " . $tablename . " t " .
           "WHERE " . $wherefilter . " " .
           "ORDER BY " . $orderby . " " .
           "LIMIT " . $firstrec . "," . $nbrpage . ";";
    #echo $sql . "<br>";

    $result = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));

    $inbr = 1;
    while ($row = mysqli_fetch_array ($result, MYSQLI_NUM))  {

    /* alternate rows */
      echo "<tr bgcolor=";
      if ($inbr % 2)  echo "palegreen";
      else  echo "honeydew";
      echo ">";

    /* update and delete options */
      echo "<td>";
      echo "<a href='TableEditor_Record.php?t=" . $tablename . "&amp;id=" . $row[0] . "&amp;o=update'>u</a>";
      echo "</td>";
      echo "<td>";
      echo "<a href='TableEditor_Record.php?t=" . $tablename . "&amp;id=" . $row[0] . "&amp;o=delete'>d</a>";
      echo "</td>";

    /* hard code first data column as unique primary key */
      echo "<td>";
      echo "<a href='TableEditor_Record.php?t=" . $tablename . "&amp;id=" . $row[0] . "&amp;o=view'>" . $row[0] . "</a>";
      echo "</td>";

    /* display record data */
      for ($icol = 1; $icol < $nbrcols; $icol++)  {
        echo "<td>";
        #if (strlen ($row[$icol]) > 64)  echo highlight_string (substr ($row[$icol], 0, 64)) . "...";
        if (strlen ($row[$icol]) > 64)  echo str_replace (array ("<", ">"), array ("&lt;", "&gt;"), substr ($row[$icol], 0, 64))  . "...";
        else  echo $row[$icol];
        echo "</td>\n";
      }

      echo "</tr>";
      $inbr++;
    }
    if ($inbr <= 1)  {
      echo "<tr><td>No Records Found.</td></tr>";
    }
    echo "</table>";
    echo "<br>";

  /* calculate number of pages */
    $sql = "SELECT COUNT(*) AS 'Nbr' " .
           "FROM " . $tablename . " t " .
           "WHERE " . $wherefilter . ";";
    #echo $sql . "<br>";

    $result = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));
    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    $nbrrows = $row['Nbr']; 
    $nbrpages = ceil ($nbrrows / $nbrpage);
    if ($lastrec > $nbrrows)  $lastrec = $nbrrows;

?>
      Page <?php echo $page+1 ?> of <?php echo $nbrpages ?>, row <?php echo $firstrec+1 ?> to <?php echo $lastrec ?>
<?php
      /* build where clause - field and value */
      $wc = "&amp;wf=" . $wf . "&amp;wv=" . $wv;
?>
      <a href="TableEditor_Table.php?t=<?php echo $tablename ?>&amp;p=0<?php echo $wc ?>">first</a>
      <a href="TableEditor_Table.php?t=<?php echo $tablename ?>&amp;p=<?php echo $page-1 ?><?php echo $wc ?>" <?php if ($page <= 0) echo "onclick='return false;'"; ?> >prev</a>
      <a href="TableEditor_Table.php?t=<?php echo $tablename ?>&amp;p=<?php echo $page+1 ?><?php echo $wc ?>" <?php if ($page+1 >= $nbrpages) echo "onclick='return false;'"; ?> >next</a>
      <a href="TableEditor_Table.php?t=<?php echo $tablename ?>&amp;p=<?php echo $nbrpages-1 ?><?php echo $wc ?>">last</a>
    </p>

<?php
    mysqli_close ($conn);
?>
    <br>
    <?php include ("footer.php"); ?>
  </body>
</html>


