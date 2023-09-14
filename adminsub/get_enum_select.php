<?php
  
  # Func: DB, Table, Column, prev.selected, prefix if joined tables
  # joined tables: tabA data for web display, 
  #  tabB data for admin, how paid, contact info, notifications

  function do_enum ($db, $table, $ecol, $seled, $ab)
  {
    # get the list of unums
    $q1 = mysqli_query ($db, "show columns from $table where field = '$ecol';");
    $u1 = mysqli_fetch_row ($q1);
    # convert to an array
    $enumarr = preg_split ("/\,/", (str_replace ("'", "", substr($u1[1], 5, -1))));
    # which one was selected?
    $sel=0;
    foreach ($enumarr as $l)
    { 
      if ("$l" == "$seled") break;
      $sel++;
    }
    # generate code
    $n=0;
    echo "<select name=$ab$ecol>\n";
    foreach ($enumarr as $item)
    {
      $seled = ($sel == $n) ? "selected":""; # which one gets "selectED"
      echo "<option value=$item $seled>$item\n";
      $n++;
    }
    echo "</select>";
  }

