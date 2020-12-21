<?php

  $valid_count = 0;
  $whole_file = file_get_contents("input");


  $rows = explode("\n\n", $whole_file);
  $count = count($rows);
  array_splice($rows, $count-1, 1);
  $count = count($rows);

  $width = intval(sqrt($count));
  $height = $width;

  print "$height x $width\n";
  
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

  $adjacency = array();

  $corners = array();

  $stitch = array();

  $final = array();

  extract_edges();
  extract_uniqueness();
  extract_adjacency();
  extract_corners();

  //print_r($edges, false);

  //print_r($unique, false);

  //print_r($adjacency, false);

  //print_r($corners, false);


  init_stitch();
  stitch_together();
  extract_final();

  print_final();

  print_r($final, false);
  //print_r($stitch, false);
  //print_r($stitch, false);
  
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


  print_final();
  pretty_print();

  //$data = $final[0][0]["data"];
  //$transform = $final[0][0]["transform"];
  //$transform["flip"] = 1;
  //$new_data = apply_data_transform($data, $transform);
  //print_r($transform, false);
  //print_r($data, false);
  //$new_data = apply_data_flip($data);
  //print_r($new_data, false);

  function pretty_print() {
    global $final;
    global $width, $height;
    for ($y = 0; $y < $height; $y++) {
      for ($r = 0; $r < 10; $r++) {
        for ($x = 0; $x < $width; $x++) {
	  $ss = $final[$y][$x]["datap"][$r];
	  if ($x != $width - 1) {
	    $ss = substr($ss,0,strlen($ss)-1);
	  }
	  if ($r == 9 && $y != ($height - 1)) {
	    break;
	  }
	  print $ss;
	  //print " ";
        }
	if ($r == 9 && $y != ($height - 1)) {
	  break;
	}
	print "\n";
      }
      //print "\n";
    }
  }

  function print_final() {
    global $final;
    global $width, $height;
    for ($y = 0; $y < $height; $y++) {
      for ($r = 0; $r < 10; $r++) {
        for ($x = 0; $x < $height; $x++) {
	  print $final[$y][$x]["datap"][$r];
	  print " ";
        }
	print "\n";
      }
      print "\n";
    }
  }

  function extract_final() {
    global $pieces, $stitch, $final;
    global $adjacency;
    global $width, $height;
    for($y=0;$y<$height;$y++) {
      for($x=0;$x<$width;$x++) {
        $key = $stitch[$y][$x]["key"];
        $transform = $stitch[$y][$x]["transform"];
        $adj = $adjacency[$key];   
	$adjp = apply_transform($adj, $transform);
        $final[$y][$x]["key"] = $key;
        $final[$y][$x]["transform"] = $transform;   
        $final[$y][$x]["adj"] = $adj;   
        $final[$y][$x]["adjp"] = $adjp;   
        $final[$y][$x]["data"] = $pieces[$key];
        $final[$y][$x]["datap"] = apply_data_transform($pieces[$key],
	  $transform);
      }
    }
  }

  function apply_data_transform($data, $transform) {
    if($transform["flip"] == 1) {
      $data = apply_data_flip($data);
    //print_r($data, false);
    }
    for($i=0;$i<$transform["dir"];$i++) {
      $data = apply_data_90_rotation($data);
    }
    return $data;
  }

  function apply_data_flip($data) {
    global $width, $height;
    $new_data = array();
    for($y=0;$y < 10;$y++) {
      $new_data[$y] = strrev($data[$y]);
    }
    return $new_data;
  }

  function apply_data_90_rotation($data) {
    $new_data = array();
    for($x=9;$x>=0;$x--) {
      $ss = "";
      for($y=0;$y<10;$y++) {
        $ss .= $data[$y][$x];
      }
      $new_data[] = $ss;
    }
    return $new_data;
  }

  function stitch_together() {
    global $corners, $adjacency;
    global $height, $width;
    global $stitch;
    // top left
    $corner = $corners[0];
    $transform = determine_corner_transform($corner);
    place_piece($corner, 0, 0, $transform);
    for($y = 0;$y < $height; $y++) {
    //for($y = 0;$y < 1; $y++) {
      for($x = 0; $x < $width; $x++) {
        if ($x == 0 && $y == 0) {
	  continue;
	}
	//print "XXXXXXXXXXX\n";
	//print "PLACING $x $y\n";
	//print "XXXXXXXXXXX\n";
	//print_r($stitch, false);
	//print "XXXXXXXXXXX\n";
	//print "XXXXXXXXXXX\n";
        fill_stitch($x, $y);
      }
    }
  }

  function fill_stitch($x, $y) {
    global $stitch, $adjacency;
    // fill rightwards
    if ($x == 0 && $y == 0) {
      die("ERROR in fill_stitch\n");
    }
    if ($x > 0) {
      $px = $x - 1;
      $py = $y;
      $pstitch = $stitch[$py][$px];
      $padj = $adjacency[$pstitch["key"]];
      $padj_correct = apply_transform($padj, $pstitch["transform"]);


      $new_key = $padj_correct[3]["key"];
      $left_key = $pstitch["key"];
      $left_flip = $padj_correct[3]["flip"];
      $new_transform = determine_piece_transform($new_key,
        $left_key, $left_flip);

      place_piece($new_key ,$x, $y, $new_transform);
 
    }
    // fill downwards
    else {
      $px = $x;
      $py = $y - 1;
      $pstitch = $stitch[$py][$px];
      $padj = $adjacency[$pstitch["key"]];
      $padj_correct = apply_transform($padj, $pstitch["transform"]);


      $new_key = $padj_correct[2]["key"];
      $up_key = $pstitch["key"];
      $up_flip = $padj_correct[2]["flip"];
      $new_transform = determine_piece_transform_down($new_key,
        $up_key, $up_flip);

      place_piece($new_key ,$x, $y, $new_transform);



    ////
    }
  }

  function determine_piece_transform
    ($piece_key, $left_key, $left_flip) {
    global $adjacency;
    $new_transform = array();
    $adj = $adjacency[$piece_key];
    $new_transform["flip"] = $left_flip;
    if ($left_flip == 1) {
      $tt["flip"] = 1;
      $tt["dir"] = 0;
      $adj = apply_transform($adj, $tt);
    }
    $new_dir = 0;
    for($i=0;$i<27;$i++) {
      if ($i > 7) {
        die("ERROR in determine_piece_transform\n");
      }
      if($adj[1]["key"] == $left_key) {
        break;
      }
      $adj = rotate_adj_90($adj);
      $new_dir++;
    }
    $new_transform["dir"] = $new_dir;
    return $new_transform;
  }

  function determine_piece_transform_down
    ($piece_key, $up_key, $up_flip) {
    global $adjacency;
    $new_transform = array();
    $adj = $adjacency[$piece_key];
    $new_transform["flip"] = $up_flip;
    if ($up_flip == 1) {
      $tt["flip"] = 1;
      $tt["dir"] = 0;
      $adj = apply_transform($adj, $tt);
    }
    $new_dir = 0;
    for(;;) {
      if($adj[0]["key"] == $up_key) {
        break;
      }
      $adj = rotate_adj_90($adj);
      $new_dir++;
    }
    $new_transform["dir"] = $new_dir;
    return $new_transform;
  }

  function apply_transform($adj, $transform) {
    $dir = $transform["dir"];
    if ($transform["flip"] == 1) {
      $tt = $adj[1];
      $adj[1] = $adj[3];
      $adj[3] = $tt;
      for ($i=0; $i<4; $i++) {
        if ($adj[$i]["flip"] == 1) {
	  $adj[$i]["flip"] = 0;
	}
        else {
	  $adj[$i]["flip"] = 1;
	}
      }
    }
    for($i = 0; $i < $dir; $i++) {
      $adj = rotate_adj_90($adj);
    }
    return $adj;
  }

  function rotate_adj_90($adj) {
    $radj = array();
    $radj[0] = $adj[3];
    $radj[1] = $adj[0];
    $radj[2] = $adj[1];
    $radj[3] = $adj[2];
    return $radj;
  }

  function init_stitch() {
    global $stitch;
    global $width, $height;
    for($y = 0;$y < $height; $y++) {
      for($x = 0; $x < $width; $x++) {
        $stitch[$y][$x]["key"] = 0;
        $stitch[$y][$x]["transform"] = 0;
      }
    }
  }

  function place_piece($key, $x, $y, $transform) {
    global $stitch;
    $stitch[$y][$x]["key"] = $key;
    $stitch[$y][$x]["transform"] = $transform;
  }

  // top left
  function determine_corner_transform($key) {
    global $adjacency;
    $transform = array();
    $transform["flip"] = 0;
    $adj = $adjacency[$key];
    if ($adj[0]["key"] == 0 && $adj[1]["key"] == 0) {
      $transform["dir"] = 0;
    }
    else if ($adj[3]["key"] == 0 && $adj[0]["key"] == 0) {
      $transform["dir"] = 1;
    }
    else if ($adj[2]["key"] == 0 && $adj[3]["key"] == 0) {
      $transform["dir"] = 2;
    }
    else if ($adj[1]["key"] == 0 && $adj[2]["key"] == 0) {
      $transform["dir"] = 3;
    }
    return $transform;
  }


  
  function extract_corners() {
    global $corners, $unique;
    foreach($unique as $key => $val) {
      if ($val > 2) {
        die("ERROR too many unique edges on $key");
      }
      else if($val == 2) {
        //print("key $key\n");
	$corners[] = $key;
      }
    }
  }

  function extract_adjacency() {
    global $pieces, $edges, $adjacency;
    foreach($edges as $key => $edge) {
      $adj_edg = array();
      for($i=0;$i<4;$i++) {
        $n = find_match($key, $i);
	$check = count_matches($key, $i);
	if ($check > 1) {
	  print "ERROR $key $i has multiple matches\n";
          $ss = $edges[$key][$index];
	  print "[$ss]";
	  die();
	}
	$adj_edg[$i] = $n;
      }
      $adjacency[$key] = $adj_edg;
    }
  }
  
  function find_match($key, $index) {
    global $edges;
    $ss = $edges[$key][$index];
    $ret = array();
    foreach($edges as $key2 => $edge2) {
      if ($key == $key2) {
        continue;
      }
      for($i=0;$i<4;$i++) {
        $ss2 = $edge2[$i];
	$ss2r = strrev($ss2);
	if ($ss2r == $ss) {
	  $ret["key"] = $key2;
	  $ret["flip"] = 0;
	  return $ret;
	}
	else if ($ss2 == $ss) {
	  $ret["key"] = $key2;
	  $ret["flip"] = 1;
	  return $ret;
	}
      }
    }
    $ret["key"] = 0;
    $ret["flip"] = 0;
    return $ret;
  }
  
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
