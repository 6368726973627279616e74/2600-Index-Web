<!--
  buildOpenSource.php

  Author:      wrepp
  Date:        10/20/2011
  Description: build Open Source data
  Notes: 
    application user needs special access
      GRANT SELECT, FILE ON * . * TO "App2600"@"localhost";
      GRANT LOCK_TABLES, SHOW VIEW for 2600 database to App2600 user with MySQL Administrator
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">


<?php
  $path = "../";

  include ($path . "db.php");

/* debug logic */
#  $debug = "yes";
/* debug logic */

/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);


function doExec ($cmd)  {

  echo "cmd=" . $cmd . "<br>";
  exec ($cmd, $output, $returnValue);

  for ($i = 0; $i < count ($output); $i++)  {
    echo $output[$i] . "<br>";
  };
  if ($returnValue == "0")  {
    echo "return=" . $returnValue;
  }
  else  {
    echo "<text class='red'>" . "return=" . $returnValue . "</text>";
  }
  echo "<br>";

  return isset($output[0]) ? $output[0] : '';
}  // doExec


function doSourceDirectoryPHP ($dir, $dbuser, $dbpass)  {

  $dh = opendir ("../" . $dir);
  $files = array ();
  
  while ($file = readdir ($dh)) {

    //if (!is_dir ($file) && substr ($file,-1,1) != '~')  {
    if (pathinfo ($file, PATHINFO_EXTENSION) == "php")  {
      $files[] = $file;
    }
  }  // while
  closedir ($dh);

  foreach ($files as $file)  {

  /* don't publish local database user/password */
    if ($file == "db.php")  {
      $cmd = "source-highlight -i ../db.php -o STDOUT | sed -e 's/" . $dbuser . "/*your user here*/' -e 's/" . $dbpass . "/*your password here*/' > ../OpenSource/PHP/" . $dir . basename ($file) . ".html";
    }
    else  {
      $cmd = "source-highlight -i ../" . $dir . $file . " -o ../OpenSource/PHP/" . $dir . basename ($file) . ".html";
    }
    doExec ($cmd);

  /* don't publish local database user/password */
    if ($file == "db.php")  {
      // works but filename in zip is "-"
      // $cmd = "sed -e 's/" . $dbuser . "/*your user here*/' -e 's/" . $dbpass . "/*your password here*/' ../db.php | zip -j ../OpenSource/PHP/db.zip -";
      $cmd = "sed -e 's/" . $dbuser . "/*your user here*/' -e 's/" . $dbpass . "/*your password here*/' ../db.php > db.php; zip -j ../OpenSource/PHP/db.zip db.php; rm db.php";
      // check this worked!
      # grep  -i -E --color "(*user*)|(*password*)" *
      # unzip -c db.zip
    }
    else  {
      $cmd = "zip -j ../OpenSource/PHP/" .$dir . basename ($file, ".php") . ".zip ../" . $dir . $file;
      #?? $cmd = "tar?gzip -c ../" . $dir . $file . " > ../OpenSource/PHP/" .$dir . basename ($file, ".php") . ".zip;
    }
    doExec ($cmd);
  }
}  // doDirectory
?>


<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>2600 Index</title>
    <?php include ($path . "header.php"); ?>
  </head>

  <body>

  <?php include ($path . "page.php"); ?>

  <div id="content">

  <p class="section">Build Open Source</p>


<?php


/********/
/* Data */
/********/
/* data sql data in .txt files */
  $cmd = "mysqldump -u " . $dbuser . " -p" . $dbpass . " -t -T../OpenSource/Data --ignore-table 2600.TableEditor_Lookups --ignore-table 2600.TableEditor_Tables --ignore-table 2600.TableEditor_HardcodedLookups 2600";
  doExec ($cmd);

  $cmd = "sed -i -e 's/5MEODMT6APB/Removed Per Authors Request/' ../OpenSource/Data/Authors.txt";
  doExec ($cmd);

/* delete zero length .sql files */
  array_map ("unlink", glob ("../OpenSource/Data/*.sql"));

/* create .html files with data */
  $sql = "SELECT TABLE_NAME " .
         "FROM information_schema.tables " .
         "WHERE table_schema = '2600' " .
         "  AND TABLE_TYPE = 'BASE TABLE' " .
         "  AND table_name NOT LIKE 'TableEditor%';";
  #echo $sql;
  $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

  while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
    $cmd = 'mysql -u ' . $dbuser . ' -p' . $dbpass . ' -e "SELECT * FROM ' . $row["TABLE_NAME"] . '" --html 2600 > ../OpenSource/Data/' . $row["TABLE_NAME"] .'.html';
    doExec ($cmd);
  }


/*******/
/* SQL */
/*******/
/* database sql in .sql files */
  $cmd = "mysqldump -u " . $dbuser . " -p" . $dbpass . " -d -T../OpenSource/SQL --ignore-table 2600.TableEditor_Lookups --ignore-table 2600.TableEditor_Tables --ignore-table 2600.TableEditor_HardcodedLookups 2600";
  doExec ($cmd);

/* convert .sql to .html */
  $sql = "SELECT TABLE_NAME " .
         "FROM information_schema.tables " .
         "WHERE table_schema = '2600' " .
         "  AND TABLE_TYPE IN ('BASE TABLE', 'VIEW') " .
         "  AND table_name NOT LIKE 'TableEditor%';";
  #echo $sql;
  $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

  while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
    $cmd = "source-highlight -i ../OpenSource/SQL/" . $row["TABLE_NAME"] . ".sql -o ../OpenSource/SQL/" . $row["TABLE_NAME"] . ".html";
    doExec ($cmd);
  }


/*******/
/* PHP */
/*******/
  doSourceDirectoryPHP ("", $dbuser, $dbpass);
  doSourceDirectoryPHP ("OpenSource/", $dbuser, $dbpass);
  doSourceDirectoryPHP ("Reports/", $dbuser, $dbpass);
  doSourceDirectoryPHP ("Util/", $dbuser, $dbpass);
/*
  $dh = opendir ("../");
  $files = array ();
  
  while ($file = readdir ($dh)) {

    //if (!is_dir ($file) && substr ($file,-1,1) != '~')  {
    if (pathinfo ($file, PATHINFO_EXTENSION) == "php")  {
      $files[] = $file;
    }
  }  // while
  closedir ($dh);

  foreach ($files as $file)  {
    $cmd = "source-highlight -i ../" . $file . " -o ../OpenSource/PHP/" . basename ($file) . ".html";
    doExec ($cmd);

    $cmd = "zip ../OpenSource/PHP/" . basename ($file, ".php") . ".zip ../" . $file;
    doExec ($cmd);
  }
*/

/* style sheet */
  $cmd = "source-highlight -i ../style.css -o ../OpenSource/PHP/style.css.html";
  doExec ($cmd);

  $cmd = "zip ../OpenSource/PHP/style.zip ../style.css";
  doExec ($cmd);


/********/
/* java */
/********/
  $cmd = "source-highlight -i /home/wepp/workspace/2600/src/wepp/Load2600Issue.java -o ../OpenSource/Load2600Issue.java.html";
  doExec ($cmd);


/* tools.html */
  $fh = fopen ("../OpenSource/tools.html", "w");

  fwrite ($fh, "<li>");
  fwrite ($fh, "PHP: " . phpversion ());
  fwrite ($fh, "</li>");

  fwrite ($fh, "<li>");
  fwrite ($fh, "MySQL: " . mysqli_get_server_info ($conn));
  fwrite ($fh, "</li>");

  fwrite ($fh, "<li>");
  fwrite ($fh, "Apache: " . apache_get_version ());
  fwrite ($fh, "</li>");

  fwrite ($fh, "<li>");

    $cmd = 'bash --version | grep "GNU bash"';
    $output = doExec ($cmd);
    fwrite ($fh, $output . "<br>");

    $cmd = "kate --version | grep Kate:";
    $output = doExec ($cmd);
    fwrite ($fh, $output . "<br>");

    $cmd = "mysql --version";
    $output = doExec ($cmd);
    fwrite ($fh, $output . "<br>");

    $cmd = "mysqldump --version";
    $output = doExec ($cmd);
    fwrite ($fh, $output . "<br>");

    $cmd = 'lftp --version | grep "LFTP |"';
    $output = doExec ($cmd);
    fwrite ($fh, $output . "<br>");

    $cmd = 'wget -V | grep "GNU Wget"';
    $output = doExec ($cmd);
    fwrite ($fh, $output . "<br>");

    $cmd = "curl --version | grep curl";
    $output = doExec ($cmd);
    fwrite ($fh, $output . "<br>");

    $cmd = 'source-highlight --version | grep "GNU Source-highlight"';
    $output = doExec ($cmd);
    fwrite ($fh, $output . "<br>");

    $cmd = 'zip -v | grep " Zip "';
    $output = doExec ($cmd);
    fwrite ($fh, $output . "<br>");

  fwrite ($fh, "</li>");

/*
  fwrite ($fh, "<li>");
  $cmd = 'dpkg -s eclipse | grep "Version:"';
  $output = doExec ($cmd);
  fwrite ($fh, "Eclipse: " . $output);
  fwrite ($fh, "</li>");
*/

  fwrite ($fh, "<li>");
  $cmd = "java -version 2>&1";  // redirect stderr to stdout
  $output = doExec ($cmd);
  fwrite ($fh, $output);
  fwrite ($fh, "</li>");

  fclose ($fh);
?>

  </div>

<?php
  mysqli_close ($conn);
?>

  <div id="foot">
    <?php include ($path . "footer.php"); ?>
  </div>
  </body>
</html>

