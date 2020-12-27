<?php

  //ini_set('memory_limit', '640000000');

  $valid_count = 0;
  $whole_file = file_get_contents("input");


  $parts = explode("\n\n", $whole_file);
  //printn($parts);

  $rules = array();
  $messages = array();

  extract_rules($parts[0]);
  extract_messages($parts[1]);

  //printn($rules);
  //printn($messages);

  //$strings = generate_strings();

  $tt = generate_strings_rec($rules[42][0]);
  $tt2 = generate_strings_rec($rules[42][1]);
  $strings_42 = array_mergen($tt, $tt2);


  $tt = generate_strings_rec($rules[31][0]);
  $tt2 = generate_strings_rec($rules[31][1]);
  $strings_31 = array_mergen($tt, $tt2);

  printn($strings_42);
  printn($strings_31);
  printn("strings42 count: ".count($strings_42)."\n");
  printn("strings31 count: ".count($strings_31)."\n");


  foreach($strings_31 as $ss) {
    foreach($strings_42 as $ss2) {
      if($ss == $ss2) {
        die("ERROR\n");
      }
    }
  }

  check_messages_pt2();

  //$strings = generate_strings_rec($rules[8][0]);
  //$strings = generate_strings_rec($rules[11][0]);
  //$strings = generate_strings_rec($rules[42][0]);
  //printn($strings);
  //$strings = generate_strings_rec($rules[31][0]);
  //printn($strings);

  //$string_count = count($strings);
  //printn("string count: $string_count\n");

  //$strings = array_unique($strings);
  //$string_count = count($strings);
  //printn("string count: $string_count\n");

  //check_messages();

  function check_messages_pt2() {
    global $messages;
    $count = 0;
    foreach($messages as $message) {
      if (check_message_pt2($message)) {
        $count++;
      }
    }
    printn("$count messages match");
  }

  function check_message_pt2($message) {
    global $messages, $strings_42, $strings_31;
    $count_42 = 0;
    $count_31 = 0;
    $n = strlen($message);
    if($n % 8 != 0) {
      return false;
    }
    $nn = $n / 8;
    for($i=0;$i<$nn;$i++) {
      $ss = substr($message,$i*8,8);
      if(in_array($ss, $strings_42)) {
        if($count_31 != 0) {
	  return false;
	}
	$count_42++;
      }
      else if (in_array($ss, $strings_31)) {
        $count_31++;
      }
      else {
        return false;
      }
    }
    if($count_42 <= $count_31) {
      return false;
    }
    if($count_42 < 2) {
      return false;
    }
    if($count_31 < 1) {
      return false;
    }
    return true;
  }

  function check_messages() {
    global $messages, $strings;
    $count = 0;
    foreach($messages as $message) {
      if (in_array($message, $strings)) {
        $count++;
      }
    }
    printn("$count messages match");
  }

  function extract_rules($rules_s) {
    global $rules;
    $messages = array();
    $rt = explode("\n", $rules_s);
    //printn($rt);
    foreach($rt as $row) {
      $rule = array();
      $rtt = explode(":", $row);
      $index = $rtt[0];
      $expansions_s = $rtt[1];
      $expansions_r = explode("|", $expansions_s);
      //$rule["variants"] = array();
      //printn($expansions_r);
      foreach($expansions_r as $expansion_s) {
        $ss = trim($expansion_s);
	$expansion_r = explode(" ", $ss);
	foreach($expansion_r as $index2 => $symbol2) {
	  $expansion_r[$index2] = trim($symbol2, "\"");
	}
	$rule[] = $expansion_r;
      }
      $rules[$index] = $rule;
    }
    ksort($rules);
  }

  function extract_messages($messages_s) {
    global $messages;
    $messages = array();
    $rt = explode("\n", $messages_s);
    foreach($rt as $message) {
      if(trim($message) != "") {
        $messages[] = $message;
      }
    }
  }

  function generate_strings() {
    global $rules;
    return generate_strings_rec($rules[0][0]);
  }

  function generate_strings_rec($expr) {
    global $rules;
    //print "generate_strings_rec ";
    //printn($expr);
    $strings = array();
    if(count($expr) == 0) {
      $strings[] = "";
      return $strings;
    }
    // expand head
    $head = $expr[0];
    $head_strings = array();
    if(is_terminal($head)) {
      $head_strings[]=$head;
    }
    else {
      $head_rule = $rules[$head];
      //print "expr ";
      //printn ($expr);
      //print "head_rule";
     // printn ($head_rule);
      foreach($head_rule as $index => $expansion) {
        $tt = generate_strings_rec($expansion);
        //print "head_strings count tt ".count($tt);
	//print "head $head generated ".count($tt)." strings on exp $index\n";
	$head_strings = array_mergen($head_strings, $tt);
      }
    }
    //print "end expr merge ". count($head_strings);
    //printn ($expr);
    // expand tail
    $tail = array_slice($expr, 1);
    $tail_strings = generate_strings_rec($tail);

    //printn($head_strings);
    //printn($tail_strings);

    foreach($head_strings as $head_string) {
      foreach($tail_strings as $tail_string) {
        $ss2 = $head_string . $tail_string;
	$strings[] = $ss2;
      }
    }
    return $strings;
  }

  function array_mergen($r1, $r2) {
    $rr = array();
    foreach($r1 as $rt) {
      $rr[] = $rt;
    }
    foreach($r2 as $rt) {
      $rr[] = $rt;
    }
    return $rr;
  }

  function is_terminal($symbol) {
    if (is_numeric($symbol)) {
      return false;
    }
    return true;
  }

  function in_str($str, $ch) {
    if(strpos($str, $ch) !== false) {
      return true;
    }
    else {
      return false;
    }
  }

  function printn($array) {
    print_r($array, false);
    print "\n";
  }

?>
