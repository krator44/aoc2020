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
    $assort2 = null;
    foreach($fields as $key2 => $val2) {
      $final = explode(":", $val2);
      if (count($final) < 2) {
        break;
      }
      $assort[] = $final[0];
      $assort2[$final[0]] = $final[1];
      print "!$final[0] = $final[1]!\n";
    }
    $valid = validate($assort, $assort2);
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

function validate($stuff, $fields) {
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
  print "birth year: ${fields["byr"]}\n";
  if (strlen($fields["byr"]) != 4) {
    return false;
  }
  if (!is_numeric($fields["byr"])) {
    return false;
  }
  if ($fields["byr"] > 2002 || $fields["byr"] < 1920) { 
    return false;
  }
  if (strlen($fields["iyr"]) != 4) {
    return false;
  }
  if (!is_numeric($fields["iyr"])) {
    return false;
  }
  if ($fields["iyr"] < 2010 || $fields["iyr"] > 2020) { 
    return false;
  }
  if (strlen($fields["eyr"]) != 4) {
    return false;
  }
  if (!is_numeric($fields["eyr"])) {
    return false;
  }
  if ($fields["eyr"] < 2020 || $fields["eyr"] > 2030) { 
    return false;
  }
  $units = substr($fields["hgt"], -2);
  print "units $units\n";
  if ($units == "cm" || $units == "in") {
    $height = substr($fields["hgt"], 0, strlen($fields["hgt"])-2);
    print "height $height\n";
    if (!is_numeric($height)) {
      return false;
    }
    if ($units == "cm") {
      if ($height < 150 || $height > 193) {
        return false;
      }
    } else if ($units == "in") {
      if ($height < 59 || $height > 76) {
        return false;
      }
    }
    else {
      die("ERROR");
    }
  }
  else {
    return false;
  }
  if (substr($fields["hcl"], 0, 1) != "#") {
    return false;
  }
  $hcl = substr($fields["hcl"], 1);
  print "hair color $hcl \n";
  if (strlen($hcl) != 6) {
    return false;
  }
  if (!ctype_xdigit($hcl)) {
    return false;
  }
  $ecl = $fields["ecl"];
  if (!in_array($ecl, array("amb", "blu", "brn",
    "gry", "grn", "hzl", "oth"))) {
    return false;
  }
  $pid = $fields["pid"];
  if (strlen($pid) != 9) {
    return false;
  }
  if (!is_numeric($pid)) {
    return false;
  }
  return true;
}

?>
