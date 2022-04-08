<!--
  status.php

  Author:      wrepp
  Date:        10/13/2008
  Description: status page for 2600 index web site
  Notes:       table editors only available if sub-directory found
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
  <title>2600 Index Status</title>
  <?php include ("header.php"); ?>
  </head>

  <body>

  <?php include ("page.php"); ?>

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
      newF.action = parms[0];
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
    
    <p class="section">Status</p>

    <p>
     This web site is a work in progress, currently I'm going back and cleaning up some things and working on the todo's.<br>
     The current issue will be indexed as soon as possible - please be patient as the initial info (cover jpg, etc) comes from <a href="http://store.2600.com/backissues.html">store.2600.com</a> and I also need time to receive, read and write the article information and update the host server.
    </p>


    <p class="section">Todo</p>
    <ul>
      <li>Update links with errors (new link and/or archive.org).</li>
      <li>Review articles in bit bucket (add more keywords?).</li>
      <li>Fix local copy logic of tinyurls.</li>
      <li>Add support for IPv6 links.</li>
      <li>Update/finish open source stuff.</li>
    </ul>

    <p class="section">Notes</p>
    <ul>
      <li>Autumn 1994 issue [11:3] page 48 was the first with pictures of phones on the back.</li>
      <li>Summer 1995 issue [12:2] page 16 was the first with reference to the www.2600.com web site. ([12:1] page 21 annouced construction)</li>
      <li>Winter 2010 issue [27:4] page 19 was the first electronic subscription edition and Hacker Digest created.</li>
    </ul>

    <p class="section">Database</p>

    <?php include "stats.html"; ?>
    

    <p class="note">(Note: I add a Letters article to each issue as I index)</p>
  </div>

<?php
  mysqli_close ($conn);
?>

  <div id="foot">
    <?php include ("footer.php"); ?>
  </div>

  </body>
</html>
