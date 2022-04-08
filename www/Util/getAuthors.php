<?php

/*
  getAuthors.php

  Author:      wrepp
  Date:        10/13/2008
  Description: get list of authors for auto-suggest.
  Notes:       no html should be put into this file! only return html from the code below!
*/

  $path = "../";

  include ($path . "db.php");

/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);

  $input = strtolower ($_GET['input']);
  $len = strlen ($input);

  $aResults = array ();

  if ($len)  {
    $sql = "SELECT * " .
           "FROM Authors " .
           "WHERE NomDePlume LIKE '" . $input . "%' " .
           "ORDER BY NomDePlume;";
    #echo $sql . "<br>";
    $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
      $aResults[] = array("id"=>$row['AuthorsID'], "value"=>$row['NomDePlume'], "info"=>$row['Name']);
    }
  }

  header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
  header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
  header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header ("Pragma: no-cache"); // HTTP/1.0



	if (isset($_REQUEST['json']))
	{
		header("Content-Type: application/json");
	
		echo "{\"results\": [";
		$arr = array();
		for ($i=0;$i<count($aResults);$i++)
		{
			$arr[] = "{\"id\": \"".$aResults[$i]['id']."\", \"value\": \"".$aResults[$i]['value']."\", \"info\": \"\"}";
		}
		echo implode(", ", $arr);
		echo "]}";
	}
	else
	{
		header("Content-Type: text/xml");

		echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?><results>";
		for ($i=0;$i<count($aResults);$i++)
		{
			echo "<rs id=\"".$aResults[$i]['id']."\" info=\"".$aResults[$i]['info']."\">".$aResults[$i]['value']."</rs>";
		}
		echo "</results>";
	}

/* wrap it up */
  mysqli_close ($conn);

?>
