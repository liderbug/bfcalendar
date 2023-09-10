<body bgcolor=#afa>
<center>
<P>&nbsp; <P>&nbsp;
<form method=post action=new.php>
<?php
date_default_timezone_set('America/Denver');

if ( ! $_POST['addevent'] == 'Submit Query' )
{
$indate = $_GET['date'];
$dt = sprintf ("%4d-%02d-%02dT00:00", substr($indate, 0,4), substr($indate, 4,2), substr($indate, 6,2));
echo "
<table border=1 align=center>
<tr><td>Date</td><td>
<input type='datetime-local' name='date' value='$dt'> *required
</td></tr>
<tr><td>Dur</td><td><input type=text name=dur size=10 value=1> * if non-zero</td></tr>
<tr><td>End</td><td>
<input type='datetime-local' name='end'> * then End not needed
</td></tr>
<tr><td>Repeat</td><td><input type=text name=rep size=30 value=once> * 'once' or 'w,3' or 'w,2,4'</td></tr>
<tr><td>Event</td><td><input type=text name=name size=40 ></td></tr>
<tr><td>Category</td><td><input type=text name=cat size=10 value=2> * 1=Reserved, 2=CommEvent</td></tr>
<tr><td><hr></td><td><hr></td></tr>
<tr><td>Note</td><td><input type=text name=desc size=40 ></td></tr>
<tr><td>Contact</td><td><input type=text name=contact size=40 ></td></tr>
<tr><td>Location</td><td>
<select name=location>
<option value=Club selected>Club
<option value=Pavioiln>Pavilion
<option value=Club+Pavilion>Club & Pavioion
<option value=N40>N40
<option value=Parking>Parking
<option value=Other>Other
</select>
 * Club, Pavilion, C&P, N40</td></tr>
<tr><td>Type</td><td>
<select name=rtype id=rtype>
<option value=Discount selected>Discount
<option value=Paid>Paid
<option value=Free>Free
<option value=Sponsored>Sponsored
<option value=Other>Other
</select>

 * Paid, Discount, Free, </td></tr>

 `location` enum('Club','Club+Pavilion','Pavilion','N40','Parking') DEFAULT 'Club',
     `type` enum('Paid','Discount','Free','Sponsored') DEFAULT NULL,

<!--tr><td>ModDate</td><td>
<input type='datetime-local' name='mod'>
</td></tr-->
<tr><td>ByWho</td><td><input type=text name=bywho size=40 ></td></tr>
</table>
<input type=submit name=addevent value=SAVE>
";
} else {
include '../.dbconnect.php';
$indate = $_POST['date'];
$year = substr($indate, 0,4);
$month = substr($indate, 5,2);
echo "<pre></center>";

$list = array ('date', 'dur', 'end', 'rep', 'name', 'desc', 'contact', 'location', 'rtype', 'moddate', 'who','cat');
foreach ($list as $l) { $$l = $_POST [$l]; }
$name = str_replace ("'", "\\'", $name);
$date = strtotime($date);
$end = strtotime($end);
$moddate = strtotime('now');
#exec ("grep 44foo4fee5 /home/chulid/logs/blkfst.com/https/access.log | cut -d' ' -f3 | grep -v '-' | tail -1", $who);
$end = ( $end == '' ) ? $date+(3600)*$dur:$end;
$q1i = "insert into cal_entry values (0, $date, $dur, $end, '$rep', '$name', '$cat');";
$r1 = mysqli_query ($newdb, $q1i);
$id = mysqli_query ($newdb, "select max(id) from cal_entry;");
$id1 = mysqli_fetch_row ($id);
$q2i = "insert into cal_misc values ($id1[0], '$desc', '$contact', '$location', '$rtype', $moddate, '$who[0]');";
$r2 = mysqli_query ($newdb, $q2i);
echo "<input type=button value=CAL onclick=window.location.href=index.php>";
echo "<meta http-equiv='refresh' content='0;url=index.php?year=$year&month=$month' />";
}

?>
</form>
