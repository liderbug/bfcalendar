<?php
$hostname = "[hostname]";
$username = "[user]";
$password = "[pw]";
$database = "[dbname]";

$newdb = mysqli_connect($hostname, $username, $password, $database);
if (mysqli_connect_errno()) {
   die("Connect failed: %s\n" + mysqli_connect_error());
   exit();
}
