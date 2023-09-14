  <body bgcolor=#afa>
  <center>
  <P>&nbsp; <P>&nbsp;
  <form method=post action=new.php>
  <?php
  
  include 'get_enum_select.php';
  
  date_default_timezone_set('America/Denver');
  include '../.dbconnect.php';
  
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
    <tr><td>Contact</td><td><input type=text name=contact size=40 ></td></tr>";
        
    echo "<tr><td>Location</td><td>";
    do_enum ($newdb, 'cal_misc', 'location', $r[$n], 'b');
    echo "</td></tr>";
    
    echo "<tr><td>Paytype</td><td>";
    do_enum ($newdb, 'cal_misc', 'paytype', $r[$n], 'b');
    echo "</td></tr>";
  
    echo "<tr><td>ByWho</td><td><input type=text name=bywho size=40 ></td></tr>
    </table>
    <input type=submit name=addevent value=SAVE>
    ";
  } else {
    $indate = $_POST['date'];
    $year = substr($indate, 0,4);
    $month = substr($indate, 5,2);
    
    $list = array ('date', 'dur', 'end', 'rep', 'name', 'desc', 'contact', 'location', 'paytype', 'moddate', 'who','cat');
    foreach ($list as $l) { $$l = $_POST [$l]; }
    $name = str_replace ("'", "\\'", $name);
    $date = strtotime($date);
    $end = strtotime($end);
    $moddate = strtotime('now');
    exec ("grep [your admin subdir] [your host logs location]/https/access.log | tail -1 | cut -d' ' -f3", $who);
    $end = ( $end == '' ) ? $date+(3600)*$dur:$end;
    $q1i = "insert into cal_entry values (0, $date, $dur, $end, '$rep', '$name', '$cat');";
    $r1 = mysqli_query ($newdb, $q1i);
    $id = mysqli_query ($newdb, "select max(id) from cal_entry;");
    $id1 = mysqli_fetch_row ($id);
    $q2i = "insert into cal_misc values ($id1[0], '$desc', '$contact', '$location', '$paytype', $moddate, '$who[0]');";
    $r2 = mysqli_query ($newdb, $q2i);
    echo "<meta http-equiv='refresh' content='0;url=index.php?year=$year&month=$month' />";
  }
  
  ?>
  </form>
