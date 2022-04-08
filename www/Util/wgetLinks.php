<!--
  wgetLinks.php

  Author:      wrepp
  Date:        07/23/2009
  Description: wget links
  Notes:

  01/10/2012 wre init $debug.
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php

/* debug flag */
  $debug = "";
  #$debug = "yes";


function convertTinyUrl ($url)  {

#echo "url=" . $url . "=<br>";
  $tinyurl = "";
  if (preg_match ("*tinyurl*", $url))  {
    $headers = get_headers ($url, 1);
  
#print_r ($headers);
    if (preg_match ( '#^HTTP/.*\s+[(301)]+\s#i', $headers[0] ))  {
      $tinyurl = $headers['Location'];
    }
  }

  return $tinyurl;
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
/* debug logic */

/* mysql */
  $conn = mysqli_connect ($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
  mysqli_select_db ($conn, $dbname);


/* query parameters */
  $start = @$_GET["start"];
  #echo "start=" . $start . "=<br>";
  
  $IssuesID = @$_POST["Issue"];
  #echo "IssuesID=" . $IssuesID . "=<br>";

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

    <p class="section">wget Links</p>

<?php
    if ($debug == 'yes')  {
      echo "<p class='red'>debug</p>";
    }

    echo "<p><text class='red'>(probably need to run stop/restore script)</text></p>";

  /* post back? */
    if ($start == "")  {

?>
      <form method="post" action="wgetLinks.php?start=start">
      <!-- <select id="Issue" name="Issue" onchange="window.location = 'wgetLinks.php?i=' + this.value"> -->
      <select id="Issue" name="Issue" >
<?php
      $sql = "SELECT vi.IssuesID, vi.Issue, vi.Volume, vi.`Quarter`, " .
             "       SUM(IF(l.LocalCopy = 'Y', 1,0)) AS 'NbrLocalCopy', " .
             "       SUM(IF(l.LocalCopy IS NULL, 0, 1)) AS 'NbrLinks' " .
             "FROM vw_Issues vi " .
             "LEFT JOIN Articles a ON  vi.IssuesID = a.IssuesID " .
             "LEFT JOIN Links l ON a.ArticlesID = l.ArticlesID  " .
             "GROUP BY vi.IssuesID, vi.Issue " .
             "ORDER BY vi.IssueYear, vi.`Quarter`;";
      #echo $sql;
      $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

      $inbr = 1;
      while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
        $id = $row["IssuesID"];
        $val = $row["Issue"] . " [" . $row['Volume'] . ":" . $row['Quarter'] . "]" . " (" . $row['NbrLinks'] . ")";
      /* select latest one */
        if ($inbr == mysqli_num_rows ($result))  {
          $selstr = " selected";
        }
        else {
          $selstr = "";
        }
      /* flag if already local copies */
        if ($row['NbrLocalCopy'] > 0)  {
          $val .= " *";
        }
?>
        <option value="<?php echo $id ?>"<?php echo $selstr ?>><?php echo $val ?></option>
<?php 
        $inbr++;
      }
?>
      </select>
      <input type="submit" id="start" name="start" value="Start...">
      </form>

<?php

    }
    else  {
    
      $sql = "SELECT l.LinksID, l.Status, l.Link, l.LocalCopy, a.ArticlesID, a.Title, i.Volume, i.Quarter, i.Period, i.IssueYear "  .
             "FROM Links l " .
             "LEFT JOIN Articles a ON l.ArticlesID = a.ArticlesID " .
             "LEFT JOIN Issues i ON a.IssuesID = i.IssuesID " .
             "WHERE i.IssuesID = " . $IssuesID;

    /* debug logic */
      if ($debug == 'yes')  {
        $sql .= " LIMIT 3 ";  // limit for testing
        #echo $sql . "<br>";
      }
    /* debug logic */

      $sql .= ";";
      #echo $sql . "<br>";
      $result = mysqli_query ($conn, $sql) or die (mysqli_error ($conn));

      $row = mysqli_fetch_array ($result, MYSQLI_ASSOC);
      echo "<p>" . $row['Period'] . " " . $row['IssueYear'] . "  " . $row['Volume'] . ":" . $row['Quarter'] . "  (" . mysqli_num_rows ($result) . ")</p>";
      mysqli_data_seek ($result, 0);  // reset to first record
      
?>
      <table width="95%" cellspacing="1px" cellpadding="2px" >
      <thead>
        <tr bgcolor="LIGHTGREY">
          <td>Status</td>
          <td>wget</td>
          <td>Link</td>
        </tr>
      </thead>
<?php
      $inbr = 0;
      while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC))  {
?>
        <tr bgcolor=<?php if ($inbr % 2)  echo "palegreen"; else  echo "HONEYDEW";?> >
        
<?php
          $showstatus = formatStatus ($row['Status']);
?>
          <td>
            <?php echo $showstatus ?>
          </td>
<?php
          $wget = "";
          if ($row['LocalCopy'] == 'Y')  $wget = "Ignore local copy";
          if ($row['Status'] != "Ok")    $wget = "Ignore status";
          
          $tinyurl = convertTinyUrl ($row['Link']);
          
          if ($wget == "")  {
            if ($tinyurl == "")  $url = $row['Link'];
            else  $url = $tinyurl;

            chdir ("../Links");
            #echo getcwd () . "<br>";
            $dir = $row['Volume'] . "/" . $row['Quarter'];
            #echo $dir . "<br>";
            if (!is_dir ($row['Volume']))  mkdir ($row['Volume']);
            if (!is_dir ($dir))  mkdir ($dir);
            chdir ($dir);
            #echo getcwd () . "<br>";

            # T=timeout, nc=no clobber (don't download multiple copies), a=append log to file name, k=convert links, 
            # p=page requisites, H=span hosts, t=number of retries, E=html extension, e=ignore robots.txt
            # -no-check-certificate=ignore certificates (wget does not come with a root certificate so ssl will always give errors)
            # U=user agent pretend to be Firefox (some sites won't download if we don't do this)
            //$cmd = 'wget -T 15 -nc -k -p -H -t 1 -E -a wget.log "' . $url . '"';
            # use version 1.12 devel because it downloads files in stylesheets
            # $cmd = '/home/wepp/internet/wget/wget -T 15 -nc -k -p -H -t 1 -E -erobots=off -a wget.log --no-check-certificate -U "Mozilla/5.0" "' . $url . '"';
            # current release version is 1.12, set full user agent and referer as some sites check for this
            $cmd = 'wget -T 15 -nc -k -p -H -t 1 -E -erobots=off -a wget.log --no-check-certificate -U "Mozilla/5.0 (X11; Linux x86_64; rv:5.0) Gecko/20100101 Firefox/52.0" --referer="http://www.google.com"  "' . $url . '"';
            echo $cmd . "<br>";
            
            exec ($cmd, $output, $returnValue);
            #print_r ($output);
            $wget = "";
            for ($i = 0; $i < count ($output); $i++)  {
              $wget .= $output[$i] . "<br>";
            }
            $wget .= "Return=" . $returnValue . "<br>";

            chdir ("../../../Util");
            #echo getcwd () . "<br>";

            /* so many websites are returning error 8 that we'll ignore it */
            /* 8 = server issued an error response, ie. server 404 not found error */
            if ($returnValue == 0 || $returnValue == 8)  {
            
              // set LocalCopy = Y
              $sql = "UPDATE Links " .
                     "SET LocalCopy = 'Y', " .
                     "    LocalCopyDate = '" . date ("Y-m-d H:i:s") . "' " .                     
                     "WHERE LinksID = " . $row['LinksID'] . ";";
              #echo $sql . "<br>";
              
              mysqli_query ($conn, $sql) or die ($sql . ' : ' . mysqli_error ($conn));
            }
            
          }  // while
?>
          <td>
<?php 
            echo $wget;
            
?>
          </td>
          
          <td>
            <?php echo "<a href='" . $row['Link'] . "'>" . $row['Link'] . "</a>"; ?>
<?php
            if ($tinyurl != "")  echo " (" . $tinyurl . ")";
?>
          </td>
          
        </tr>

<?php
        $inbr++;

      }
    /* any errors in log? */
      chdir ("../Links" . "/" . $dir);
      #echo getcwd () . "<br>";
      
      #$cmd = 'grep -i "Read error" wget.log';
      $cmd = 'grep -i -e "Read error" -e "ERROR:" -e "WARNING:" -e "ERROR " wget.log';
      echo $cmd . "<br>";
      exec ($cmd, $output, $returnValue);
      $grep = "";
      for ($i = 0; $i < count ($output); $i++)  {
        $grep .= $output[$i] . "<br>";
      }
      $grep .= "Return=" . $returnValue . "<br>";
      echo $grep;
      
      chdir ("../../../Util");
      #echo getcwd () . "<br>";    
      
    /* any found? */      
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


