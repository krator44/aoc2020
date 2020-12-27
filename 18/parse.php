<?php

  $valid_count = 0;
  $whole_file = file_get_contents("input");
  $expressions = explode("\n", trim($whole_file));

  $total = 0;
  foreach($expressions as $expr) {
    $sx = reduce_fully($expr);
    printn($expr);
    printn($sx);
    $total += $sx;
  }

  printn("total $total");

  //$answer = reduce($expr);

  function reduce_fully($expr) {
    printn("reduce");
    for(;;) {
      printn($expr);
      if(is_reduced($expr)) {
        break;
      }
      $expr = reduce_step($expr);
    }
    printn("end reduce");
    return $expr;
  }

  function is_reduced($expr) {
    if(!in_str($expr, "(")
      && !in_str($expr, ")")
      && !in_str($expr, "+")
      && !in_str($expr, "*")) {
      return true;
    }
    else {
      return false;
    }
  }

  function reduce_step($expr) {
    $n = strlen($expr);
    $xx = find_innermost_bracket($expr);
    $min = $xx["min"];
    $max = $xx["max"];
    $ss = substr($expr, $min, $max-$min+1);
    if(is_reduced($ss)) {
      // discard brackets
      $ss4 = substr($expr, 0, $min-1);
      $ss4 .= $ss;
      $ss4 .= substr($expr, $max+2, $n-$max-2);
    }
    else {
      $ss2 = reduce_without_brackets($ss);
      $ss4 = substr($expr, 0, $min);
      $ss4 .= $ss2;
      $ss4 .= substr($expr, $max+1, $n-$max-1);
    }
    //print "$expr\n";
    //print "$ss\n";
    //printn($ss2);
    //printn($ss4);
    //print "..\n";
    return $ss4;
  }

  function trim_brackets($expr) {
    $n = strlen($expr);
    if($expr[0] == "(") {
      if($expr[$n-1] == ")") {
        $expr = substr($expr, 1, -1);
      }
      else {
        printn("input $expr");
        die("ERROR in trim_brackets\n");
      }
    }
    return $expr;
  }

  function reduce_without_brackets($expr) {
    $n = strlen($expr);
    $tt = find_leftmost_expression($expr);
    //printn($expr);
    //printn($tt);
    $min = $tt["min"];
    $max = $tt["max"];
    $ss = substr($expr, $min, $max-$min+1);
    $ss2 = substr($expr, $max+1, $n-$max-1);
    $ss4 = reduce_binary($ss);
    $result = $ss4.$ss2;
    return $result;
  }

  function reduce_binary($expr) {
    //print "$expr\n";
    $op = "";
    if(in_str($expr, "*")) {
      $op = "*";
    }
    else if(in_str($expr, "+")) {
      $op = "+";
    }
    else {
      print "reduce_binary '$expr'\n";
      die("ERROR in reduce_binary\n");
    }
    $rt = explode($op, $expr);
    //print "$expr\n";
    //printn($rt);
    $left = trim($rt[0]);
    $right = trim($rt[1]);
    if ($op == "+") {
      $result = $left + $right;
    }
    else {
      $result = $left * $right;
    }
    return $result;
  }

  function find_leftmost_expression($expr) {
    $n = strlen($expr);
    $min = 0;
    $max = -1;
    $state = 0;
    for($i=0;$i<$n;$i++) {
      if($expr[$i] == "+" || $expr[$i] == "*") {
        if($state != 0) {
          die("ERROR in find_leftmost_expression\n");
        }
        $state = 1;
      }
      else if($expr[$i] == " ") {
        if ($state == 1) {
          $state = 2;
        }
        else if ($state == 2) {
          $max = $i - 1;
          $state = 3;
          break;
        }
      }
    }
    if ($state < 2) {
      die("ERROR in find_leftmost_expression state=0\n");
    } else if ($state == 2) {
      $max = $n-1;
    }
    $result["min"] = $min;
    $result["max"] = $max;
    return $result;
  }

  /*for(;;) {
    $tt = find_next_reduction();
    if($tt = null) {
      break;
    }
    reduce($tt["min"], $tt["max"]);
  }
  printn($expr);
  */


  function find_next_reduction() {
    $tt = find_innermost_bracket();
    $tt2 = find_leftmost_expression($tt);
    return $tt2;
  }

  function find_innermost_bracket($expr) {
    $i = 0;
    $n = strlen($expr);
    if(!in_str($expr, "(") && !in_str($expr, ")")) {
      $result["min"] = 0;
      $result["max"] = $n-1;
      return $result;
    }

    $left_bracket = -1;
    $right_bracket = -1;
    for ($i=0;$i<$n;$i++) {
      if($expr[$i] == "(") {
        $left_bracket = $i;
        continue;
      }
      else if ($expr[$i] == ")") {
        if ($left_bracket == -1) {
          die("ERROR: no matching '(' for ')' at $i\n");
        }
        else {
          $right_bracket = $i;
          $result = array();
          $result["min"] = $left_bracket+1;
          $result["max"] = $right_bracket-1;
          return $result;
        }
      }
    }
    die("ERROR: left_bracket = $left_bracket, "
      . "right_bracket = $right_bracket\n");
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
