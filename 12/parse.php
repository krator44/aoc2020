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
    print "op = $op num = $num\n";
    switch($op) {
      case "N":
      $ship_y += $num;
      break;
      case "S":
      $ship_y -= $num;
      break;
      case "E":
      $ship_x += $num;
      break;
      case "W":
      $ship_x -= $num;
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
    $dir = 0;
    switch($ship_dir) {
      case "N":
      $dir = 1;
      break;
      case "S":
      $dir = 3;
      break;
      case "E":
      $dir = 0;
      break;
      case "W":
      $dir = 2;
      break;
      default:
      die("ERROR");
    };
    switch($num) {
      //case "0":
      //$dir += 0;
      //break;
      case "90":
      $dir += 1;
      break;
      case "180":
      $dir += 2;
      break;
      case "270":
      $dir += 3;
      break;
      default:
      die("ERROR");
    };
    if ($dir > 3) {
      $dir -= 4;
    }
    switch($dir) {
      case 0:
      $ship_dir = "E";
      break;
      case 1:
      $ship_dir = "N";
      break;
      case 2:
      $ship_dir = "W";
      break;
      case 3:
      $ship_dir = "S";
      break;
      default:
      die("ERROR");
    };
  }

  function go_forward($num) {
    global $ship_dir, $ship_x, $ship_y;
    switch($ship_dir) {
      case "N":
      $ship_y += $num;
      break;
      case "S":
      $ship_y -= $num;
      break;
      case "E":
      $ship_x += $num;
      break;
      case "W":
      $ship_x -= $num;
      break;
      default:
      die("ERROR");
    }
  }

?>
