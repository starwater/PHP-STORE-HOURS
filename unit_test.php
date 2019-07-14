<?php

include 'getAvailableTimeSlots.php';

//testing variables
$open_hours = '{"MON":"09:00-17:00,20:00-23:00","TUE":"CLOSED","WED":"09:00-17:00,19:00-3:00","THU":"09:00-17:00","FRI":"09:00-17:00,22:00-03:00","SAT":"09:00-17:00,20:00-23:00","SUN":"ALL"}';
$open_hours = json_decode($open_hours);
//

//$open_hours = json_decode($open_hours);
$time_zone = '8:40am';
print('isOpenNow for:'."$time_zone".' is '.isOpenNow($open_hours, $time_zone).PHP_EOL);
//case 1: before open time
//case 2: within open time
$time_zone = '3:40pm';
print('isOpenNow for:'."$time_zone".' is '.isOpenNow($open_hours, $time_zone).PHP_EOL);
//case 3: in between open time
$time_zone = '18:00';
print('isOpenNow for:'."$time_zone".' is '.isOpenNow($open_hours, $time_zone).PHP_EOL);
//case 3: after open time
$time_zone = '23:20';
print('isOpenNow for:'."$time_zone".' is '.isOpenNow($open_hours, $time_zone).PHP_EOL);


//
// testing is Open Now
//print_r("open_hours is CLOSED");
$open_hours2 = '{"MON":"09:00-17:00,20:00-23:00","TUE":"CLOSED","WED":"09:00-17:00,19:00-3:00","THU":"09:00-17:00","FRI":"09:00-17:00,22:00-03:00","SAT":"09:00-17:00,20:00-23:00","SUN":"ALL"}';
$open_hours2 = json_decode($open_hours2);
$time_zone = '8:40am';
print('isOpenNow for:'."$time_zone".' is '.isOpenNow($open_hours2, $time_zone).PHP_EOL);
//case 1: before open time
//case 2: within open time
$time_zone = '3:40pm';
print('isOpenNow for:'."$time_zone".' is '.isOpenNow($open_hours2, $time_zone).PHP_EOL);
//case 3: in between open time
$time_zone = '18:00';
print('isOpenNow for:'."$time_zone".' is '.isOpenNow($open_hours2, $time_zone).PHP_EOL);
//case 3: after open time
$time_zone = '23:20';
print('isOpenNow for:'."$time_zone".' is '.isOpenNow($open_hours2, $time_zone).PHP_EOL);
//
//
$open_hours2 = '{"MON":"ALL","TUE":"CLOSED","WED":"09:00-17:00,19:00-3:00","THU":"09:00-17:00","FRI":"09:00-17:00,22:00-03:00","SAT":"CLOSED","SUN":"ALL"}';
$open_hours = json_decode($open_hours2);
//


//INITIAL TEST
$open_hours = '{"MON":"CLOSED","TUE":"09:00-17:00","WED":"09:00-17:00,19:00-3:00","THU":"09:00-17:00","FRI":"09:00-17:00,22:00-03:00","SAT":"9:00-13:00","SUN":"20:00-2:30"}';
$time_zone = '11:05pm';
$range = 30;
$interval = 15;
$days = 5;
$preparation_minutes = 20;

getAvailableTimeSlots($open_hours, $time_zone, $range=30, $interval=30, $days=3, $preparation_minutes=10);

//ASAP TEST
$open_hours = '{"MON":"CLOSED","TUE":"09:00-17:00","WED":"09:00-17:00,19:00-3:00","THU":"09:00-17:00","FRI":"09:00-17:00,22:00-03:00","SAT":"9:00-13:00","SUN":"20:00-2:30"}';
$time_zone = '7:05pm';
$range = 30;
$interval = 15;
$days = 5;
$preparation_minutes = 20;

getAvailableTimeSlots($open_hours, $time_zone, $range=30, $interval=30, $days=3, $preparation_minutes=10);

//ALL DAY
$open_hours = '{"MON":"CLOSED","TUE":"09:00-17:00","WED":"09:00-17:00,19:00-3:00","THU":"09:00-17:00","FRI":"09:00-17:00,22:00-03:00","SAT":"9:00-13:00","SUN":"ALL"}';
$time_zone = '7:05pm';
$range = 30;
$interval = 15;
$days = 1;
$preparation_minutes = 10;

getAvailableTimeSlots($open_hours, $time_zone, $range=30, $interval=15, $days=1, $preparation_minutes=10);


//ALL DAY
$open_hours = '{"MON":"CLOSED","TUE":"09:00-17:00","WED":"09:00-17:00,19:00-3:00","THU":"09:00-17:00","FRI":"09:00-17:00,22:00-03:00","SAT":"9:00-13:00","SUN":"ALL"}';
$time_zone = '7:05pm';
$range = 30;
$interval = 15;
$days = 1;
$preparation_minutes = 10;

getAvailableTimeSlots($open_hours, $time_zone, $range=30, $interval=15, $days=4, $preparation_minutes=10);
