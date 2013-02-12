<?php

$File = "counter.txt"; 
$handle = fopen($File, 'r+') ; 
$data = fread($handle, 512) ; 
$count = $data + 1;
fseek($handle, 0) ; 
fwrite($handle, $count) ; 
fclose($handle) ; 

// Headers for an download:
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="Feeditout-SoftwareTesting.zip"'); 
header('Content-Transfer-Encoding: binary');
// load the file to send:
readfile('Feeditout-SoftwareTesting.zip');



