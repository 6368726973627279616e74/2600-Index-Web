<!--
  TableEditor.php

  Author:      wrepp
  Date:        01/14/2009
  Description: start page for TableEditor web site
  Notes:
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>TableEditor</title>
    <?php include ("header.php"); ?>
  </head>

  <body>

    <noscript>
    <p class='red'>This page requires Javascript to function properly!!</p>
    </noscript>

    <h2>
    Table Editor
    </h2>

    <p class="section">Tables</p>

<?php
    include ("db.php");

/* mysql */
    $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
    mysqli_select_db ($conn, $dbname);

    $sql = "SELECT * " .
           "FROM INFORMATION_SCHEMA.TABLES " .
           "WHERE TABLE_SCHEMA = '" . $dbname . "' " .
           "  AND TABLE_TYPE = 'BASE TABLE';";
    #echo $sql . "<br>";

    $result = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));
?>

    <table width="600px" cellspacing="1px" cellpadding="2px">
    <thead>
    <tr bgcolor="lightgrey">
      <th>Table</th>
      <th>Comment</th>
    </tr>
    </thead>

<?php
    $inbr = 1;
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {

      echo "<tr bgcolor=";
      if ($inbr % 2)  echo "palegreen";
      else  echo "honeydew";
      echo ">";

      echo "<td>";
      echo "<a href='TableEditor_Table.php?t={$row['TABLE_NAME']}'>{$row['TABLE_NAME']}</a>";
      echo "</td>";
      echo "<td>" . substr ($row['TABLE_COMMENT'], 0, strpos ($row['TABLE_COMMENT'], ";") -1) . "</td>";

      echo "</tr>";
      $inbr++;
    }
    echo "</table>";


    mysqli_close ($conn);
?>

    <?php include ("footer.php"); ?>
  </body>
</html>


