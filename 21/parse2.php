<?php

  $valid_count = 0;
  $whole_file = file_get_contents("input");


  $rows = explode("\n", $whole_file);
  $count = count($rows);
  array_splice($rows, $count-1, 1);
  $count = count($rows);

  $allergens = array();
  $ingredients = array();
  $contains = array();

  $candidate = array();
  $safe = array();

  print_r($rows, false);

  $count = 0;
  foreach($rows as $row) {
    $fields = explode("(", $row);
    print_r($fields, false);
    $ingr = $fields[0];
    $ingr = substr($ingr, 0, strlen($ingr) - 1);
    $argn = $fields[1];
    $argn = substr($argn,9,strlen($argn)-10);
    $argn = str_replace(",", "", $argn);
    $ingredients_f = explode(" ", $ingr);
    $allergens_f = explode(" ", $argn);
    print ("ingr = $ingr\n");
    print ("argn = $argn\n");

    foreach($ingredients_f as $tt) {
      if (!in_array($tt, $ingredients)) {
        $ingredients[] = $tt;
      }
    }
    foreach($allergens_f as $tt) {
      if (!in_array($tt, $allergens)) {
        $allergens[] = $tt;
      }
    }
    $contains[$count]["ingr"] = $ingredients_f;
    $contains[$count]["argn"] = $allergens_f;
    $count++;

//    $key = substr($fields[0], -4);
//    $data = explode("\n", $fields[1]);
//    array_splice($data, 0, 1);
    //print "x$key XX\n";
    //print_r($data, false);
//    $pieces[$key] = $data;
  }

  print "INGREDIENTS\n";
  printn($ingredients);
  print "ALLERGENS\n";
  printn($allergens, false);
  print "CONTAINS\n";
  printn($contains);

  extract_candidates();

  print "CANDIDATE\n";
  printn($candidate);
  
  clean_candidates();

  print "CANDIDATE again\n";
  printn($candidate);

  extract_safe();
  printn("SAFE");
  printn($safe);

  $safe_count = 0;
  foreach($contains as $key => $value) {
    $rr = $value["ingr"];
    foreach($rr as $ingr) {
      if (is_safe($ingr)) {
        $safe_count++;
      }
    }
  }

  printn("safe count $safe_count");

  $danger = array();
  foreach($candidate as $key => $value) {
    if (count($value) != 1) {
      printn($value);
      die("ERROR\n");
    }
    $danger[$value[0]] = $key;
  }
  
  printn($danger);
  asort($danger);
  $dangern = array_flip($danger);
  $ss = implode(",", $dangern);

  printn("canonical dangerous ingredient list '$ss'");
  
  
  function is_safe($ingr) {
    global $safe;
    return in_array($ingr, $safe);
  }

  function extract_safe() {
    global $safe, $ingredients;
    $safe = array();
    foreach($ingredients as $ingr) {
      if(!is_candidate($ingr)) {
        $safe[] = $ingr;
      }
    }
  }

  function is_candidate($ingr) {
    global $candidate;
    foreach($candidate as $key => $value) {
      if(in_array($ingr, $value)) {
        return true;
      }
    }
    return false;
  }

  function clean_candidates() {
    global $candidate;
    for(;;) {
      $state_changed = false;
      foreach($candidate as $argn => $ingrs) {
        if (count($ingrs) == 0) {
          die("ERROR\n");
        }
        else if (count($ingrs) == 1) {
          $changes = remove_candidate($ingrs[0], $argn);
          if ($changes) {
            $state_changed = true;
          }
        }
      }
      if (!$state_changed) {
        break;
      }
    }
  }

  function remove_candidate($ingr, $except) {
    global $candidate;
    $changes = false;
    foreach($candidate as $key => $ingrs) {
      if ($key == $except) {
        continue;
      }
      if (in_array($ingr, $ingrs)) {
        $arr = array_diff($ingrs, array($ingr));
        $arr = array_values($arr);
        $candidate[$key] = $arr;
        $changes = true;
      }
    }
    return $changes;
  }

  function extract_candidates() {
    global $contains;
    foreach($contains as $food) {
      $argns = $food["argn"];
      $ingrs = $food["ingr"];
      foreach($argns as $argn) {
        update_candidate($argn, $ingrs);
      }
    }
  }

  function update_candidate($argn, $ingr) {
    global $candidate;
    if (!array_key_exists("$argn", $candidate)) {
      $candidate[$argn] = $ingr;
      return;
    }
    $old_data = $candidate[$argn];
    $new_data = $ingr;
    $intersection = array_intersect($old_data, $new_data);
    $candidate[$argn] = array_values($intersection);
  }

  function printn($array) {
    print_r($array, false);
    print "\n";
  }

?>
