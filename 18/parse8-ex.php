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
      && !in_str($expr, "*")
      && !in_str($expr, "-")) {
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
    //printn($expr);
    print_highlight($min, $max, "+");
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
    //$tt = find_leftmost_expression($expr);
    $tt = find_binary($expr);
    printn($expr);
    //printn($tt);
    $min = $tt["min"];
    $max = $tt["max"];
    print_highlight($min, $max, "#");
    $ss = substr($expr, $min, $max-$min+1);
    $ss2p = "";
    if ($min != 0) {
      $ss2p = substr($expr, 0, $min);
    }
    //print ">$ss2p<\n";
    $ss2 = substr($expr, $max+1, $n-$max-1);
    $ss4 = reduce_binary($ss);
    $result = $ss2p.$ss4.$ss2;
    return $result;
  }

  function print_highlight($min, $max, $ch="+") {
    for($i=0;$i<$min;$i++) {
      print(" ");
    }
    for($i=$min;$i<=$max;$i++) {
      print($ch);
    }
    print "\n";
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
    else if(in_str($expr, "-")) {
      $op = "-";
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
    else if ($op == "-") {
      $result = $left - $right;
    }
    else {
      $result = $left * $right;
    }
    return $result;
  }

  function find_binary($expr) {
    // if only + or only *
    if(!(in_str($expr, "+") || in_str($expr, "-")) || !in_str($expr, "*")) {
      return find_leftmost_expression($expr);
    }
    //print "find binary\n";
    //print "$expr\n";
    // find leftmost +
    /*
    $x = 4096;
    $x2 = 4096;
    if (in_str($expr, "-")) {
      $x = strpos($expr, "-");
    }
    if (in_str($expr, "+")) {
      $x2 = strpos($expr, "+");
    }
    if($x2 < $x) {
      $x = $x2;
    }
    printn("x = $x\n");
    */
    $x = strpos($expr, "*");
    
    $n = strlen($expr);
    $min = -1;
    $max = -1;
    $strike = 0;
    for($i=$x;$i>=0;$i--) {
      //printn("ch ${expr[$i]}");
      if ($strike==0) {
        if(!is_numeric($expr[$i])) {
	  $strike++;
	}
      } else if ($strike == 1) {
        if(is_numeric($expr[$i])) {
	  $strike++;
	}
      } else if ($strike == 2) {
        if (!is_numeric($expr[$i])) {
          $min=$i+1;
          break;
	}
      }
    }
    $strike = 0;
    for($i=$x;$i<$n;$i++) {
      //printn("ch2 ${expr[$i]}");
      if ($strike==0) {
        if(!is_numeric($expr[$i])) {
	  $strike++;
	}
      } else if ($strike == 1) {
        if(is_numeric($expr[$i])) {
	  $strike++;
	}
      } else if ($strike == 2) {
        if (!is_numeric($expr[$i])) {
          $max=$i-1;
          break;
	}
      }
    }
    if($min == -1) {
      $min = 0;
    }
    if($max == -1) {
      $max = $n-1;
    }
    $result["min"] = $min;
    $result["max"] = $max;
    //printn("find_binary");
    //printn($result);
    return $result;
  }

  function find_leftmost_expression($expr) {
    $n = strlen($expr);
    $min = 0;
    $max = -1;
    $state = 0;
    for($i=0;$i<$n;$i++) {
      //printn("ch3 ${expr[$i]} state $state");
      if($expr[$i] == "+" || $expr[$i] == "-" || $expr[$i] == "*") {
        if($state != 0) {
	  die("ERROR in find_leftmost_expression\n");
	}
        $state = 1;
      }
      else if($expr[$i] == " " || !is_numeric($expr[$i])) {
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
    if ($state < 1) {
      die("ERROR in find_leftmost_expression state=$state\n");
    } 
    if ($max == -1) {
      $max = $n-1;
    }
    $result["min"] = $min;
    $result["max"] = $max;
    //printn($result);
    return $result;
  }

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
