<?php

  $valid_count = 0;
  $whole_file = file_get_contents("input");

  $rows = explode("\n", $whole_file);
  $count = count($rows);
  array_splice($rows, $count-1, 1);
  $count = count($rows);

  $timestamp = $rows[0];
  $fields = explode(",", $rows[1]);

  $busses = array();

  foreach($fields as $val) {
    if($val != "x") {
      $busses[] = $val;
    }
  }
  
  print "timestamp $timestamp\n";
  print_r($busses, false);

  $earliest = array();

  foreach($busses as $bus) {
    $xx = $timestamp % $bus;
    $earliest[$bus] = $timestamp - $xx + $bus;
  }

  $key = 0;
  $min = 0x7fffffff;
  foreach($earliest as $bus => $departure) {
    if ($departure < $min) {
      $min = $departure;
      $key = $bus;
    }
  }

  $departure_time = $earliest[$key];
  $arrival_time = $departure_time + $key;
  $total_time = $arrival_time - $timestamp;
  $wait_time = $departure_time - $timestamp;
  $answer = $key * $wait_time;

  print "we can leave on bus $key at $departure_time ";
  print "and arrive at $arrival_time\n";
  print "it will take $total_time minutes in total\n";
  print "$key x $wait_time = $answer\n";

?>
