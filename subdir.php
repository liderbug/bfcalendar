<html>
<head>
<?php date_default_timezone_set('America/Denver'); ?>
# this code goes in a subdirectory for use as the admin
# suggested - this directory is named [dot]aHidd3nD1rectory#%!
<script>
function ae(value, day)
{
var value;
var day;
  if ( value == 0)
	newedit = window.open("new.php?date=${day}", "NewWin", "location=0,status=0,menubar=0,scrollbars=no,resizable=no, height=550,width=600,left=800,top=300");
  else
	newedit = window.open("edit.php?date=${date}", "NewWin", "location=0,status=0,menubar=0,scrollbars=no,resizable=no, height=550,width=600,left=800,top=300");
}
</script>
<style>
.text-center { text-align: center; }
</style>
</head>
<body>
<?php
echo "<div class=text-center><a href=edit.php>EDIT</a></div>";
include '../index.php';
?>
</body>
</html>
