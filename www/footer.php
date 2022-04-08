<!--
  footer.php
-->

<?php

/* work with vhost or subfolder - locate stylesheet */
  if (is_file ("style.css"))  {
    $fullpath = "";
  }
  elseif (is_file ("/style.css"))  {
    $fullpath = "/";
  }
  elseif (is_file ("../style.css"))  {
    $fullpath = "../";
  }

?>

<br>
<!-- original
Copyleft &copy 2008<?php //if (date("Y") <> 2008) echo "-" . date("Y"); ?> w.r.epp<br>
-->
Copyleft <img src="<?php echo $fullpath ?>Copyleft.jpg" style="vertical-align: bottom" alt="Copyleft image"> 2008<?php if (date("Y") <> 2008) echo "-" . date("Y"); ?> William R. Epp, banner by Max Nardi.
<br>
