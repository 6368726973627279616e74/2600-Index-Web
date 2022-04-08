<!--
  mirrors.php

  Author:      wrepp
  Date:        01/11/2016
  Description: seperate page for mirrors of 2600 index web site
  Notes:
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html>
  <head>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8">  
  <title>Mirrors</title>
  <?php include ("header.php"); ?>
  </head>

  <body>

  <?php include ("page.php"); ?>

  <div id="content">

    <p class="section">Mirrors</p>

    <p>
       2600 index website info, please contact me if you would like to host a <a href="https://en.wikipedia.org/wiki/Mirror_website">mirror.</a>
    </p>

    <table width="75%" cellspacing="1px" cellpadding="2px" >
      <thead>
        <tr bgcolor="LIGHTGREY">
          <td>Description</td>
          <td>Log retention</td>
          <td>Analytics</td>
          <td>Hosting</td>
          <td>Server</td>
          <td>Url</td>
        </tr>
      </thead>
      <tr>
        <td>Main site</td>
        <td>Three days - apache</td>
        <td><a href="https://www.2600index.info/webalizer">Webalizer - ip's ignored</a></td>
        <td><a href="https://www.vultr.com">Vultr - USA</a></td>
        <td>VPS - Chicago, USA</td>
        <!-- <td><a href="https://www.2600index.info">www.2600index.info</a></td> -->
        <td>www.2600index.info shutdown 11/30/2020</td>
      </tr>
      <tr>
        <td>Mirror One</td>
        <td>Three days - apache</td>
        <td><a href="https://www.2600index.website/webalizer">Webalizer - ip's ignored</td>
        <td><a href="https://www.digitalocean.com/">Digital Ocean - USA</a></td>
        <td>VPS - New York, USA</td>
        <td><a href="https://www.2600index.website">www.2600index.website</td>
      </tr>
    </table>


  <div id="foot">
    <?php include ("footer.php"); ?>
  </div>

  </body>
</html>
