<?php

  $valid_count = 0;
  $whole_file = file_get_contents("input");


  $rows = explode("\n\n", $whole_file);
  $count = count($rows);
  array_splice($rows, $count-1, 1);
  $count = count($rows);
  
  $pieces = array();

  foreach($rows as $row) {
    $fields = explode(":", $row);
    $key = substr($fields[0], -4);
    $data = explode("\n", $fields[1]);
    array_splice($data, 0, 1);
    //print "x$key XX\n";
    //print_r($data, false);
    $pieces[$key] = $data;
  }

  //print_r($pieces, false);

  $edges = array();

  $unique = array();

  extract_edges();
  extract_uniqueness();

  print_r($edges);

  print_r($unique, false);
  
  $total = 1;
  foreach($unique as $key => $val) {
    if($val >= 2) {
      print("key $key\n");
      $total = $total * $key;
    }
  }
  print "product $total\n";
  
  //$xx = count_matches("2971", 0);
  //print("$xx matches\n");

  print "there are $count pieces\n";

  
  function extract_uniqueness() {
    global $pieces, $edges, $unique;
    foreach($edges as $key => $edge) {
      $tt = 0;
      for($i=0;$i<4;$i++) {
        $n = count_matches($key, $i);
        if($n == 0) {
          $tt++;
        }
      }
      $unique[$key] = $tt;
    }
  }

  function count_matches($key, $index) {
    global $edges;
    $num_matches = 0;
    $ss = $edges[$key][$index];
    foreach($edges as $key2 => $edge2) {
      if ($key == $key2) {
        continue;
      }
      for($i=0;$i<4;$i++) {
        $ss2 = $edge2[$i];
        $ss2r = strrev($ss2);
        if ($ss2r == $ss || $ss2 == $ss) {
          $num_matches++;
        }
      }
    }
    return $num_matches;
  }

  function extract_edges() {
    global $pieces, $edges;
    foreach($pieces as $key => $piece) {
      extract_edge($key, $piece);
    }
  }

  function extract_edge($key, $piece) {
    global $edges;
    $edg_arr = array();
    // top
    $ss = "";
    for($i=0;$i<10;$i++) {
      $ss .= $piece[0][9-$i];
    }
    $edg_arr[0] = $ss;
    // left
    $ss = "";
    for($i=0;$i<10;$i++) {
      $ss .= $piece[$i][0];
    }
    $edg_arr[1] = $ss;
    // bottom
    $ss = "";
    for($i=0;$i<10;$i++) {
      $ss .= $piece[9][$i];
    }
    $edg_arr[2] = $ss;
    // right
    $ss = "";
    for($i=0;$i<10;$i++) {
      $ss .= $piece[9-$i][9];
    }
    $edg_arr[3] = $ss;
    $edges[$key] = $edg_arr;
  }

?>
