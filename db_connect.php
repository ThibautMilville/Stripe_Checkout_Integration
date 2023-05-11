<?php
  // CONNECT TO DATABASE
  $db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
  // DISPLAY ERROR IF FAILED TO CONNECT
  if($db->connect_errno){
    exit("Failed to connect to MySQL: " . $db->connect_error);
  }
?>