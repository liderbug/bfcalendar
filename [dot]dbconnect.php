<?php
$hostname = "[hostname]";
$username = "[username]";
$password = "[password]";
$database = "[database]";

$newdb = mysqli_connect($hostname, $username, $password, $database);
if (mysqli_connect_errno()) {
   die("Connect failed: %s\n" + mysqli_connect_error());
   exit();
}
