<?php

include_once('functions.php'); # including all our neccessary functions
$handler = new Handler; # new instance of Handler class
$ip_address = $_SERVER['REMOTE_ADDR']; # client ip address
date_default_timezone_set("Europe/Berlin"); # setting timezone
$time = date("d.m.Y H:i:s"); # Time Format

switch($_GET) {
  case isset($_GET['ping']):
    $handler->logRequest($ip_address, $time, '?ping'); # logging every request
    $handler->handlePing($ip_address, $time); # calling the handlePing func with the real ip addr
    break;
  case isset($_GET['get']):
    if(!empty($_GET['get'])) {
      $handler->logRequest($ip_address, $time, '?get');
      $handler->handleGet($_GET['get']); # calling the handleGet func with the query_parameter_value as parameter
    }
    break;
  case isset($_GET['flush']):
    $handler->logRequest($ip_address, $time, '?flush');
    $handler->handleFlush(); # calling the flush func
    break;
}