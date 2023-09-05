# bfcalendar
# BlackForestCalendar

This project started when PHP went from 7.4 to 8.1 and the K5N calendar broke. There is a minor difference between K5N and this calendar.  K5N uses extensive use of CSS, extensive use of Javascript and lots of nested <div's.  This calendar attempts to go the other way. KISS.

How the program works: It starts by assuming you've already brought the calendar up and gets previous vars - ie month, year, etc. If not then "now".

In the background are two Mysql tables:

    CREATE TABLE `cal_entry` (
     `id` int NOT NULL AUTO_INCREMENT,
     `date` int DEFAULT NULL,
     `duration` smallint DEFAULT '0',
     `end_date` int DEFAULT NULL,
     `repeats` char(16) NOT NULL,
     `description` varchar(80) NOT NULL,
     `category` smallint DEFAULT NULL,
     PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1484 DEFAULT CHARSET=latin1;

and

    CREATE TABLE `cal_misc` (
     `id` int NOT NULL,
     `note` text,
     `contact` varchar(100) DEFAULT NULL,
     `location` enum('Club','Club+Pavilion','Pavilion','N40','Parking') DEFAULT 'Club',
     `type` enum('Paid','Discount','Free','Sponsored') DEFAULT NULL,
     `mod_date` int DEFAULT NULL,
     `create_by` varchar(25) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

The program has 2 locations:  Home and a sub-directory for administration controlled by htaccess.

I was brought up being told if your table, in a relational database, was over 10 columns long - go back to the drawing board and start over. (a general rule)  And that Int searches were way faster than Char searches.  To that end cal_entry has only that needed to display the calendar entry.  The table cal_misc contains information for the Admin and any background apps - IE notifications.

So after the initial "where were we" the program uses the system "cal" program to get an image of the month in question - day of week for day 1, days in the month.  There are two types of entries: One time events IE a wedding and Repeating events IE Boy Scouts every Tuesday evening.

So there are 2 Mysql queries - where "once" and where "not once".  The date is "push"ed in to an array by day.  Then each day is sorted by time.  Then, via "<table..." the calendar is created 7x5.

Feel free to modify, change, improve - but please KISS
cal@blkfst.com

   # time php index.php
   real    0m0.058s
   user    0m0.027s
   sys     0m0.023s
