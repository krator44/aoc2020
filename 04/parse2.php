<?php
  $valid_count = 0;
  $whole_file = file_get_contents("input");

  $passports = explode("\n\n", $whole_file);

  foreach($passports as $key => $val) {
    $passport = str_replace("\n", " ", $val);
    if (strlen($passport) == 0) { 
      break;
    }
    $fields = explode(" ", $passport);
    $assort = null;
    foreach($fields as $key2 => $val2) {
      $final = explode(":", $val2);
      if (count($final) < 2) {
        break;
      }
      $assort[] = $final[0];
      print "!$final[0] = $final[1]!\n";
    }
    $valid = validate($assort);
    if ($valid) {
      print "VALID\n";
      $valid_count++;
    }
    else {
      print "INVALID\n";
    }
    print "\n";
  }
  print "total $valid_count passports valid.\n";

function validate($stuff) {
  if (!in_array("byr", $stuff)) {
    return false;
  }
  if (!in_array("iyr", $stuff)) {
    return false;
  }
  if (!in_array("eyr", $stuff)) {
    return false;
  }
  if (!in_array("hgt", $stuff)) {
    return false;
  }
  if (!in_array("hcl", $stuff)) {
    return false;
  }
  if (!in_array("ecl", $stuff)) {
    return false;
  }
  if (!in_array("pid", $stuff)) {
    return false;
  }
  return true;
}

?>
