<?php

  ini_set('memory_limit', '640000000');

  $valid_count = 0;
  $whole_file = file_get_contents("input");


  $parts = explode("\n\n", $whole_file);
  //printn($parts);

  $rules = array();
  $messages = array();

  extract_rules($parts[0]);
  extract_messages($parts[1]);

  printn($rules);
  printn($messages);

  $strings = generate_strings();

  //printn($strings);

  $string_count = count($strings);
  printn("string count: $string_count\n");

  //$strings = array_unique($strings);
  //$string_count = count($strings);
  //printn("string count: $string_count\n");

  check_messages();

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
      foreach($head_rule as $expansion) {
        $tt = generate_strings_rec($expansion);
	$head_strings = array_merge($head_strings, $tt);
      }
    }
    
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
