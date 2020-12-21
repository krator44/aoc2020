<?php
  $done = false;
  $ff = fopen("input", "r");
  for(;;) {
    $pass = array();
    $pass_s = "";
    for(;;) {
      $ss = fgets($ff);
      if (feof($ff)) {
        $done = true;
        break;
      }
      if ($ss == "") {
        break;
      }
      $pass_s .= $ss;
      //print "xx $ss xx\n";
    }
    print("xx $pass_s XX\n");
    if ($done) {
      break;
    }
  }

?>
