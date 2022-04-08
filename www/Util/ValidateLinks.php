<!--
  ValidateLinks.php

  Author:      wrepp
  Date:        10/13/2008
  Description: Validate links report
  Notes:
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php
function is_valid_url ($url)
{
echo "url=" . $url . "=<br>";
  $url = @parse_url($url);
  if ( ! $url) {
    return false;
  }

  $url = array_map('trim', $url);
  $url['port'] = (!isset($url['port'])) ? 80 : (int)$url['port'];
#echo "scheme=" . $url['scheme'] . "<br>";
  if ($url['scheme'] == "https")  $url['port'] = 443;
  $path = (isset($url['path'])) ? $url['path'] : '';

  if ($path == '')
  {
    $path = '/';
  }
#echo "path=" . $path . "=<br>";
#echo "url[host]=" . $url['host'] . "=<br>";

  $path .= ( isset ( $url['query'] ) ) ? "?$url[query]" : '';
  if ( isset ( $url['host'] ) AND $url['host'] != gethostbyname ( $url['host'] ) )
  {
/*
echo "url[port]=" . $url['port'] . "=<br>";
echo "url[scheme]=" . $url['scheme'] . "=<br>";
echo "url[query]=" . $url['query'] . "=<br>";
echo "url[path]=" . $url['path'] . "=<br>";
*/
    if ( PHP_VERSION >= 5 )
    {
      $err_rpt - error_reporting (0);  // off
      try  {
        $headers = get_headers("$url[scheme]://$url[host]:$url[port]$path");
      }
      catch (Exception $e)  {
        $headers = "get_headers error: " . $e;
      }
      error_reporting ($err_rpt);  // reset
    }
    else
    {
      $fp = fsockopen($url['host'], $url['port'], $errno, $errstr, 30);
      if ( ! $fp )
      {
        return false;
      }
      fputs($fp, "HEAD $path HTTP/1.1\r\nHost: $url[host]\r\n\r\n");
      //fputs($fp, "GET $path HTTP/1.1\r\nHost: $url[host]\r\n\r\n");
      $headers = fread ( $fp, 1024 );
      fclose ( $fp );
    }
    $headers = ( is_array ( $headers ) ) ? implode ( "\n", $headers ) : $headers;
#echo "headers=" . $headers . "=<br>";

    // original: return ( bool ) preg_match ( '#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers );
    return ( bool ) preg_match ( '#^HTTP/.*\s+[(200)]+\s#i', $headers );
  }
  return false;
}

function formatStatus ($status)  {

  if ($status == "Err")  $showstatus = "<text class='red'>Err</text>";
  else  $showstatus = "<text class='green'>" . $status . "</text>";
  
  return $showstatus;
}
?>

<?php

  $path = "../";

  include ($path . "db.php");

/* debug logic */
  #$debug = "yes";
  $debug = "";
/* debug logic */

/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);

/* query parameters */
  $start = @$_GET["start"];
  #echo "start=" . $start . "=<br>";

  $errorsonly = @$_POST["errorsonly"];
  #echo "errorsonly=" . $errorsonly . "=<br>";

  $unvalidated = @$_POST["unvalidated"];
  #echo "unvalidated=" . $unvalidated . "=<br>";

  $checklink = @$_POST["checklink"];
  #echo "checklink=" . $checklink . "=<br>";

  $curl = @$_POST["curl"];
  #echo "curl=" . $curl . "=<br>";

  $update = @$_POST["update"];
  if ($update == "")  $update = "yes";  // default
  #echo "update=" . $update . "=<br>";

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
    
    var xmlHttp;

    function updateLinkStatus (LinksID, Status, Debug)  { 
      //alert ("updateLinkStatus " + LinksID + ',' + Status + ',' + Debug);

      xmlHttp = GetXmlHttpObject();
      if (xmlHttp == null)  {
        alert ("Browser does not support HTTP Request");
        return;
      }

      var url = "updateLink.php";
      url = url + "?id=" + LinksID;
      url = url + "&s=" + Status;
      url = url + "&d=" + Debug;
      xmlHttp.onreadystatechange = stateChanged;
      xmlHttp.open ("GET", url, true);
      xmlHttp.send (null);
    }

    function stateChanged ()  { 
      if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")  {
        alert (xmlHttp.responseText);
        //document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
      } 
    }

    function GetXmlHttpObject ()  {
      var xmlHttp = null;
      try  {
        // Firefox, Opera 8.0+, Safari
        xmlHttp = new XMLHttpRequest();
      }
      catch (e)  {
        //Internet Explorer
        try  {
          xmlHttp = new ActiveXObject ("Msxml2.XMLHTTP");
        }
        catch (e)  {
          xmlHttp = new ActiveXObject ("Microsoft.XMLHTTP");
        }
      }
      return xmlHttp;
    }
    
    </script>

    <p class="section">Validate Links</p>

<?php
    if ($debug == 'yes')  {
      echo "<p class='red'>debug</p>";
    }
    
  /* post back? */
    if ($start == "")  {

      $sql = "SELECT MAX(Validated) AS 'Validated' " .
             "FROM Links;";
      #echo $sql . "<br>";
      $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
      $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);

      echo "<br>&nbsp Last validated at " . $row['Validated'] . ".<br>";

      $sql = "SELECT COUNT(*) AS 'Unvalidated' " .
             "FROM Links " . 
             "WHERE Status = '?';";
      #echo $sql . "<br>";
      $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
      $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);

      echo "<br>&nbsp There are currently " . $row['Unvalidated'] . " unvalidated link(s).<br>";

      $sql = "SELECT COUNT(*) AS 'Errors' " .
             "FROM Links " . 
             "WHERE Status = 'Err';";
      #echo $sql . "<br>";
      $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));
      $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);

      echo "<br>&nbsp There are currently " . $row['Errors'] . " link(s) that had errors.<br>";
      
?>
      <form method="post" action="ValidateLinks.php?start=start">
      <p>
      <input type="checkbox" id="unvalidated" name="unvalidated" value="?" checked>Unvalidated only
      <br>
      <input type="checkbox" id="errorsonly" name="errorsonly" value="Err" >Errors only
      <br>
      <input type="checkbox" id="checklink" name="checklink" value="yes" >use checklink
      <br>
      <input type="checkbox" id="curl" name="curl" value="yes" checked>use curl
    <!--
      <br>
      <input type="checkbox" id="update" name="update" value="yes" >update
      -->
      <br><br>
      <input type="submit" id="start" name="start" value="Start...">
      </p>
      </form>
<?php

    }
    else  {
    
      if ($checklink == 'yes')  {
      
      /* execute checklink : s=summary html=html output */
        $returnValue = -1;
        system ('checklink -s --html http://localhost/2600/Reports/Links.php?o=NotValidated', $returnValue);
        echo "<br>";
        echo "Return Value: $returnValue<br>";
      }
?>

    <table width="95%" cellspacing="1px" cellpadding="2px" >
    <thead>
      <tr bgcolor="LIGHTGREY">
      <td>Link</td>
      <td>Status</td>
      <td>New</td>
      <td>Set..</td>
      <td>Edit..</td>
      <td>dns..</td>
      <td>HTML</td>
      </tr>
    </thead>
<?php
      $sql = "SELECT * "  .
             "FROM Links";
      $cond = "";
      if ($unvalidated == '?')  $cond .= "'?'";
      if ($errorsonly == 'Err')  {
        if (strlen ($cond) > 0)  $cond .= ",";
        $cond .= "'Err'";
      }
      if (strlen ($cond) > 0)  $sql .= " WHERE Status IN (" . $cond . ")";
      
    /* debug logic */
      if ($debug == 'yes')  {
        $sql .= "  OR LinksID IN (430, 599, 841, 1045, 1050)";  // test links
        $update = "no";  // don't update when debugging
        echo $sql . "<br>";
      }
    /* debug logic */

      $sql .= ";";
      #echo $sql . "<br>";
      $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

      $inbr = 0;
      while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
?>
        <tr bgcolor=<?php if ($inbr % 2)  echo "palegreen"; else  echo "HONEYDEW";?> >
          <td>
            <?php echo "<a href='" . $row['Link'] . "'>" . $row['Link'] . "</a>"; ?>
          </td>
<?php
          $currentshowstatus = formatStatus ($row['Status']);
?>
          <td>
            <?php echo $currentshowstatus ?>
          </td>
<?php
          $url = $row['Link'];
          if (is_valid_url (trim ($url)))  {
            $status = "Ok";
            $showstatus = formatStatus ($status);
          }
          else  {
            $status = "Err";
            $showstatus = formatStatus ($status);
          }
?>
          <td>
            <?php echo $showstatus ?>
          </td>
          
        <!--
          <td onclick="updateLinkStatus (<?php #echo $row['LinksID'] ?>,'<?php #echo $status ?>','<?php #echo $curloutput ?>','<?php #echo $debug ?>'); return false;">
             <a href="">Set..</a>
          </td>
          -->
          
          <td>
             <select id="Status" name="Status" 
                     onchange="updateLinkStatus (<?php echo $row['LinksID'] ?>,this.value,'<?php echo $debug ?>'); return false;">
               <option value="?" <?php if ($status == "?") echo "selected";?>>
                 ?
               </option>
               <option value="Err" <?php if ($status == "Err") echo "selected";?>>
                 Err
               </option>
               <option value="Ok" <?php if ($status == "Ok") echo "selected";?>>
                 Ok
               </option>
             </select>
          </td>
          
          <td>
          <!--
            <a href="http://localhost/2600/TableEditors/Links.php?a=edit&amp;recid=0&amp;filter=<?php #echo $row['LinksID'] ?>&amp;filter_field=LinksID">..</a>
            -->
            <a href="../TableEditors/Links.php?filter=<?php echo $row['LinksID'] ?>&amp;filter_field=LinksID" onclick="toPost(this.href); return false;">Edit..</a>            
            
          </td>
          
          <td>
            <a href="http://whois.domaintools.com/<?php echo gethostbyname (parse_url ($url, PHP_URL_HOST)); ?>">dns</a>
          </td>
          
          <td>
<?php
            if ($curl == 'yes')  {
            
            /* execute curl : i=include http header, m=max time, s=silent, S=show error when used with s */
            /* --connect-timeout <seconds> limit for connection phase */
            /* -k=don't validate SSL certificate */
              unset ($output);
              $returnValue = -1;
              exec ('curl -i -m 15 --connect-timeout 15 -s -S -k "' . $url . '" 2>&1', $output, $returnValue);
              
           /* format to display */
              $output1 = str_replace ("<", "&lt;", $output);
              $outputHTML = str_replace (">", "&gt;", $output1);
              #print_r ($outputHTML);
              $output1 = str_replace ('"', '\"', $outputHTML);
              $outputMySQL = str_replace ("'", "\'", $output1);
              
              $nbr = count ($outputHTML);
              
           /* format html */
              $curloutputHTML = "";
              $curloutputMySQL = "";
              #$curloutputHTML .= "url=" . $url . "=<br>";
              $curloutputHTML .= "lines=" . $nbr . "<br>";
              $curloutputMySQL .= "lines=" . $nbr . "<br>";
              $curloutputHTML .= "<pre>";
              $curloutputMySQL .= "<pre>";
              for ($i = 0; $i < 15 && $i < $nbr; $i++)  {
                $curloutputHTML .= $outputHTML [$i] . "<br>";
                $curloutputMySQL .= $outputMySQL [$i] . "<br>";
              }
              $curloutputHTML .= "</pre>";
              $curloutputMySQL .= "</pre>";
              $curloutputHTML .= "Return Value: " . $returnValue . "<br>";
              $curloutputMySQL .= "Return Value: " . $returnValue . "<br>";

              echo $curloutputHTML;
              
              #echo '<table cellspacing="1px" cellpadding="2px">';
              #echo "<thead><th>lines=" . $nbr . "</th></thead>";
              #echo "<tfoot><tr><td>Return Value: " . $returnValue . "</td></tr></tfoot>";
              #for ($i = 0; $i < 15 && $i < $nbr; $i++)  echo "<tr><td><pre>" . $output2 [$i] . "</pre></td></tr>";
              #echo "</table>";
            }
?>
          </td>
        </tr>

<?php
        $inbr++;

       /* update database */
        if ($update == 'yes')  {
          $sql2 = "UPDATE Links " .
                  "SET Status = '" . $status . "', " .
                  "    Validated = '" . date ("Y-m-d H:i:s") . "', " .
                  #"    CurlOutput = '" . $curloutputMySQL . "' " .
                  "    CurlOutput = '" . mysqli_real_escape_string ($conn, $curloutputMySQL) . "' " .
                  "WHERE LinksID = " . $row['LinksID'] . ";";
          #echo $sql2 . "<br>";
          mysqli_query ($conn, $sql2) or die ($sql . ' : ' . mysqli_error ($conn));
        }
      }
      if ($inbr < 1)  {
        echo "&nbsp;None found.<br>";
      }
?>
      </table>
<?php
    }
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


