<?php session_start();
  if (isset($_GET["order"])) $order = @$_GET["order"];
  if (isset($_GET["type"])) $ordtype = @$_GET["type"];

  if (isset($_POST["filter"])) $filter = @$_POST["filter"];
  if (isset($_POST["filter_field"])) $filterfield = @$_POST["filter_field"];
  $wholeonly = false;
  if (isset($_POST["wholeonly"])) $wholeonly = @$_POST["wholeonly"];

  if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];

?>

<html>
<head>
<title>2600 -- Links</title>
<meta name="generator" http-equiv="content-type" content="text/html">
<style type="text/css">
  body {
    background-color: #FFFFFF;
    color: #004080;
    font-family: Arial;
    font-size: 12px;
  }
  .bd {
    background-color: #FFFFFF;
    color: #004080;
    font-family: Arial;
    font-size: 12px;
  }
  .tbl {
    background-color: #FFFFFF;
  }
  a:link { 
    color: #FF0000;
    font-family: Arial;
    font-size: 12px;
  }
  a:active { 
    color: #0000FF;
    font-family: Arial;
    font-size: 12px;
  }
  a:visited { 
    color: #800080;
    font-family: Arial;
    font-size: 12px;
  }
  .hr {
    background-color: #336699;
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  a.hr:link {
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  a.hr:active {
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  a.hr:visited {
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  .dr {
    background-color: #FFFFFF;
    color: #000000;
    font-family: Arial;
    font-size: 12px;
  }
  .sr {
    background-color: #FFFFCF;
    color: #000000;
    font-family: Arial;
    font-size: 12px;
  }
</style>
</head>
<body>
<table class="bd" width="100%"><tr><td class="hr"><h2>PHP Generator</h2></td></tr></table>
<table width="100%">
<tr>

<td width="10%" valign="top">
<li><a href="../index.php">Home</a>
<li><a href="Addendums.php?a=reset">Addendums</a>
<li><a href="Articles.php?a=reset">Articles</a>
<li><a href="Authors.php?a=reset">Authors</a>
<li><a href="Issues.php?a=reset">Issues</a>
<li><a href="Keywords.php?a=reset">Keywords</a>
<li><a href="KeywordXref.php?a=reset">KeywordXref</a>
</td>
<td width="5%">
</td>
<td bgcolor="#e0e0e0">
</td>
<td width="5%">
</td>
<td width="80%" valign="top">
<?php
  $conn = connect();
  $showrecs = 20;
  $pagerange = 10;

  $a = @$_GET["a"];
  $recid = @$_GET["recid"];
  $page = @$_GET["page"];
  if (!isset($page)) $page = 1;

  $sql = @$_POST["sql"];

  switch ($sql) {
    case "insert":
      sql_insert();
      break;
    case "update":
      sql_update();
      break;
    case "delete":
      sql_delete();
      break;
  }

  switch ($a) {
    case "add":
      addrec();
      break;
    case "view":
      viewrec($recid);
      break;
    case "edit":
      editrec($recid);
      break;
    case "del":
      deleterec($recid);
      break;
    default:
      select();
      break;
  }

  if (isset($order)) $_SESSION["order"] = $order;
  if (isset($ordtype)) $_SESSION["type"] = $ordtype;
  if (isset($filter)) $_SESSION["filter"] = $filter;
  if (isset($filterfield)) $_SESSION["filter_field"] = $filterfield;
  if (isset($wholeonly)) $_SESSION["wholeonly"] = $wholeonly;

  mysql_close($conn);
?>
</td></tr></table>
<table class="bd" width="100%"><tr><td class="hr">http://www.sqlmaestro.com/products/mysql/phpgenerator/</td></tr></table>
</body>
</html>

<?php function select()
  {
  global $a;
  global $showrecs;
  global $page;
  global $filter;
  global $filterfield;
  global $wholeonly;
  global $order;
  global $ordtype;


  if ($a == "reset") {
    $filter = "";
    $filterfield = "";
    $wholeonly = "";
    $order = "";
    $ordtype = "";
  }

  $checkstr = "";
  if ($wholeonly) $checkstr = " checked";
  if ($ordtype == "asc") { $ordtypestr = "desc"; } else { $ordtypestr = "asc"; }
  $res = sql_select();
  $count = sql_getrecordcount();
  if ($count % $showrecs != 0) {
    $pagecount = intval($count / $showrecs) + 1;
  }
  else {
    $pagecount = intval($count / $showrecs);
  }
  $startrec = $showrecs * ($page - 1);
  if ($startrec < $count) {mysql_data_seek($res, $startrec);}
  $reccount = min($showrecs * $page, $count);
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr><td>Table: Links</td></tr>
<tr><td>Records shown <?php echo $startrec + 1 ?> - <?php echo $reccount ?> of <?php echo $count ?></td></tr>
</table>
<hr size="1" noshade>
<form action="Links.php" method="post">
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><b>Custom Filter</b>&nbsp;</td>
<td><input type="text" name="filter" value="<?php echo $filter ?>"></td>
<td><select name="filter_field">
<option value="">All Fields</option>
<option value="<?php echo "LinksID" ?>"<?php if ($filterfield == "LinksID") { echo "selected"; } ?>><?php echo htmlspecialchars("LinksID") ?></option>
<option value="<?php echo "lp_ArticlesID" ?>"<?php if ($filterfield == "lp_ArticlesID") { echo "selected"; } ?>><?php echo htmlspecialchars("ArticlesID") ?></option>
<option value="<?php echo "Link" ?>"<?php if ($filterfield == "Link") { echo "selected"; } ?>><?php echo htmlspecialchars("Link") ?></option>
<option value="<?php echo "Description" ?>"<?php if ($filterfield == "Description") { echo "selected"; } ?>><?php echo htmlspecialchars("Description") ?></option>
<option value="<?php echo "Notes" ?>"<?php if ($filterfield == "Notes") { echo "selected"; } ?>><?php echo htmlspecialchars("Notes") ?></option>
<option value="<?php echo "Status" ?>"<?php if ($filterfield == "Status") { echo "selected"; } ?>><?php echo htmlspecialchars("Status") ?></option>
<option value="<?php echo "Validated" ?>"<?php if ($filterfield == "Validated") { echo "selected"; } ?>><?php echo htmlspecialchars("Validated") ?></option>
<option value="<?php echo "Updated" ?>"<?php if ($filterfield == "Updated") { echo "selected"; } ?>><?php echo htmlspecialchars("Updated") ?></option>
<option value="<?php echo "LocalCopy" ?>"<?php if ($filterfield == "LocalCopy") { echo "selected"; } ?>><?php echo htmlspecialchars("LocalCopy") ?></option>
<option value="<?php echo "Type" ?>"<?php if ($filterfield == "Type") { echo "selected"; } ?>><?php echo htmlspecialchars("Type") ?></option>
<option value="<?php echo "OriginalLink" ?>"<?php if ($filterfield == "OriginalLink") { echo "selected"; } ?>><?php echo htmlspecialchars("OriginalLink") ?></option>
<option value="<?php echo "ArchiveLink" ?>"<?php if ($filterfield == "ArchiveLink") { echo "selected"; } ?>><?php echo htmlspecialchars("ArchiveLink") ?></option>
<option value="<?php echo "CurlOutput" ?>"<?php if ($filterfield == "CurlOutput") { echo "selected"; } ?>><?php echo htmlspecialchars("CurlOutput") ?></option>
</select></td>
<td><input type="checkbox" name="wholeonly"<?php echo $checkstr ?>>Whole words only</td>
</td></tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" name="action" value="Apply Filter"></td>
<td><a href="Links.php?a=reset">Reset Filter</a></td>
</tr>
</table>
</form>
<hr size="1" noshade>
<?php showpagenav($page, $pagecount); ?>
<br>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
<tr>
<td class="hr">&nbsp;</td>
<td class="hr">&nbsp;</td>
<td class="hr">&nbsp;</td>
<td class="hr"><a class="hr" href="Links.php?order=<?php echo "LinksID" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("LinksID") ?></a></td>
<td class="hr"><a class="hr" href="Links.php?order=<?php echo "lp_ArticlesID" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("ArticlesID") ?></a></td>
<td class="hr"><a class="hr" href="Links.php?order=<?php echo "Link" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Link") ?></a></td>
<td class="hr"><a class="hr" href="Links.php?order=<?php echo "Description" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Description") ?></a></td>
<td class="hr"><a class="hr" href="Links.php?order=<?php echo "Notes" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Notes") ?></a></td>
<td class="hr"><a class="hr" href="Links.php?order=<?php echo "Status" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Status") ?></a></td>
<td class="hr"><a class="hr" href="Links.php?order=<?php echo "Validated" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Validated") ?></a></td>
<td class="hr"><a class="hr" href="Links.php?order=<?php echo "Updated" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Updated") ?></a></td>
<td class="hr"><a class="hr" href="Links.php?order=<?php echo "LocalCopy" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("LocalCopy") ?></a></td>
<td class="hr"><a class="hr" href="Links.php?order=<?php echo "Type" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Type") ?></a></td>
<td class="hr"><a class="hr" href="Links.php?order=<?php echo "OriginalLink" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("OriginalLink") ?></a></td>
<td class="hr"><a class="hr" href="Links.php?order=<?php echo "ArchiveLink" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("ArchiveLink") ?></a></td>
<td class="hr"><a class="hr" href="Links.php?order=<?php echo "CurlOutput" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("CurlOutput") ?></a></td>
</tr>
<?php
  for ($i = $startrec; $i < $reccount; $i++)
  {
    $row = mysql_fetch_assoc($res);
    $style = "dr";
    if ($i % 2 != 0) {
      $style = "sr";
    }
?>
<tr>
<td class="<?php echo $style ?>"><a href="Links.php?a=view&recid=<?php echo $i ?>">View</a></td>
<td class="<?php echo $style ?>"><a href="Links.php?a=edit&recid=<?php echo $i ?>">Edit</a></td>
<td class="<?php echo $style ?>"><a href="Links.php?a=del&recid=<?php echo $i ?>">Delete</a></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["LinksID"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["lp_ArticlesID"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["Link"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["Description"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["Notes"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["Status"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["Validated"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["Updated"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["LocalCopy"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["Type"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["OriginalLink"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["ArchiveLink"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["CurlOutput"]) ?></td>
</tr>
<?php
  }
  mysql_free_result($res);
?>
</table>
<br>
<?php showpagenav($page, $pagecount); ?>
<?php } ?>

<?php function showrow($row, $recid)
  {
?>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
<tr>
<td class="hr"><?php echo htmlspecialchars("LinksID")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["LinksID"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("ArticlesID")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["lp_ArticlesID"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Link")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Link"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Description")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Description"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Notes")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Notes"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Status")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Status"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Validated")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Validated"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Updated")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Updated"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("LocalCopy")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["LocalCopy"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Type")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Type"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("OriginalLink")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["OriginalLink"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("ArchiveLink")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["ArchiveLink"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("CurlOutput")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["CurlOutput"]) ?></td>
</tr>
</table>
<?php } ?>

<?php function showroweditor($row, $iseditmode)
  {
  global $conn;
?>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
<tr>
<td class="hr"><?php echo htmlspecialchars("LinksID")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="LinksID" value="<?php echo str_replace('"', '&quot;', trim($row["LinksID"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("ArticlesID")."&nbsp;" ?></td>
<td class="dr"><select name="ArticlesID">
<?php
  $sql = "select `ArticlesID`, `Article` from `vw_ArticlesList` ORDER BY `IssueYear`, `Quarter`";
  $res = mysql_query($sql, $conn) or die(mysql_error());

  while ($lp_row = mysql_fetch_assoc($res)){
  $val = $lp_row["ArticlesID"];
  $caption = $lp_row["Article"];
  if ($row["ArticlesID"] == $val) {$selstr = " selected"; } else {$selstr = ""; }
 ?><option value="<?php echo $val ?>"<?php echo $selstr ?>><?php echo $caption ?></option>
<?php } ?></select>
</td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Link")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="Link" maxlength="256"><?php echo str_replace('"', '&quot;', trim($row["Link"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Description")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="Description" maxlength="64"><?php echo str_replace('"', '&quot;', trim($row["Description"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Notes")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="Notes" maxlength="2048"><?php echo str_replace('"', '&quot;', trim($row["Notes"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Status")."&nbsp;" ?></td>
<td class="dr"><select name="Status">
<?php
  $lookupvalues = array("?","Ok","Err");

  reset($lookupvalues);
  foreach($lookupvalues as $val){
  $caption = $val;
  if ($row["Status"] == $val) {$selstr = " selected"; } else {$selstr = ""; }
 ?><option value="<?php echo $val ?>"<?php echo $selstr ?>><?php echo $caption ?></option>
<?php } ?></select>
</td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Validated")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="Validated" value="<?php echo str_replace('"', '&quot;', trim($row["Validated"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Updated")."&nbsp;" ?></td>
<td class="dr"><select name="Updated">
<?php
  $lookupvalues = array("N","Y");

  reset($lookupvalues);
  foreach($lookupvalues as $val){
  $caption = $val;
  if ($row["Updated"] == $val) {$selstr = " selected"; } else {$selstr = ""; }
 ?><option value="<?php echo $val ?>"<?php echo $selstr ?>><?php echo $caption ?></option>
<?php } ?></select>
</td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("LocalCopy")."&nbsp;" ?></td>
<td class="dr"><select name="LocalCopy">
<?php
  $lookupvalues = array("N","Y");

  reset($lookupvalues);
  foreach($lookupvalues as $val){
  $caption = $val;
  if ($row["LocalCopy"] == $val) {$selstr = " selected"; } else {$selstr = ""; }
 ?><option value="<?php echo $val ?>"<?php echo $selstr ?>><?php echo $caption ?></option>
<?php } ?></select>
</td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Type")."&nbsp;" ?></td>
<td class="dr"><select name="Type">
<?php
  $lookupvalues = array("O","A","I");

  reset($lookupvalues);
  foreach($lookupvalues as $val){
  $caption = $val;
  if ($row["Type"] == $val) {$selstr = " selected"; } else {$selstr = ""; }
 ?><option value="<?php echo $val ?>"<?php echo $selstr ?>><?php echo $caption ?></option>
<?php } ?></select>
</td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("OriginalLink")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="OriginalLink" maxlength="256"><?php echo str_replace('"', '&quot;', trim($row["OriginalLink"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("ArchiveLink")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="ArchiveLink" maxlength="256"><?php echo str_replace('"', '&quot;', trim($row["ArchiveLink"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("CurlOutput")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="CurlOutput" maxlength="4096"><?php echo str_replace('"', '&quot;', trim($row["CurlOutput"])) ?></textarea></td>
</tr>
</table>
<?php } ?>

<?php function showpagenav($page, $pagecount)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="Links.php?a=add">Add Record</a>&nbsp;</td>
<?php if ($page > 1) { ?>
<td><a href="Links.php?page=<?php echo $page - 1 ?>">&lt;&lt;&nbsp;Prev</a>&nbsp;</td>
<?php } ?>
<?php
  global $pagerange;

  if ($pagecount > 1) {

  if ($pagecount % $pagerange != 0) {
    $rangecount = intval($pagecount / $pagerange) + 1;
  }
  else {
    $rangecount = intval($pagecount / $pagerange);
  }
  for ($i = 1; $i < $rangecount + 1; $i++) {
    $startpage = (($i - 1) * $pagerange) + 1;
    $count = min($i * $pagerange, $pagecount);

    if ((($page >= $startpage) && ($page <= ($i * $pagerange)))) {
      for ($j = $startpage; $j < $count + 1; $j++) {
        if ($j == $page) {
?>
<td><b><?php echo $j ?></b></td>
<?php } else { ?>
<td><a href="Links.php?page=<?php echo $j ?>"><?php echo $j ?></a></td>
<?php } } } else { ?>
<td><a href="Links.php?page=<?php echo $startpage ?>"><?php echo $startpage ."..." .$count ?></a></td>
<?php } } } ?>
<?php if ($page < $pagecount) { ?>
<td>&nbsp;<a href="Links.php?page=<?php echo $page + 1 ?>">Next&nbsp;&gt;&gt;</a>&nbsp;</td>
<?php } ?>
</tr>
</table>
<?php } ?>

<?php function showrecnav($a, $recid, $count)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="Links.php">Index Page</a></td>
<?php if ($recid > 0) { ?>
<td><a href="Links.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>">Prior Record</a></td>
<?php } if ($recid < $count - 1) { ?>
<td><a href="Links.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>">Next Record</a></td>
<?php } ?>
</tr>
</table>
<hr size="1" noshade>
<?php } ?>

<?php function addrec()
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="Links.php">Index Page</a></td>
</tr>
</table>
<hr size="1" noshade>
<form enctype="multipart/form-data" action="Links.php" method="post">
<p><input type="hidden" name="sql" value="insert"></p>
<?php
$row = array(
  "LinksID" => "",
  "ArticlesID" => "",
  "Link" => "",
  "Description" => "",
  "Notes" => "",
  "Status" => "",
  "Validated" => "",
  "Updated" => "",
  "LocalCopy" => "",
  "Type" => "",
  "OriginalLink" => "",
  "ArchiveLink" => "",
  "CurlOutput" => "");
showroweditor($row, false);
?>
<p><input type="submit" name="action" value="Post"></p>
</form>
<?php } ?>

<?php function viewrec($recid)
{
  $res = sql_select();
  $count = sql_getrecordcount();
  mysql_data_seek($res, $recid);
  $row = mysql_fetch_assoc($res);
  showrecnav("view", $recid, $count);
?>
<br>
<?php showrow($row, $recid) ?>
<br>
<hr size="1" noshade>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="Links.php?a=add">Add Record</a></td>
<td><a href="Links.php?a=edit&recid=<?php echo $recid ?>">Edit Record</a></td>
<td><a href="Links.php?a=del&recid=<?php echo $recid ?>">Delete Record</a></td>
</tr>
</table>
<?php
  mysql_free_result($res);
} ?>

<?php function editrec($recid)
{
  $res = sql_select();
  $count = sql_getrecordcount();
  mysql_data_seek($res, $recid);
  $row = mysql_fetch_assoc($res);
  showrecnav("edit", $recid, $count);
?>
<br>
<form enctype="multipart/form-data" action="Links.php" method="post">
<input type="hidden" name="sql" value="update">
<input type="hidden" name="xLinksID" value="<?php echo $row["LinksID"] ?>">
<?php showroweditor($row, true); ?>
<p><input type="submit" name="action" value="Post"></p>
</form>
<?php
  mysql_free_result($res);
} ?>

<?php function deleterec($recid)
{
  $res = sql_select();
  $count = sql_getrecordcount();
  mysql_data_seek($res, $recid);
  $row = mysql_fetch_assoc($res);
  showrecnav("del", $recid, $count);
?>
<br>
<form action="Links.php" method="post">
<input type="hidden" name="sql" value="delete">
<input type="hidden" name="xLinksID" value="<?php echo $row["LinksID"] ?>">
<?php showrow($row, $recid) ?>
<p><input type="submit" name="action" value="Confirm"></p>
</form>
<?php
  mysql_free_result($res);
} ?>

<?php function connect()
{
  $conn = mysql_connect("localhost", "root", "1.root_2");
  mysql_select_db("2600");
  return $conn;
}

function sqlvalue($val, $quote)
{
  if ($quote)
    $tmp = sqlstr($val);
  else
    $tmp = $val;
  if ($tmp == "")
    $tmp = "NULL";
  elseif ($quote)
    $tmp = "'".addslashes($tmp)."'";
  return $tmp;
}

function sqlstr($val)
{
  return $val;  //str_replace("'", "''", $val);
}

function sql_select()
{
  global $conn;
  global $order;
  global $ordtype;
  global $filter;
  global $filterfield;
  global $wholeonly;

  $filterstr = sqlstr($filter);
  if (!$wholeonly && isset($wholeonly) && $filterstr!='') $filterstr = "%" .$filterstr ."%";
  $sql = "SELECT * FROM (SELECT t1.`LinksID`, t1.`ArticlesID`, lp1.`Article` AS `lp_ArticlesID`, t1.`Link`, t1.`Description`, t1.`Notes`, t1.`Status`, t1.`Validated`, t1.`Updated`, t1.`LocalCopy`, t1.`Type`, t1.`OriginalLink`, t1.`ArchiveLink`, t1.`CurlOutput` FROM `Links` AS t1 LEFT OUTER JOIN `vw_ArticlesList` AS lp1 ON (t1.`ArticlesID` = lp1.`ArticlesID`)) subq";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`LinksID` like '" .$filterstr ."') or (`lp_ArticlesID` like '" .$filterstr ."') or (`Link` like '" .$filterstr ."') or (`Description` like '" .$filterstr ."') or (`Notes` like '" .$filterstr ."') or (`Status` like '" .$filterstr ."') or (`Validated` like '" .$filterstr ."') or (`Updated` like '" .$filterstr ."') or (`LocalCopy` like '" .$filterstr ."') or (`Type` like '" .$filterstr ."') or (`OriginalLink` like '" .$filterstr ."') or (`ArchiveLink` like '" .$filterstr ."') or (`CurlOutput` like '" .$filterstr ."')";
  }
  if (isset($order) && $order!='') $sql .= " order by `" .sqlstr($order) ."`";
  if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  $res = mysql_query($sql, $conn) or die(mysql_error());
  return $res;
}

function sql_getrecordcount()
{
  global $conn;
  global $order;
  global $ordtype;
  global $filter;
  global $filterfield;
  global $wholeonly;

  $filterstr = sqlstr($filter);
  if (!$wholeonly && isset($wholeonly) && $filterstr!='') $filterstr = "%" .$filterstr ."%";
  $sql = "SELECT COUNT(*) FROM (SELECT t1.`LinksID`, t1.`ArticlesID`, lp1.`Article` AS `lp_ArticlesID`, t1.`Link`, t1.`Description`, t1.`Notes`, t1.`Status`, t1.`Validated`, t1.`Updated`, t1.`LocalCopy`, t1.`Type`, t1.`OriginalLink`, t1.`ArchiveLink`, t1.`CurlOutput` FROM `Links` AS t1 LEFT OUTER JOIN `vw_ArticlesList` AS lp1 ON (t1.`ArticlesID` = lp1.`ArticlesID`)) subq";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`LinksID` like '" .$filterstr ."') or (`lp_ArticlesID` like '" .$filterstr ."') or (`Link` like '" .$filterstr ."') or (`Description` like '" .$filterstr ."') or (`Notes` like '" .$filterstr ."') or (`Status` like '" .$filterstr ."') or (`Validated` like '" .$filterstr ."') or (`Updated` like '" .$filterstr ."') or (`LocalCopy` like '" .$filterstr ."') or (`Type` like '" .$filterstr ."') or (`OriginalLink` like '" .$filterstr ."') or (`ArchiveLink` like '" .$filterstr ."') or (`CurlOutput` like '" .$filterstr ."')";
  }
  $res = mysql_query($sql, $conn) or die(mysql_error());
  $row = mysql_fetch_assoc($res);
  reset($row);
  return current($row);
}

function sql_insert()
{
  global $conn;
  global $_POST;

  $sql = "insert into `Links` (`LinksID`, `ArticlesID`, `Link`, `Description`, `Notes`, `Status`, `Validated`, `Updated`, `LocalCopy`, `Type`, `OriginalLink`, `ArchiveLink`, `CurlOutput`) values (" .sqlvalue(@$_POST["LinksID"], false).", " .sqlvalue(@$_POST["ArticlesID"], false).", " .sqlvalue(@$_POST["Link"], true).", " .sqlvalue(@$_POST["Description"], true).", " .sqlvalue(@$_POST["Notes"], true).", " .sqlvalue(@$_POST["Status"], true).", " .sqlvalue(@$_POST["Validated"], true).", " .sqlvalue(@$_POST["Updated"], true).", " .sqlvalue(@$_POST["LocalCopy"], true).", " .sqlvalue(@$_POST["Type"], true).", " .sqlvalue(@$_POST["OriginalLink"], true).", " .sqlvalue(@$_POST["ArchiveLink"], true).", " .sqlvalue(@$_POST["CurlOutput"], true).")";
  mysql_query($sql, $conn) or die(mysql_error());
}

function sql_update()
{
  global $conn;
  global $_POST;

  $sql = "update `Links` set `LinksID`=" .sqlvalue(@$_POST["LinksID"], false).", `ArticlesID`=" .sqlvalue(@$_POST["ArticlesID"], false).", `Link`=" .sqlvalue(@$_POST["Link"], true).", `Description`=" .sqlvalue(@$_POST["Description"], true).", `Notes`=" .sqlvalue(@$_POST["Notes"], true).", `Status`=" .sqlvalue(@$_POST["Status"], true).", `Validated`=" .sqlvalue(@$_POST["Validated"], true).", `Updated`=" .sqlvalue(@$_POST["Updated"], true).", `LocalCopy`=" .sqlvalue(@$_POST["LocalCopy"], true).", `Type`=" .sqlvalue(@$_POST["Type"], true).", `OriginalLink`=" .sqlvalue(@$_POST["OriginalLink"], true).", `ArchiveLink`=" .sqlvalue(@$_POST["ArchiveLink"], true).", `CurlOutput`=" .sqlvalue(@$_POST["CurlOutput"], true) ." where " .primarykeycondition();
  mysql_query($sql, $conn) or die(mysql_error());
}

function sql_delete()
{
  global $conn;

  $sql = "delete from `Links` where " .primarykeycondition();
  mysql_query($sql, $conn) or die(mysql_error());
}
function primarykeycondition()
{
  global $_POST;
  $pk = "";
  $pk .= "(`LinksID`";
  if (@$_POST["xLinksID"] == "") {
    $pk .= " IS NULL";
  }else{
  $pk .= " = " .sqlvalue(@$_POST["xLinksID"], false);
  };
  $pk .= ")";
  return $pk;
}
 ?>
