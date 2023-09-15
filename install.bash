#!/bin/bash

# this script creates a hidden sub-directory for admin functions
# it then modifies index.php for that hidden directory
# if you want you can, after the fact, rename it.

hiddir=`tr -dc A-Za-z0-9 </dev/urandom | head -c 13 ; echo ''`
#hiddir="yourchoice"
echo $hiddir
mv adminsub .$hiddir
mv '[dot]dbconnect.php' .dbconnect.php
sed -i 's/44foo4fee5/'.$hiddir'/' index.php
sed -i 's/foo4fee5/'$hiddir'/' index.php
sed -i 's/HOSTNAME/'$HOSTNAME'/' index.php

echo -n "Hostname?  " ; read ans
sed -i 's/\[hostname\]/'$ans'/' .dbconnect.php
echo -n "Username?  " ; read ans
sed -i 's/\[username\]/'$ans'/' .dbconnect.php
echo -n "Passwd?  " ; read ans
sed -i 's/\[password\]/'$ans'/' .dbconnect.php
echo -n "DB name?  " ; read ans
sed -i 's/\[database\]/'$ans'/' .dbconnect.php
cp .dbconnect.php .$hiddir/.

