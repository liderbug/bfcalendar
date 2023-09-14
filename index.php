<head>
<script type="text/javascript">
function selcat(val) {
  var val;
  var  url = `https://yourhost]/webcal/index.php?setcat=${val}`;
  window.location.href = url;
}
</script>
<style>
.text-right { text-align: right; }
</style>
</head>

<body bgcolor=#AFA>
<?php
function lilo ()
{
  $cwd = getcwd();
  if ( strstr($cwd, "foo4fee5") )
  { 
    echo "<p valign=top class='text-right'><small><a href=../index.php>logout</a></p>";
  } else {
    echo "<p class='text-right'><small><a href=44foo4fee5>admin</a></p>";
  }
}

function do_month() { return (-1); }

function do_week($x, $dow, $rmo, $ryr)
{
    $name = array( '', 'first', 'second', 'third', 'fourth', 'fifth');
    $wom  = $name[$x];
    return (date('j', strtotime("$wom $dow of $rmo $ryr")));
}

function dow($ts)
{
    $down = date("w", $ts);
    $name = array( "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    return ($name[date('w', $ts)]);
}

function do_day($day, $darray, $date, $today)
{
	$cwd = getcwd();
	if ( strstr($cwd, "foo4fee5") ) { $pw=1; }
    if ($day > 0) {
        $bgc = ($date == $today) ? "lightgreen" : "yellow";
        echo "<td width=14% valign=top bgcolor=$bgc>$day";
		if ($pw == 1) { 
			echo "<a href=new.php?date=$date> Add</a>";
		}
		echo "<br>\n";
        $block = 0;
        foreach ($darray as $events) {
            $i = explode(",", $events);
            foreach ($i as $j) {
                $k = explode("|", $j);
                if ($k[2] != '') {
                    $H = date('g:i A', $k[2]);
                    $B = date('H', $k[2]);
                    if ("$H" == "12 AM")
                        $H = "Allday<br>";
                    if ($pw) { $block = 0; $k1 = $k[1]; }
                    if ($block <= $B) {
                        $block = 0;
                        if ($pw) echo "<a href=edit.php?idg=$k[5]>";
                        echo "<small>$H($k[4]); $k1<br> &nbsp; $k[3]</small><p>\n";
                        if ($pw) echo "</a>";
                    }
                    if ($k[1] == 1) {
                        $block = $B + $k[4];
                    }
                }
            }
        }
    } else { # a non-this-month table cell
        echo "<td width=100 height=100 valign=top bgcolor=pink> </td>\n";
    }
    echo "</td>\n";
}

# ----- main () -----
$Home = "[yourhost]/webcal";
date_default_timezone_set('America/Denver');

if ( isset ($_GET['month']))
{
  $month = $_GET['month'];
  $year = $_GET['year'];
} else {
  $adom = $_POST['adom'];
  $a = explode (",", $adom);
  $month = $a[0];
  $year = $a[1];
}

$setcat = $_GET['setcat'];
echo "<form action=index.php method=POST>";

# Jump to year:month - 202409 = sept 2024.  9=sept current yr. 20243= march 2024.
$moday = $_POST['moday'];
switch (strlen($moday))
{
  case 1:
  case 2: $month = substr($moday, 0,2); break;
  case 4: $year  = substr($moday, 0,4); break;
  case 5: $year  = substr($moday, 0,4);
          $month = substr($moday, -1);  break;
  case 6: $month = substr($moday, -2);
          $year  = substr($moday, 0,4); break;
  case 7: $month = substr($moday, -1);
          $year  = substr($moday, 0,4); break;
  default: break;
}

# Clicking on prev/next month increments month - check for going from Dec to Jan
$monthm = $_POST['monthm_x']; #get month +
$monthp = $_POST['monthp_x']; #get month -
if ($monthm) $month--;
if ($monthp) $month++;
if ($year == '') { $year = date('Y'); }
if ($month == '') { $month = date('m'); }
if ($month == 0)  { $month = 12; $year--; }
if ($month == 13) { $month = 1; $year++; }
if ($month == '') { $month = date('m'); }

echo "<input type=hidden name=month value=$month>";
echo "<input type=hidden name=year value=$year>";

$mw  = sprintf("%4d%02d%02d", $year, $month, 01);
$m1  = sprintf("%02d", $month);
$m2  = date('F Y', strtotime($mw)); # month year for header
$rmo = date('F', strtotime($mw));

# Start Calendar
echo "<table border=0 width=95% align=center>
    </td></tr>
  <tr height=100px>
    <td align=left valign=middle width=20%>
      <input type=image src=https://$Home/images/leftarrow.gif alt=Submit value=prev name=monthm />
    </td>
    <td align=center ><H2><br><b>$m2</b></H2>";
# category 1 is Paid & Sponsored. Cat 2 is Free or Discounted that can be bumped for cat 1 events
if ($setcat == 0) { $sel0='selected'; }
if ($setcat == 1) { $sel1='selected'; }
if ($setcat == 2) { $sel2='selected'; }
echo "<select onchange=selcat(value)>
  <option value=0 $sel0>All Events</option>
  <option value=1 $sel1>Reserved Events</option>
  <option value=2 $sel2>Community Events</option>
</select>
<input type=text name=moday placeholder='2024 09' size=8><input type=submit value='Date'>
</td><td align=right valign=middle width=20%>";
echo "<table border=0 height=100%><tr><td valign=top>";
lilo ();
echo "</td></tr><tr><td valign=top><input type=image src=https://$Home/images/rightarrow.gif alt=Submit value=next name=monthp />";
echo "</td></tr></table>";
echo "</td></tr>";
echo "<tr height=100px><td colspan=3>";

exec("cal $month $year | tr -d '_' | tr -d '\b'", $month1); # Use system "cal" command
$week = array_slice($month1, 2);
$x    = substr($week[0], -1);
$day  = $x - 6;

$last  = ($week[5]) ? substr($week[5], -2) : substr($week[4], -2);
#echo "L $last ";
$wpm   = (strlen($week[5]) == 0) ? 4 : 5;
$today = date('Ymd', time());

$som = strtotime(sprintf("%4d%02d%02d", $year, $m1, "01"));
$eom = strtotime(sprintf("%4d%02d%02d235959", $year, $m1, $last));
$now = strtotime("now");

$carray = array_fill(0, 32, array_fill(0,0, ''));  # array ( array () ) - for 31 days

include './.dbconnect.php'; 
$getcat = "";
switch ($setcat) { # display all or cat 1 or cat 1 events
    case 0: $getcat = ""; break;
    case 1: $getcat = "and category = 1 "; break;
    case 2: $getcat = "and category = 2 "; break;
    default: echo "WTFO<br>"; break;
}

# once
$q1 = "select id, date, description, category, duration from cal_entry where repeats = 'once' and date between $som and $eom $getcat order by date desc;";
$r1 = mysqli_query($newdb, $q1);

while ($r = mysqli_fetch_array($r1)) {
    $ts = date('j', $r[1]);
    $H  = date('H', $r[1]);
    array_push($carray[$ts], "$H|$r[3]|$r[1]|$r[2]|$r[4]|$r[0]");
}

# repeats
$q2   = "select id,date,repeats,description,category,duration from cal_entry where repeats != 'once' and end_date > $som $getcat;";
#echo "$q2<br>";
$r1   = mysqli_query($newdb, $q2);
#$num_rows = mysqli_num_rows($r1);
$ryr  = $year;
$rday = date('j', $r[1]);
while ($r = mysqli_fetch_array($r1)) {
    $lweek = 0;
    $dow = dow($r[1]);
    $i   = explode(",", $r[2]);
    $lts = 0;
    for ($x = 1; $x <= count($i) - 1; $x++) {
        switch ($i[0]) {
            case 'y':
                if ($r[1] >= $som && $r[1] <= $eom)
                    $ts = date('j', $r[1]);
                break;
            case 'm':
                $ts = do_month(); #thinking ahead
                break;
            case 'w':
                $ts = do_week($i[$x], $dow, $rmo, $ryr);
                 if ($ts > $lweek) { $lweek = $ts; $push=1; } else { $push=0;}
                break;
            default:
                echo "dow $dow<br>";
                break;
        }
        if ($push)
        {
          $H = date('H', $r[1]);
          array_push($carray[$ts], "$H|$r[4]|$r[1]|$r[3]|$r[5]|$r[0]");
        }
    }
}

#sort each day by time, category
for ($n = 0; $n <= $last; $n++)
    sort($carray[$n]);

echo "<table border=1 width=100%><tr height=100px><th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th></tr>\n";
foreach ($week as $w) {
    $w = trim($w);
    echo "<tr height=100px>\n";
    for ($d = 0; $d <= 6; $d++) {
      $date = sprintf("%4d%02d%02d", $year, $month, $day);
      do_day($day, $carray[$day], $date, $today, $carray[5]);  # do each day in the table
      
      if ($day == $last) {
        while ($d < 6) {
          $d++;
          do_day(0, $carray, $date, $today, $carray[5]);
        }
        echo "</tr></table>";
		 echo "<input type=hidden name=adom value='$month,$year,1,2,3'>";
        exit;
      }
      $day++;
    }
    echo "</tr>\n";
}


?>
</table>
<td><tr></table>
</form>
