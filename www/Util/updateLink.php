<?php

/*
  updateLink.php

  Author:      wrepp
  Date:        07/17/2009
  Description: update Status column of Links table
  Notes:       http://localhost/2600/Util/updateLink?id=430&s=Err&d=yes
*/

  $path = "../";

  include ($path . "db.php");

/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);

/* parameters */
  $LinksID    = $_GET['id'];
  $Status     = $_GET['s'];
  $debug      = strtolower ($_GET['d']);

/* update */
  $sql = "UPDATE Links " .
          "SET Status = '" . $Status . "', " .
          "    Validated = '" . date ("Y-m-d H:i:s") . "' " .
          "WHERE LinksID = " . $LinksID . ";";
  #echo $sql . "<br>";
  if ($debug != "yes")  {
    mysqli_query ($conn, $sql) or die ($sql . ' : ' . mysqli_error ($conn));
    echo "Status '" . $Status . "' updated!";
    #echo $sql;
  }
  else  echo $sql;


/* wrap it up */
  mysqli_close ($conn);

?>
