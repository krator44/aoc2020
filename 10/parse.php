<?php

  $valid_count = 0;
  $whole_file = file_get_contents("input");

  $adapters = explode("\n", $whole_file);
  //print "xx".$adapters[$count-1]."XX\n";
  $count = count($adapters);
  array_splice($adapters, $count-1, 1);
  $adapters[] = max($adapters) + 3;
  $adapters[] = "0";
  $count = count($adapters);
  
  sort($adapters);
  
  $last = null;
  $one_count = 0;
  $three_count = 0;
  foreach($adapters as $val) {
    if ($last == null) {
      $last = $val;
      continue;
    }
    $diff = $val - $last;
    if ($diff == 1) {
      $one_count++;
    }
    else if ($diff == 3) {
      $three_count++;
    }
    print("$val  $diff\n");
    $last = $val;
  }
  $total = $one_count * $three_count;
  $xx = $count-2;
  print "total $xx adapters\n";
  print "$one_count * $three_count = $total joltage\n";


?>
