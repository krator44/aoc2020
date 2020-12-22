<?php

  $valid_count = 0;
  $whole_file = file_get_contents("input");

  $parts = explode("\n\n", $whole_file);

  //printn($parts);

  $rules = array();
  $ticket = array();
  $nearby = array();

  extract_rules();
  extract_ticket();
  extract_nearby();


  $errors = check_rules();
  $count = $errors["count"];
  $total = $errors["total"];

  printn("$count fields don't match any of the rules");
  printn("ticket scanning error rate $total");

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
    foreach($rules as $rule) {
      for($i = 0; $i < 2; $i++) {
        $min = $rule[$i]["min"];
        $max = $rule[$i]["max"];
	//printn("$min - $max: $number");
	if ($min <= $number && $max >= $number) {
	  return true;
	}
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
      $tt = explode(":", $row);
      $tt2 = trim($tt[1]);
      $tt4 = explode(" or ", $tt2);
      $rule = array();
      foreach($tt4 as $key2 => $row2) {
        $tt7 = explode("-", $row2);
        $rule[$key2]["min"] = $tt7[0];
        $rule[$key2]["max"] = $tt7[1];
      }
      $rules[] = $rule;
      //printn($rule);
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
