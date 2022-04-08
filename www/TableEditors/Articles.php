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
<title>2600 -- Articles</title>
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
<li><a href="Authors.php?a=reset">Authors</a>
<li><a href="Issues.php?a=reset">Issues</a>
<li><a href="Keywords.php?a=reset">Keywords</a>
<li><a href="KeywordXref.php?a=reset">KeywordXref</a>
<li><a href="Links.php?a=reset">Links</a>
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
<tr><td>Table: Articles</td></tr>
<tr><td>Records shown <?php echo $startrec + 1 ?> - <?php echo $reccount ?> of <?php echo $count ?></td></tr>
</table>
<hr size="1" noshade>
<form action="Articles.php" method="post">
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><b>Custom Filter</b>&nbsp;</td>
<td><input type="text" name="filter" value="<?php echo $filter ?>"></td>
<td><select name="filter_field">
<option value="">All Fields</option>
<option value="<?php echo "ArticlesID" ?>"<?php if ($filterfield == "ArticlesID") { echo "selected"; } ?>><?php echo htmlspecialchars("ArticlesID") ?></option>
<option value="<?php echo "lp_IssuesID" ?>"<?php if ($filterfield == "lp_IssuesID") { echo "selected"; } ?>><?php echo htmlspecialchars("IssuesID") ?></option>
<option value="<?php echo "Title" ?>"<?php if ($filterfield == "Title") { echo "selected"; } ?>><?php echo htmlspecialchars("Title") ?></option>
<option value="<?php echo "lp_AuthorsID" ?>"<?php if ($filterfield == "lp_AuthorsID") { echo "selected"; } ?>><?php echo htmlspecialchars("AuthorsID") ?></option>
<option value="<?php echo "Synopsis" ?>"<?php if ($filterfield == "Synopsis") { echo "selected"; } ?>><?php echo htmlspecialchars("Synopsis") ?></option>
<option value="<?php echo "Notes" ?>"<?php if ($filterfield == "Notes") { echo "selected"; } ?>><?php echo htmlspecialchars("Notes") ?></option>
<option value="<?php echo "Code" ?>"<?php if ($filterfield == "Code") { echo "selected"; } ?>><?php echo htmlspecialchars("Code") ?></option>
<option value="<?php echo "Page" ?>"<?php if ($filterfield == "Page") { echo "selected"; } ?>><?php echo htmlspecialchars("Page") ?></option>
<option value="<?php echo "PageTitle" ?>"<?php if ($filterfield == "PageTitle") { echo "selected"; } ?>><?php echo htmlspecialchars("PageTitle") ?></option>
<option value="<?php echo "Pages" ?>"<?php if ($filterfield == "Pages") { echo "selected"; } ?>><?php echo htmlspecialchars("Pages") ?></option>
<option value="<?php echo "PageIn2600Book" ?>"<?php if ($filterfield == "PageIn2600Book") { echo "selected"; } ?>><?php echo htmlspecialchars("PageIn2600Book") ?></option>
</select></td>
<td><input type="checkbox" name="wholeonly"<?php echo $checkstr ?>>Whole words only</td>
</td></tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" name="action" value="Apply Filter"></td>
<td><a href="Articles.php?a=reset">Reset Filter</a></td>
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
<td class="hr"><a class="hr" href="Articles.php?order=<?php echo "ArticlesID" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("ArticlesID") ?></a></td>
<td class="hr"><a class="hr" href="Articles.php?order=<?php echo "lp_IssuesID" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("IssuesID") ?></a></td>
<td class="hr"><a class="hr" href="Articles.php?order=<?php echo "Title" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Title") ?></a></td>
<td class="hr"><a class="hr" href="Articles.php?order=<?php echo "lp_AuthorsID" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("AuthorsID") ?></a></td>
<td class="hr"><a class="hr" href="Articles.php?order=<?php echo "Synopsis" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Synopsis") ?></a></td>
<td class="hr"><a class="hr" href="Articles.php?order=<?php echo "Notes" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Notes") ?></a></td>
<td class="hr"><a class="hr" href="Articles.php?order=<?php echo "Code" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Code") ?></a></td>
<td class="hr"><a class="hr" href="Articles.php?order=<?php echo "Page" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Page") ?></a></td>
<td class="hr"><a class="hr" href="Articles.php?order=<?php echo "PageTitle" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("PageTitle") ?></a></td>
<td class="hr"><a class="hr" href="Articles.php?order=<?php echo "Pages" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Pages") ?></a></td>
<td class="hr"><a class="hr" href="Articles.php?order=<?php echo "PageIn2600Book" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("PageIn2600Book") ?></a></td>
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
<td class="<?php echo $style ?>"><a href="Articles.php?a=view&recid=<?php echo $i ?>">View</a></td>
<td class="<?php echo $style ?>"><a href="Articles.php?a=edit&recid=<?php echo $i ?>">Edit</a></td>
<td class="<?php echo $style ?>"><a href="Articles.php?a=del&recid=<?php echo $i ?>">Delete</a></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["ArticlesID"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["lp_IssuesID"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["Title"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["lp_AuthorsID"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["Synopsis"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["Notes"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["Code"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["Page"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["PageTitle"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["Pages"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["PageIn2600Book"]) ?></td>
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
<td class="hr"><?php echo htmlspecialchars("ArticlesID")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["ArticlesID"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("IssuesID")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["lp_IssuesID"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Title")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Title"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("AuthorsID")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["lp_AuthorsID"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Synopsis")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Synopsis"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Notes")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Notes"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Code")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Code"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Page")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Page"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("PageTitle")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["PageTitle"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Pages")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Pages"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("PageIn2600Book")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["PageIn2600Book"]) ?></td>
</tr>
</table>
<?php } ?>

<?php function showroweditor($row, $iseditmode)
  {
  global $conn;
?>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
<tr>
<td class="hr"><?php echo htmlspecialchars("ArticlesID")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="ArticlesID" value="<?php echo str_replace('"', '&quot;', trim($row["ArticlesID"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("IssuesID")."&nbsp;" ?></td>
<td class="dr"><select name="IssuesID">
<?php
  $sql = "select `IssuesID`, `Issue` from `vw_Issues`";
  $res = mysql_query($sql, $conn) or die(mysql_error());

  while ($lp_row = mysql_fetch_assoc($res)){
  $val = $lp_row["IssuesID"];
  $caption = $lp_row["Issue"];
  if ($row["IssuesID"] == $val) {$selstr = " selected"; } else {$selstr = ""; }
 ?><option value="<?php echo $val ?>"<?php echo $selstr ?>><?php echo $caption ?></option>
<?php } ?></select>
</td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Title")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="Title" maxlength="128"><?php echo str_replace('"', '&quot;', trim($row["Title"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("AuthorsID")."&nbsp;" ?></td>
<td class="dr"><select name="AuthorsID">
<?php
  $sql = "select `AuthorsID`, `NomDePlume` from `Authors`";
  $res = mysql_query($sql, $conn) or die(mysql_error());

  while ($lp_row = mysql_fetch_assoc($res)){
  $val = $lp_row["AuthorsID"];
  $caption = $lp_row["NomDePlume"];
  if ($row["AuthorsID"] == $val) {$selstr = " selected"; } else {$selstr = ""; }
 ?><option value="<?php echo $val ?>"<?php echo $selstr ?>><?php echo $caption ?></option>
<?php } ?></select>
</td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Synopsis")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="Synopsis" maxlength="2048"><?php echo str_replace('"', '&quot;', trim($row["Synopsis"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Notes")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="Notes" maxlength="1024"><?php echo str_replace('"', '&quot;', trim($row["Notes"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Code")."&nbsp;" ?></td>
<td class="dr"><select name="Code">
<?php
  $lookupvalues = array("A","C","N","S");

  reset($lookupvalues);
  foreach($lookupvalues as $val){
  $caption = $val;
  if ($val == 'A')  $caption = "In article";
  if ($val == 'C')  $caption = "Code @ 2600";
  if ($val == 'N')  $caption = "None";
  if ($val == 'S')  $caption = "See links";

  if ($row["Code"] == $val) {$selstr = " selected"; } else {$selstr = ""; }
 ?><option value="<?php echo $val ?>"<?php echo $selstr ?>><?php echo $caption ?></option>
<?php } ?></select>
</td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Page")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="Page" value="<?php echo str_replace('"', '&quot;', trim($row["Page"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("PageTitle")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="PageTitle" maxlength="128"><?php echo str_replace('"', '&quot;', trim($row["PageTitle"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Pages")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="Pages" value="<?php echo str_replace('"', '&quot;', trim($row["Pages"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("PageIn2600Book")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="PageIn2600Book" value="<?php echo str_replace('"', '&quot;', trim($row["PageIn2600Book"])) ?>"></td>
</tr>
</table>
<?php } ?>

<?php function showpagenav($page, $pagecount)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="Articles.php?a=add">Add Record</a>&nbsp;</td>
<?php if ($page > 1) { ?>
<td><a href="Articles.php?page=<?php echo $page - 1 ?>">&lt;&lt;&nbsp;Prev</a>&nbsp;</td>
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
<td><a href="Articles.php?page=<?php echo $j ?>"><?php echo $j ?></a></td>
<?php } } } else { ?>
<td><a href="Articles.php?page=<?php echo $startpage ?>"><?php echo $startpage ."..." .$count ?></a></td>
<?php } } } ?>
<?php if ($page < $pagecount) { ?>
<td>&nbsp;<a href="Articles.php?page=<?php echo $page + 1 ?>">Next&nbsp;&gt;&gt;</a>&nbsp;</td>
<?php } ?>
</tr>
</table>
<?php } ?>

<?php function showrecnav($a, $recid, $count)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="Articles.php">Index Page</a></td>
<?php if ($recid > 0) { ?>
<td><a href="Articles.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>">Prior Record</a></td>
<?php } if ($recid < $count - 1) { ?>
<td><a href="Articles.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>">Next Record</a></td>
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
<td><a href="Articles.php">Index Page</a></td>
</tr>
</table>
<hr size="1" noshade>
<form enctype="multipart/form-data" action="Articles.php" method="post">
<p><input type="hidden" name="sql" value="insert"></p>
<?php
$row = array(
  "ArticlesID" => "",
  "IssuesID" => "",
  "Title" => "",
  "AuthorsID" => "",
  "Synopsis" => "",
  "Notes" => "",
  "Code" => "",
  "Page" => "",
  "PageTitle" => "",
  "Pages" => "",
  "PageIn2600Book" => "");
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
<td><a href="Articles.php?a=add">Add Record</a></td>
<td><a href="Articles.php?a=edit&recid=<?php echo $recid ?>">Edit Record</a></td>
<td><a href="Articles.php?a=del&recid=<?php echo $recid ?>">Delete Record</a></td>
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
<form enctype="multipart/form-data" action="Articles.php" method="post">
<input type="hidden" name="sql" value="update">
<input type="hidden" name="xArticlesID" value="<?php echo $row["ArticlesID"] ?>">
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
<form action="Articles.php" method="post">
<input type="hidden" name="sql" value="delete">
<input type="hidden" name="xArticlesID" value="<?php echo $row["ArticlesID"] ?>">
<?php showrow($row, $recid) ?>
<p><input type="submit" name="action" value="Confirm"></p>
</form>
<?php
  mysql_free_result($res);
} ?>

<?php function connect()
{
  $conn = mysql_connect("localhost", "wepp", "wrec");
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
    $tmp = "'".$tmp."'";
  return $tmp;
}

function sqlstr($val)
{
  return $val; //str_replace("'", "''", $val);
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
  $sql = "SELECT * FROM (SELECT t1.`ArticlesID`, t1.`IssuesID`, lp1.`Issue` AS `lp_IssuesID`, t1.`Title`, t1.`AuthorsID`, lp3.`NomDePlume` AS `lp_AuthorsID`, t1.`Synopsis`, t1.`Notes`, t1.`Code`, t1.`Page`, t1.`PageTitle`, t1.`Pages`, t1.`PageIn2600Book` FROM `Articles` AS t1 LEFT OUTER JOIN `vw_Issues` AS lp1 ON (t1.`IssuesID` = lp1.`IssuesID`) LEFT OUTER JOIN `Authors` AS lp3 ON (t1.`AuthorsID` = lp3.`AuthorsID`)) subq";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`ArticlesID` like '" .$filterstr ."') or (`lp_IssuesID` like '" .$filterstr ."') or (`Title` like '" .$filterstr ."') or (`lp_AuthorsID` like '" .$filterstr ."') or (`Synopsis` like '" .$filterstr ."') or (`Notes` like '" .$filterstr ."') or (`Code` like '" .$filterstr ."') or (`Page` like '" .$filterstr ."') or (`PageTitle` like '" .$filterstr ."') or (`Pages` like '" .$filterstr ."') or (`PageIn2600Book` like '" .$filterstr ."')";
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
  $sql = "SELECT COUNT(*) FROM (SELECT t1.`ArticlesID`, t1.`IssuesID`, lp1.`Issue` AS `lp_IssuesID`, t1.`Title`, t1.`AuthorsID`, lp3.`NomDePlume` AS `lp_AuthorsID`, t1.`Synopsis`, t1.`Notes`, t1.`Code`, t1.`Page`, t1.`PageTitle`, t1.`Pages`, t1.`PageIn2600Book` FROM `Articles` AS t1 LEFT OUTER JOIN `vw_Issues` AS lp1 ON (t1.`IssuesID` = lp1.`IssuesID`) LEFT OUTER JOIN `Authors` AS lp3 ON (t1.`AuthorsID` = lp3.`AuthorsID`)) subq";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`ArticlesID` like '" .$filterstr ."') or (`lp_IssuesID` like '" .$filterstr ."') or (`Title` like '" .$filterstr ."') or (`lp_AuthorsID` like '" .$filterstr ."') or (`Synopsis` like '" .$filterstr ."') or (`Notes` like '" .$filterstr ."') or (`Code` like '" .$filterstr ."') or (`Page` like '" .$filterstr ."') or (`PageTitle` like '" .$filterstr ."') or (`Pages` like '" .$filterstr ."') or (`PageIn2600Book` like '" .$filterstr ."')";
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

  $sql = "insert into `Articles` (`ArticlesID`, `IssuesID`, `Title`, `AuthorsID`, `Synopsis`, `Notes`, `Code`, `Page`, `PageTitle`, `Pages`, `PageIn2600Book`) values (" .sqlvalue(@$_POST["ArticlesID"], false).", " .sqlvalue(@$_POST["IssuesID"], false).", " .sqlvalue(@$_POST["Title"], true).", " .sqlvalue(@$_POST["AuthorsID"], false).", " .sqlvalue(@$_POST["Synopsis"], true).", " .sqlvalue(@$_POST["Notes"], true).", " .sqlvalue(@$_POST["Code"], true).", " .sqlvalue(@$_POST["Page"], false).", " .sqlvalue(@$_POST["PageTitle"], true).", " .sqlvalue(@$_POST["Pages"], false).", " .sqlvalue(@$_POST["PageIn2600Book"], false).")";
  mysql_query($sql, $conn) or die(mysql_error());
}

function sql_update()
{
  global $conn;
  global $_POST;

  $sql = "update `Articles` set `ArticlesID`=" .sqlvalue(@$_POST["ArticlesID"], false).", `IssuesID`=" .sqlvalue(@$_POST["IssuesID"], false).", `Title`=" .sqlvalue(@$_POST["Title"], true).", `AuthorsID`=" .sqlvalue(@$_POST["AuthorsID"], false).", `Synopsis`=" .sqlvalue(@$_POST["Synopsis"], true).", `Notes`=" .sqlvalue(@$_POST["Notes"], true).", `Code`=" .sqlvalue(@$_POST["Code"], true).", `Page`=" .sqlvalue(@$_POST["Page"], false).", `PageTitle`=" .sqlvalue(@$_POST["PageTitle"], true).", `Pages`=" .sqlvalue(@$_POST["Pages"], false).", `PageIn2600Book`=" .sqlvalue(@$_POST["PageIn2600Book"], false) ." where " .primarykeycondition();
  mysql_query($sql, $conn) or die(mysql_error());
}

function sql_delete()
{
  global $conn;

  $sql = "delete from `Articles` where " .primarykeycondition();
  mysql_query($sql, $conn) or die(mysql_error());
}
function primarykeycondition()
{
  global $_POST;
  $pk = "";
  $pk .= "(`ArticlesID`";
  if (@$_POST["xArticlesID"] == "") {
    $pk .= " IS NULL";
  }else{
  $pk .= " = " .sqlvalue(@$_POST["xArticlesID"], false);
  };
  $pk .= ")";
  return $pk;
}
 ?>
