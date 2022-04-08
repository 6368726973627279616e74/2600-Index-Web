<!--
  TableEditor_Record.php

  Author:      wrepp
  Date:        01/15/2009
  Description: Table record for TableEditor web site
  Notes:

  01/10/2012 wre added mysqli_real_escape_string and str_replace.

-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php

function setQuote ($DataType) {

  #echo "DataType=" . $DataType . "=<br>";

  switch ($DataType)  {
    case "int" :
    case "tinyint" :
      $quote = "";
      break;
    default :
      $quote = "\"";
      break;
  }
  return $quote;
}

/* query parameters */
  $t = @$_GET["t"];
  $tablename = $t;

  $id = @$_GET["id"];

  $o = @$_GET["o"];
  if ($o == "")  $o = "view";
  $opt = $o;

?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>TableEditor_Record</title>
    <?php include ("header.php"); ?>

    <script language="javascript" type="text/javascript">
    function setMaxLength (Object, MaxLength)  {
      //alert (Object.value.length);
      //alert (MaxLength);
      if (Object.value.length > MaxLength)  Object.value = Object.value.substring (0, MaxLength);
      //return (Object.value.length <= MaxLength);
    }
    </script>
  </head>

  <body>

    <noscript>
    <p class='red'>This page requires Javascript to function properly!!</p>
    </noscript>

    <h2>
    Table Editor - <?php echo $tablename ?>
    </h2>

<?php
    include ("db.php");


  /* mysql */
    $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
    mysqli_select_db ($conn, $dbname);

?>
    
      <a href="javascript:window.history.back ();">back</a>
      <a href="TableEditor.php">select another table</a>
    <br>

<?php
  /* post back? */
    if (!isset($_POST['submit']))  {

  /* get table id */
    $sql = "SELECT * " .
           "FROM TableEditor_Tables " .
           "WHERE TableName = '" . $tablename . "';";
    #echo $sql . "<br>";

    $tableid = "";
    $result = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));
    $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
    $tableid = $row['TablesID'];

  /* get column names */
    $sql = "SELECT * " .
           "FROM INFORMATION_SCHEMA.COLUMNS " .
           "WHERE TABLE_NAME = '" . $tablename . "' " .
           "  AND TABLE_SCHEMA = '" . $dbname . "' " .
           "ORDER BY ORDINAL_POSITION;";
    #echo $sql . "<br>";

    $resultCol = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));

  /* get first record */
    $rowCol = mysqli_fetch_array ($resultCol, MYSQLI_ASSOC);
    $primarycol = $rowCol['COLUMN_NAME'];

  /* reset to first */
    $resultCol = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));

?>
<?php

  /* display record */
    $sql = "SELECT * " . 
           "FROM " . $tablename . " " .
           "WHERE " . $primarycol . "=" . $id . ";";
    #echo $sql . "<br>";

    $resultData = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));

    $rowData = mysqli_fetch_array ($resultData, MYSQLI_NUM);
?>
    <form method="post" action="">

<!-- put get variables into hidden form elements for post -->
<!--    <input id="tablename" name="tablename" type="hidden" size="64" maxlength="64" value="<?php #echo $tablename ?>"> -->
    <input id="primarycol" name="primarycol" type="hidden" size="64" maxlength="64" value="<?php echo $primarycol ?>">

<!-- data table -->
    <table width="100%" cellspacing="1px" cellpadding="2px">

<?php

    $inbr = 1;
    while ($rowCol = mysqli_fetch_array ($resultCol, MYSQLI_ASSOC))  {

    /* display record data */
?>
      <tr bgcolor="honeydew" >
        <td bgcolor="lightgray" > 
          <?php echo $rowCol['COLUMN_NAME']; ?>
		  <?php if ($rowCol['IS_NULLABLE'] == 'NO')  echo "*"; ?>
        </td>
        <td bgcolor="lightblue" >
<?php
      /* option */
        if ($opt == "view" || $opt == "delete")  $disable = "disabled";
        else  $disable = "";

      /* set default value */
        $rowValue = $rowData[$inbr-1];
        if ($opt == "add")  {
		/*
          $sql = "SELECT COLUMN_DEFAULT " .
                  "FROM INFORMATION_SCHEMA.COLUMNS " .
                  "WHERE TABLE_NAME = '" . $tablename . "' " .
                  "  AND COLUMN_NAME = '" . $rowCol['COLUMN_NAME'] . "' " .
                  "  AND TABLE_SCHEMA = '" . $dbname . "'";
          #echo $sql . "<br>";

          $resultDefault = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));
          $rowDefault = mysqli_fetch_array ($resultDefault, MYSQLI_ASSOC);
          $rowValue = $rowDefault['COLUMN_DEFAULT'];
		*/
		  $rowValue = $rowCol['COLUMN_DEFAULT'];
        }

      /* display lookup or actual data */
        $displaydata = true;
        if ($tableid != "")  {
          $sql = "SELECT * " .
                 "FROM TableEditor_Lookups " .
                 "WHERE TablesID = " . $tableid . " " .
                 "  AND ColumnName = '" . $rowCol['COLUMN_NAME'] . "';";
          #echo $sql . "<br>";

          $resultLookup = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));
          if ($rowLookup = mysqli_fetch_array ($resultLookup, MYSQLI_ASSOC))  {

            $sql =  "SELECT " . $rowLookup['LookupColumnID'] . ", " . $rowLookup['LookupColumnValue'] . " " .
                    "FROM " . $rowLookup['LookupTableName'] . " ";
          /* add where clause if applicable */                    
            if ($rowLookup['LookupColumnWhere'] != "")  $sql .= " WHERE " . $rowLookup['LookupColumnWhere'] . " ";
            if ($rowLookup['LookupNumber'] > 0)  $sql .= " WHERE LookupNumber = " . $rowLookup['LookupNumber'] . " ";
          /* add order by clause if applicable */
            if ($rowLookup['LookupColumnOrderBy'] != "")  $sql .= " ORDER BY " . $rowLookup['LookupColumnOrderBy'] . " ";
            
          /* end statement */
            $sql .= ";";
            #echo $sql . "<br>";
?>
            <select name="<?php echo $rowCol['COLUMN_NAME'] ?>" <?php echo $disable ?> >
<?php
            $resultLookup = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));
            while ($rowLookup = mysqli_fetch_array ($resultLookup, MYSQLI_NUM))  {
?>
              <option value="<?php echo $rowLookup[0] ?>"
                             <?php if ($rowLookup[0] == $rowValue)  echo " selected ";  ?> > <?php echo $rowLookup[1] ?>
              </option>
<?php
            }
?>
            </select>
<?php
            $displaydata = false;
          }
        }

      /* display data */
        if ($displaydata)  {

        /* never enter auto increment fields - */
          $savedisable = $disable;
          if ($rowCol['EXTRA'] == "auto_increment")  {
            $disable = "disabled";
          };

        /* by data type */
          switch ($rowCol['DATA_TYPE'])  {
            case "int" :
              echo "<input name='" . $rowCol['COLUMN_NAME'] . "' size='10' " .
                   "maxlength='10' " . " value='" . $rowValue . "' " . $disable . " >";
              break;
			case "decimal" :
              echo "<input name='" . $rowCol['COLUMN_NAME'] . "' size='16' " .
                   "maxlength='16' " . " value='" . $rowValue . "' " . $disable . " >";
              break;
            case "tinyint" :  // boolean
              echo "<input name='" . $rowCol['COLUMN_NAME'] . "' size='3' " .
                   "maxlength='3' " . " value='" . $rowValue . "' " . $disable . " >";
              break;
            case "date" :
            case "datetime" :
#              break;
            case "char" :
            case "varchar" :
              if ($rowCol['CHARACTER_MAXIMUM_LENGTH'] > 80)  {
                echo "<textarea name='" . $rowCol['COLUMN_NAME'] . "' cols='70' rows='4' ";
                echo "onkeypress='setMaxLength (this," . $rowCol['CHARACTER_MAXIMUM_LENGTH'] . ");' ";
                echo " " . $disable . " >";
                echo str_replace ("\"", "&quot;", $rowValue);
                echo "</textarea>";
              }
              else  {
                echo "<input name='" . $rowCol['COLUMN_NAME'] . "' size='" . $rowCol['CHARACTER_MAXIMUM_LENGTH'] . "' " .
                     "maxlength='" . $rowCol['CHARACTER_MAXIMUM_LENGTH'] . "' " . " value=\"" . 
                     str_replace ("\"", "&quot;", $rowValue) . "\" " . $disable . " >";
              }
            break;
          }
        /* reset */
          $disable = $savedisable;
        }
?>
        </td>
        <td>
          <?php echo $rowCol['COLUMN_COMMENT'] ?>
        </td>
      </tr>
<?php
      $inbr++;
    }
?>
    </table>
    * = required <br>

    <p>
    <input type="submit" id="submit" name="submit" value="<?php echo ucfirst ($opt); ?> ...">
    </p>
    </form>
	
<?php
    }
    else  {

    /* display get parameters */
      echo "tablename=" . $tablename . "=<br>";
      echo "id=" . $id . "=<br>";
      echo "opt=" . $opt . "=<br>";

    /* display posted parameters */
      $primarycol = trim ($_POST["primarycol"]);
      echo "primarycol=" . $primarycol . "=<br>";

    /* get column names */
      $sql = "SELECT * " .
             "FROM INFORMATION_SCHEMA.COLUMNS " .
             "WHERE TABLE_NAME = '" . $tablename . "' " .
           "  AND TABLE_SCHEMA = '" . $dbname . "' " .
             "ORDER BY ORDINAL_POSITION;";
      #echo $sql . "<br>";

      $resultCol = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));
      while ($rowCol = mysqli_fetch_array ($resultCol, MYSQLI_ASSOC))  {
        echo $rowCol['COLUMN_NAME'] . "=" . isset ($_POST[$rowCol['COLUMN_NAME']]) . "=<br>";
      }
    /* reset */
      $resultCol = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));

      $sql = "";
      switch ($opt)  {

        case "view" :
          $sql = "";
          echo "<b>View Only!<br></b>";
          break;

        case "add" :
          $comma = "";
          $values = "";
          $sql = "INSERT " . $tablename . " (";
          while ($rowCol = mysqli_fetch_array ($resultCol, MYSQLI_ASSOC))  {
         /* don't insert auto increment columns */
            if ($rowCol['EXTRA'] != "auto_increment")  {
              $sql .= $comma . $rowCol['COLUMN_NAME'];
              $quote = setQuote ($rowCol['DATA_TYPE']);
              $values .= $comma . $quote . mysqli_real_escape_string ($conn, $_POST[$rowCol['COLUMN_NAME']]) . $quote;
              $comma = ", ";
            };
          }
          $sql .= ") VALUES (" . $values . ");";
          break;

        case "delete" :
          $sql = "DELETE FROM " .$tablename . " ";
          $sql .= "WHERE " . $primarycol . " = " . $id . ";";
          break;

        case "update" :
          $comma = "";
          $sql = "UPDATE " . $tablename . " SET ";
          while ($rowCol = mysqli_fetch_array ($resultCol, MYSQLI_ASSOC))  {
         /* don't update auto increment columns */
            if ($rowCol['EXTRA'] != "auto_increment")  {
              $quote = setQuote ($rowCol['DATA_TYPE']);
              $sql .= $comma . $rowCol['COLUMN_NAME'] . "=" . $quote . mysqli_real_escape_string ($conn, $_POST[$rowCol['COLUMN_NAME']]) . $quote;
              $comma = ", ";
            }
          }
          $sql .= " WHERE " . $primarycol . " = " . $id . ";";
          break;
      }

      echo $sql . "<br>";

    /* do it */
      if (strlen ($sql) > 0)  {
        $result = mysqli_query ($conn, $sql) or die ('error' . mysqli_error ($conn));
      }
?>
      <a href="TableEditor_Table.php?t=<?php echo $tablename;?>">back to list</a>
<?php      
    }

    mysqli_close ($conn);
?>

    <?php include ("footer.php"); ?>
  </body>
</html>


