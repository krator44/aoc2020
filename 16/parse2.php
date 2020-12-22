<?php

  $valid_count = 0;
  $whole_file = file_get_contents("input");

  $parts = explode("\n\n", $whole_file);

  //printn($parts);

  $rules = array();
  $ticket = array();
  $nearby = array();
  $candidate = array();


  extract_rules();
  extract_ticket();
  extract_nearby();

  $num_rules = count($rules);



  printn($rules);


  $errors = check_rules();
  $count = $errors["count"];
  $total = $errors["total"];

  printn("$count fields don't match any of the rules");
  printn("ticket scanning error rate $total");

  filter_tickets();

  //printn($nearby);
  extract_candidates();
  //printn("CANDIDATE");
  //printn($candidate);
  
  clean_candidates();

  //printn("CLEAN CANDIDATE");
  //printn($candidate);

  extract_final();
  //printn($final);


  $count = 0;
  $total = 1;
  for($i=0; $i<$num_rules; $i++) {
    $ss = $rules[$final[$i]]["name"];
    //printn($rules);
    if (substr($ss,0,strlen("departure")) == "departure") {
      print "$ss ${ticket[$i]}\n";
      $total = $total * $ticket[$i];
      $count++;
    }
  }
  printn("$count fields, total $total");
  //extract_fields();

  function extract_final() {
    global $final, $candidate;
    $final = array();
    foreach($candidate as $key => $value) {
      if(count($value) != 1) {
        die("ERROR some fields unidentified\n");
      }
      $final[$key] = $value[0];
    }
  }

  function clean_candidates() {
    global $candidate;
    for (;;) {
      $state_changed = false;
      foreach($candidate as $column => $rules) {
        if (count($rules) == 0) {
          die("ERROR in clean_candidates\n");
        }
        else if (count($rules) == 1) {
          $xx = remove_candidate($rules[0], $column);
	  if ($xx) {
	    $state_changed = true;
	  }
        }
      }
      if(!$state_changed) {
        break;
      }
    }
  }

  function remove_candidate($rule_index, $except) {
    global $candidate;
    $changes = false;
    foreach($candidate as $col => $tt) {
      if($col == $except) {
        continue;
      }
      if (in_array($rule_index, $candidate[$col])) {
        $rr = array_diff($candidate[$col], array($rule_index));
	$rr = array_values($rr);
        $candidate[$col] = $rr;
	$changes = true;
      }
    }
    return $changes;
  }

  function extract_candidates() {
    global $candidate;
    global $rules, $num_rules;
    $candidate = array();
    
    for($i=0; $i<$num_rules; $i++) {
      $candidate[$i] = array();
      foreach($rules as $key => $rule) {
        if(is_valid_for_column($i, $key)) {
	  $candidate[$i][] = $key;
	}
      }
    }
  }

  /*
  function extract_fields() {
    global $rules, $nearby, $fields;
    $num_rules = count($rules);
    foreach($rules as $key => $rule) {
      for($i=0;$i<$num_rules;$i++) {
        $matches = true;
        foreach($nearby as $ticket) {
	  printn ("is valid ${ticket[$i]} for rule $key");
          if (!is_valid_for_rule($ticket[$i], $key)) {
	    printn("${ticket[$i]} not valid for rule $key");
	    $matches = false;
	    break;
	  }
        }
	if ($matches) {
          printn ("FOUND");
	  printn ("column $i matches rule $key");
	  if(isset($rule["index"])) {
	    $index2 = $rule["index"];
	    printn("columns $i and $index2 both match rule $key");
	    die("ERROR\n");
	  }
	  $rule["index"] = $i;
	}
      }
    }
  }
  */

  function filter_tickets() {
    global $rules, $nearby;
    $error_count = 0;
    $total = 0;
    foreach($nearby as $index => $nearby_ticket) {
      foreach($nearby_ticket as $field) {
	if(!is_valid($field)) {
	  unset($nearby[$index]);
	}
      }
    }
    $nearby = array_values($nearby);
  }

  function check_rules() {
    global $rules, $nearby;
    $error_count = 0;
    $total = 0;
    foreach($nearby as $nearby_ticket) {
      foreach($nearby_ticket as $field) {
	//print("$field");
	if(!is_valid($field)) {
	  //print(" INVALID");
	  $error_count++;
	  $total += $field;
	}
	//print("\n");
      }
    }
    $result = array();
    $result["count"] = $error_count;
    $result["total"] = $total;
    return $result;
  }

  function is_valid($number) {
    global $rules;
    foreach($rules as $key => $rule) {
      if(is_valid_for_rule($number, $key)) {
        return true;
      }
    }
    return false;
  }

  function is_valid_for_column($column, $rule_index) {
    global $nearby;
    foreach($nearby as $ticket) {
      if(!is_valid_for_rule($ticket[$column], $rule_index)) {
        return false;
      }
    }
    return true;
  }

  function is_valid_for_rule($field, $index) {
    global $rules;
    $rule = $rules[$index];
    for($i = 0; $i < 2; $i++) {
      $min = $rule["ranges"][$i]["min"];
      $max = $rule["ranges"][$i]["max"];
      //printn("$min - $max: $number");
      if ($min <= $field && $max >= $field) {
        return true;
      }
    }
    return false;
  }

  function extract_rules() {
    global $parts, $rules;
    $rules = array();
    $rows = explode("\n", $parts[0]);
    //printn($rows);
    foreach($rows as $row) {
      $rule = array();
      $tt = explode(":", $row);
      $rule["name"] = $tt[0];
      $tt2 = trim($tt[1]);
      $tt4 = explode(" or ", $tt2);
      $ranges = array();
      foreach($tt4 as $key2 => $row2) {
        $tt7 = explode("-", $row2);
        $ranges[$key2]["min"] = $tt7[0];
        $ranges[$key2]["max"] = $tt7[1];
      }
      $rule["ranges"] = $ranges;
      $rules[] = $rule;
      printn($rule);
    }
    //printn($rules);
  }


  function extract_ticket() {
    global $parts, $ticket;
    $ticket = array();
    $tt = explode(":\n", $parts[1]);
    $tt2 = explode(",", $tt[1]);
    //printn($tt2);
    $ticket = $tt2;
  }

  function extract_nearby() {
    global $parts, $nearby;
    $nearby = array();
    $tt = explode("\n", $parts[2]);
    foreach($tt as $index => $row) {
      if($index == 0) {
        continue;
      }
      if(trim($row) == "") {
        continue;
      }
      $tt2 = explode(",", $row);
      $nearby[] = $tt2;
    }
    printn($nearby);
  }


  function printn($array) {
    print_r($array, false);
    print "\n";
  }

?>
