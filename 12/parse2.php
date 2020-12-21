<?php

  $valid_count = 0;
  $whole_file = file_get_contents("input");

  $rows = explode("\n", $whole_file);
  $count = count($rows);
  array_splice($rows, $count-1, 1);
  $count = count($rows);

  $ship_dir = "E";
  $ship_x = 0;
  $ship_y = 0;
  $way_x = 10;
  $way_y = 1;

  for($i = 0; $i < $count; $i++) {
    $ss = $rows[$i];
    $op = substr($ss, 0, 1);
    $num = substr($ss, 1, strlen($ss));
    process_command($op, $num);
  }

  $distance = abs($ship_x) + abs($ship_y);
  print "[$ship_x, $ship_y] $ship_dir\n";
  print "manhattan distance = $distance\n";

  function process_command($op, $num) {
    global $ship_x, $ship_y;
    global $way_x, $way_y;
    print "op = $op num = $num\n";
    switch($op) {
      case "N":
      $way_y += $num;
      break;
      case "S":
      $way_y -= $num;
      break;
      case "E":
      $way_x += $num;
      break;
      case "W":
      $way_x -= $num;
      break;
      case "L":
      change_dir($op, $num);
      break;
      case "R":
      change_dir($op, $num);
      break;
      case "F":
      go_forward($num);
      break;
      default:
      die("ERROR");
    }
  }

  function change_dir($op, $num) {
    global $ship_dir;
    global $way_x, $way_y;
    if ($op != "R" && $op != "L") {
      die("ERROR");
    }

    if ($op == "R") {
      if ($num == "90") {
        $num = "270";
      }
      else if ($num == "270") {
        $num = "90";
      }
      $op = "L";
    }
    switch($num) {
      //case "0":
      //$dir += 0;
      //break;
      case "90":
      $tx = $way_x;
      $ty = $way_y;
      $way_x = -$ty;
      $way_y = $tx;
      break;
      case "180":
      $way_x = -$way_x;
      $way_y = -$way_y;
      break;
      case "270":
      $tx = $way_x;
      $ty = $way_y;
      $way_x = $ty;
      $way_y = -$tx;
      break;
      default:
      die("ERROR");
    };
  }

  function go_forward($num) {
    global $ship_dir, $ship_x, $ship_y;
    global $way_x, $way_y;
    $ship_x += ($way_x*$num);
    $ship_y += ($way_y*$num);
  }

?>
