<?php

include('CsvParser.php');

if($argc != 2 || $argc > 2) {
  echo "Script accepts one argument after being called\nExample: php *script* *argument*";
  exit();
}

if(!file_exists($argv[1]) || pathinfo($argv[1])['extension'] !== 'csv') {
  print_r("Input should be a csv file!");
  exit();
}

$parser = new CsvParser($argv[1]);
$parser->run();




