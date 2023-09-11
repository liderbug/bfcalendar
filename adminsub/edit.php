<body bgcolor=#afa>
  <center><H3>EDIT</H3>
  <?php
  date_default_timezone_set('America/Denver');
  function get_desc ($newdb)
  {
    global $cols;
    $d = mysqli_query ($newdb, "desc cal_entry;");
    while ($e = mysqli_fetch_row ($d)) { $cols[] .= "a$e[0]"; }
    $d = mysqli_query ($newdb, "desc cal_misc;");
    while ($e = mysqli_fetch_row ($d)) { $cols[] .= "b$e[0]"; }
  }
  
  # ========== main  ==========
  include '../.dbconnect.php';
  $cols = array ();
  echo "<form action=edit.php method=POST>";
  $indate = $_GET['indate']; 
  $query = $_POST['query'];
  $date = $_POST['date'];
  $iyear = substr($indate, 0,4);
  $imonth = substr($indate, 4,2);
  $word = $_POST['word'];
  $id = $_POST['id'];
  $idg = $_GET['idg'];
  $year   = $_POST['year']; if ($year == '') $year   = $_GET['year'];
  $month  = $_POST['month']; if ($month == '') $month   = $_GET['month'];

  $descx = array ( "DB id event", "Initial date, sets day of week", "Lenght in hours", "Does not exist after", "Once? Every Tues? Third Thur?", "Name of event", "If 2 this event can be bumped", "DB id misc", "Notes", "Who? How?", "In the building? Park?", "Paid? Sponsered? Free?", "When?", "Who created");
  if ($idg > '')
  {
    get_desc ($newdb);
    $q = "select e.*, m.* from cal_entry e, cal_misc m where e.id = $idg and m.id = e.id;";
    $q1 = mysqli_query ($newdb, $q);
    $r = mysqli_fetch_row ($q1);
    $res = array_combine($cols, $r);
    $retdate = "";
    echo "<table border=5>";
    for ($n=0; $n<sizeof($cols); $n++)
    {
      $r[$n] = htmlspecialchars($r[$n],ENT_QUOTES);
      $col = substr($cols[$n], 1);
      echo " <tr> <td>$col</td>";
      if (strstr($cols[$n], "date"))
      {
        $dt = date('Y-m-d H:i', $r[$n]);
        if ($retdate == '')
        { 
          $retdate = $dt;
          $year = substr($retdate, 0, 4);
          $month = substr($retdate, 5, 2);
        }
        echo "<td><input type='datetime-local' id='$cols[$n]' name='$cols[$n]' value='$dt'> </td><td>$descx[$n]</td>";
      } else {
if ( strstr ($cols[$n], 'location'))
{
$l1 = array ("Club", "Pavilion", "Club+Pavilion", "N40", "Parking", "Other");
$x=1;
foreach ($l1 as $l)
{
if ("$l" == "$r[$n]")
  break;
$x++; 
}
$s1=$s2=$s3=$s4=$s5=$s6=""; $x = "s$x"; $$x = "selected";
echo "
<td><select name=blocation>
<option value=Club $s1>Club
<option value=Pavilion $s2>Pavilon
<option value=Club+Pavilion $s3>Club & Pavilion
<option value=N40 $s4>N40
<option value=Parking $s5>Parking
<option value=Other $s6>Other
</select>
$x
</td>";
} else
if ( strstr ($cols[$n], 'type'))
{
$l1 = array ("Discount", "Paid", "Free", "Sponsored","Other");
$x=1;
foreach ($l1 as $l)
{
if ("$l" == "$r[$n]")
  break;
$x++; 
}
$s1=$s2=$s3=$s4=$s5=$s6=""; $x = "s$x"; $$x = "selected";
echo "<td><select name=btype>
<option value=Discount $s1>Discount
<option value=Paid $s2>Paid
<option value=Free $s3>Free
<option value=Sponsored $s4>Sponsored
<option value=Other $s5>Other
</select>
$x
</td>";
} else
  echo "<td><input type=text name=$cols[$n] value='$r[$n]' size=25></td><td>$descx[$n]</td></tr>\n";
      }
    }
    echo "</table><input type=hidden name=aid value=$idg>";
    echo "<input type=hidden name=year value=$year>";
    echo "<input type=hidden name=month value=$month>";
    echo "<input type=submit name=query value=Update>";
    echo "<input type=submit name=query value=Cancel>";
    echo "<input type=submit name=query value=Delete>";
  } else {
    switch ($query)
    {
      case 'Delete': $id = $_POST['aid'];
            $qude = "delete from cal_entry where id = $id;";
            $qudm = "delete from cal_misc  where id = $id;";
            $d = mysqli_query ($newdb, $qude);
            $d = mysqli_query ($newdb, $qudm);
            echo "<meta http-equiv='refresh' content='0;url=index.php?year=$iyear&month=$imonth' />";
            break;
      case 'Update': get_desc ($newdb);
           $id = $_POST['aid'];
           $qud =  "UPDATE cal_entry a INNER JOIN cal_misc b ON (a.id = $id and a.id = b.id) set ";
           for ($n=0; $n<sizeof($cols); $n++)
           {
             $t = substr($cols[$n],0,1);
             $c = substr($cols[$n],1);
             $v = htmlspecialchars($_POST[$cols[$n]]);
             switch ($c)
             {
               case 'id': break;
               case 'date': $s = strtotime($v); $qud .= "$t.$c=$s, \n"; break;
               case 'end_date': $s = strtotime($v); $qud .= "$t.$c=$s, \n"; break;
               case ( is_numeric($cols[$n]) ): $qud .= "$t.$c=$v, \n"; break;
               case 'mod_date': $qud .= "$t.$c=" . strtotime("now") . ", \n"; break;
               default: $qud .= "$t.$c='$v', \n"; break;
             }
            }
            $qud = preg_replace("/,([^,]+)$/", "", $qud);
            $qud .= " WHERE a.id = b.id;";
            $d = mysqli_query ($newdb, $qud);
#xcal
            echo "<meta http-equiv='refresh' content='0;url=index.php?year=$iyear&month=$imonth' />";
            break;
      case 'Cancel':
            echo "<meta http-equiv='refresh' content='0;url=index.php?year=$iyear&month=$imonth' />";
      break;
      case 'Calendar':
            echo "<meta http-equiv='refresh' content='0;url=index.php?year=$iyear&month=$imonth' />";
      break;
      case 'search':
      if ($date > '')
      {
        $date = strtotime($date);
        $sdate = $date - 86400;
        $edate = $date + 86400;
        echo "select * from cal_entry where date between $sdate and $edate;<br>";
        $q1 = mysqli_query ($newdb, "select * from cal_entry where date between $sdate and $edate;");
        echo "<table border=1>";
        while ($r = mysqli_fetch_array ($q1))
        {
          echo "<tr><td><a href=edit.php?idg=$r[0]>$r[0]</a></td><td>$r[5]</td></tr>";
        }
        echo "</table>";
      } else if ($word > '')
      {
        $q1 = "select * from cal_entry where description like '%$word%';";
        $q1 = mysqli_query ($newdb, $q1);
        echo "<table border=1>";
        while ($r = mysqli_fetch_array ($q1))
        {
          echo "<tr><td><a href=edit.php?idg=$r[0]>$r[0]</a></td><td>$r[5]</td></tr>";
        }
        echo "</table>";
      }
      break;
      case 'edit':
      default:
      echo "In 'Word' anything in 'Name' or sort by 'Date'<br>
      <table border=1 width=80% align=center>
      <tr><td>ID</td><td>
      <input type=text name=id>
      </td></tr>
      <tr><td>Word</td><td>
      <input type=text name=word>
      </td></tr>
      <tr><td>Date</td><td>
      <input type='datetime-local' name='date'> *required
      </td></tr>
      </table>
      <input type=submit name=query value=search>
      <input type=submit name=query value=calendar>";
    }
  }
  ?>
  </form>
