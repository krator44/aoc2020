<?php

  $valid_count = 0;
  $whole_file = file_get_contents("input");

  $rows = explode("\n", $whole_file);
  //print "xx".$adapters[$count-1]."XX\n";
  $count = count($rows);
  array_splice($rows, $count-1, 1);
  $count = count($rows);

  $width = strlen($rows[0]);
  $height = $count;
  $map = $rows;

  $round = 0;
  $maplast = $map;
  $state_changed = false;

  print("$width x $height\n");
  
  //seat_set(0,0, "#");
  //process_seat(0, 0);
  //$maplast = $map;
  //print_map();

  //die();
  for(;;) {
    //print_r($maplast, false);
    //print_r($map, false);
    print_map();
    print_occupied();
    $maplast = $map;
    $state_changed = false;
    for($y = 0; $y < $height; $y++) {
      for($x = 0; $x < $width; $x++) {
        process_seat($x, $y);
      }
    }
    if (!$state_changed) {
      break;
    }
    $round++;
    print "end round $round\n\n";
  }
  print_map();
  print_occupied();

  $total_occupied = count_total_occupied();

  print "after $round rounds\n";
  print "$total_occupied seats occupied\n";
  

  function print_map() {
    global $width, $height;
    for ($y = 0; $y < $height; $y++) {
      for ($x = 0; $x < $width; $x++) {
        $s = seat_get($x, $y);
        print $s;
      }
      print "\n";
    }
    print "\n";
  }

  function print_occupied() {
    global $width, $height;
    for ($y = 0; $y < $height; $y++) {
      for ($x = 0; $x < $width; $x++) {
        $s = count_occupied($x, $y);
        print $s;
      }
      print "\n";
    }
    print "\n";
  }

  function count_total_occupied() {
    global $width, $height;
    $total = 0;
    for ($y = 0; $y < $height; $y++) {
      for ($x = 0; $x < $width; $x++) {
        $s = seat_get($x, $y);
        if ($s == "#") {
          $total++;
        }
      }
    }
    return $total;
  }

  function process_seat($x, $y) {
    global $map, $maplast;
    //print "processing $x $y\n";
    $s = seat_get($x, $y);
    if ($s == "L" || $s == "#") {
      $occ = count_occupied_ray($x, $y); 
    }
    else {
      return;
    }
    if ($s == "L") {
      if ($occ == 0) {
        seat_set($x, $y, "#");
      }
    }
    else if ($s == "#") {
      if ($occ >= 5) {
        seat_set($x, $y, "L");
      }
    }
  }

  function seat_get($x, $y) {
    global $maplast, $width, $height;
    if ($x < 0 || $x >= $width ||
      $y < 0 || $y >= $height) {
      return ".";
    }
    $state = $maplast[$y][$x];
    return $state;
  }

  function seat_set($x, $y, $state) {
    global $map, $maplast, $state_changed;
    global $width, $height;
    if ($x < 0 || $x >= $width ||
      $y < 0 || $y >= $height) {
      die ("ERROR");
    }
    $old_state = seat_get($x, $y);
    if ($old_state != $state) {
      $map[$y][$x] = $state;
      $state_changed = true;
    }
  }

  function count_occupied_ray($x, $y) {
    $total = 0;
    if (ray_get($x, $y, -1, 0) == "#") {
      $total++;
    }
    if (ray_get($x, $y, 1, 0) == "#") {
      $total++;
    }
    if (ray_get($x, $y, -1, 1) == "#") {
      $total++;
    }
    if (ray_get($x, $y, 1, 1) == "#") {
      $total++;
    }
    if (ray_get($x, $y, 0, 1) == "#") {
      $total++;
    }
    if (ray_get($x, $y, -1, -1) == "#") {
      $total++;
    }
    if (ray_get($x, $y, 1, -1) == "#") {
      $total++;
    }
    if (ray_get($x, $y, 0, -1) == "#") {
      $total++;
    }
    return $total;
  }

  function ray_get($x, $y, $dx, $dy) {
    for (;;) {
      $x += $dx;
      $y += $dy;
      if (!in_bounds($x, $y)) {
        return ".";
      }
      $s = seat_get($x, $y);
      if ($s == "L") {
        return "L";
      }
      else if ($s == "#") {
        return "#";
      }
    }
  }

  function in_bounds($x, $y) {
    global $width, $height;
    if ($x < 0 || $x >= $width ||
      $y < 0 || $y >= $height) {
      return false;
    }
    return true;
  }

  function count_occupied($x, $y) {
    $total = 0;
    if (seat_get($x-1, $y) == "#") {
      $total++;
    }
    if (seat_get($x+1, $y) == "#") {
      $total++;
    }
    if (seat_get($x-1, $y+1) == "#") {
      $total++;
    }
    if (seat_get($x+1, $y+1) == "#") {
      $total++;
    }
    if (seat_get($x, $y+1) == "#") {
      $total++;
    }
    if (seat_get($x-1, $y-1) == "#") {
      $total++;
    }
    if (seat_get($x+1, $y-1) == "#") {
      $total++;
    }
    if (seat_get($x, $y-1) == "#") {
      $total++;
    }
    return $total;
  }
?>
