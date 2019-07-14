#PHP for store opening hours:  
--------------------------------
Jul 14: update for "ALL" hours
--------------------------------
The code still need a lot to work on code optimization, feel free to let me know. Tried to use strtotime but the result turns out to be unpredictable due to different time zones.  
Note: When interval at 30, time will only show to 11:30 pm, this is relate to the rounding method used. (round to nearest 15min)  
Use http://jsonviewer.stack.hu/ to have a nice view for JSON object.  
Closed days will not appear on the JSON result.
# Get Open Hours:

### Functions:  

getAvailableTimeSlots($open_hours, $time_zone, $range, $interval, $days, $preparation_minutes=10)

@params:  
$open_hours : JSON object

{"MON":"CLOSED","TUE":"09:00-17:00","WED":"09:00-17:00,19:00-3:00","THU":"09:00-17:00","FRI":"09:00-17:00,22:00-03:00","SAT":"ALL","SUN":"ALL”}

$time_zone :  Implemented in time form. '1:30pm'  

$range : In minutes. Time range for each interval.  '12:30-1:00'  

$interval : The differences between each slot in minutes. '12:30-1:00', '12:45-1:15', '1:00-1:30'  

$days: number of days to be return.  

$preparation_minute: to calculate the next avaiable time slot, round to nearest 15 min. eg. Current time is 8:35 am. Prep time is 10mins, next time slot will be 8:45 mod 15mins + 15mins = 9:00. If it was 8:44 then it will round to 8:45.


isOpenNow($open_hours, $time_zone)  

@return                      "8:00-12:00,15:00-19:00"

code: 11 : store is currently open in first period of hours  
code: 12 : store is currently open in the second period of hours  
code: 13 : store will be open soon to the first period of hours  
code 14 : store will be open soon to the second period of hours  

## helper functions:
time_table($start, $end, $range, $interval)

@params:  
$start : start time in string format  
$end : end time in string format  
$range : range of slot in minutes  
$interval : in minutes  

this fuction uses date and DateTime::diff to compute the time difference and conversion, also added  specail cases for ALL DAY OPEN.  
This function generates the time list withthout the dates.

 
add_time($time, $min)  
 use to round off the time to the nearest 15minute.
 
 table_helper, table_helper11~14  
 use to read the time in string and assign them to the time_table function, also handling special cases.
  
 time_compare($time_range, $current_time)  
 return true or "soon" when there are time slots available for today
 
 



