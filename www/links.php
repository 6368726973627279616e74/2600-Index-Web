<!--
  links.php

  Author:      wrepp
  Date:        01/12/2010
  Description: seperate page for links for 2600 index web site
  Notes:
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php
function showIssue ($row) {
  return $row['Period'] . " " . $row['IssueYear'] . " [" . $row['Volume'] . ":" . $row['Quarter'] . "]";
}

  include ("db.php");

/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);
?>

<html>
  <head>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8">  
  <title>Links</title>
  <?php include ("header.php"); ?>
  </head>

  <body>

  <?php include ("page.php"); ?>

  <div id="content">

    <p class="section">Links</p>

    <p>
     Some useful, some not so much.
    </p>

    <p class="section">2600 related</p>
    <ul>
      <li>
        <a href="http://www.2600.com">2600 web site</a>
      </li>
      <li>
        <a href="http://www.subspacefield.org/security/2600_summary/">2600 Summary - additional issue info (doesn't appear it's being updated)</a>
      </li>
      <li>
        <a href="http://servv89pn0aj.sn.sourcedns.com/~gbpprorg/2600/">$2600 Magazine Information (some offensive content/rants)</a>
      </li>
      <li>
        <a href="http://en.wikipedia.org/wiki/2600:_The_Hacker_Quarterly">wikipedia</a>
      </li>
    </ul>
    
    <p class="section">Humor</p>
    <ul>
      <li>
        <a href="http://www.dilbert.com/">Dilbert</a>
      </li>
      <li>
        <a href="http://www.userfriendly.org/">User Friendly</a>
      </li>
      <li>
        <a href="http://www.gpf-comics.com/">GPF</a>
      </li>
      <li>
        <a href="http://thismodernworld.com/newest-comic">This Modern World</a>
      </li>
      <li>
        <a href="http://thedailywtf.com/">The Daily WTF</a>
      </li>
    </ul>

    <p class="section">Interesting</p>
    <ul>
      <li>
        <a href="http://antwrp.gsfc.nasa.gov/apod/">Astronomy Picture of the Day</a>
      </li>
      <li>
        <a href="http://www.eff.org/">Electronic Frontier Foundation</a>
      </li>
      <li>
        <a href="http://www.phrack.org/">Phrack</a>
      </li>
      <li>
        <a href="http://isc.sans.org/">SANS</a>
      </li>
      <li>
        <a href="http://www.digifail.com//">DIGIFAIL</a>
      </li>
      <li>
        <a href="http://hak5.org//">trust your technolust</a>
      </li>
      <li>
        <a href="http://cyberasylum.org///">Cyber Asylum</a>
      </li>
    </ul>

    <p class="section">Charity</p>
    <ul>
      <li>
        <a href="http://www.stjude.org">St. Jude Children's Research Hospital</a>
      </li>
      <li>
        <a href="http://www.rileykids.org/">Riley Children's Foundation</a>
      </li>
    </ul>

    <p class="section">Misc.</p>
    <ul>
      <li>
        <a href="http://www.stuttgart.de/">Stuttgart, Deutschland</a>
      </li>
      <li>
        <a href="http://www.wikileaks.org/">Wikileaks</a>
      </li>
    </ul>

  </div>


<?php
  mysqli_close ($conn);
?>

  <div id="foot">
    <?php include ("footer.php"); ?>
  </div>

  </body>
</html>
