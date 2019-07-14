<?php
include 'isOpenNow.php';

//$open_hours = '{"MON":"CLOSED","TUE":"09:00-17:00","WED":"09:00-17:00,19:00-3:00","THU":"09:00-17:00","FRI":"09:00-17:00,22:00-03:00","SAT":"9:00-13:00","SUN":"20:00-2:30"}';
//$time_zone = '11:05pm';
//$range = 30;
//$interval = 15;
//$days = 5;
//$preparation_minutes = 20;

//getAvailableTimeSlots($open_hours,$time_zone,$range,$interval,$days,$preparation_minutes);

function getAvailableTimeSlots($open_hours, $time_zone, $range, $interval, $days, $preparation_minutes=10)
{
    // today
    // loop to todays opbject
    $time_arr = [];
    $arr = [];
    $todays_date = date('y-m-d');
    print_r('today\'s day: '.$todays_date);
    $todays_day = date('D'); $todays_day = strtoupper($todays_day); //get todays day
    $open_hours = json_decode($open_hours);
    reset($open_hours);
    while (key($open_hours) != $todays_day) { next($open_hours); } //loop to todays day
    $open = isOpenNow($open_hours, $time_zone); // determine the start time, time_zone + preparation time , then round
//    $time = current($open_hours);
    // today
    print("keyis: ".key($open_hours)."   hours: ".current($open_hours).PHP_EOL);
    print('isopen:'.$open.PHP_EOL);
    if($open!=false){
        if($open===11){ //p1
            //within the time
            $t = add_time($time_zone,$preparation_minutes);
            print('t is :'.$t);
            $arr = table_helper_11($open_hours, $range, $interval, key($open_hours), $t);
            array_unshift($arr, 'ASAP');
        }else if ($open==12){
            $t = add_time($time_zone,$preparation_minutes);
            $arr = table_helper_12($open_hours, $range, $interval, key($open_hours), $t);
            array_unshift($arr, 'ASAP');
        } else if ($open==13)
        {
            $arr = table_helper_13($open_hours, $range, $interval, key($open_hours));
        } else if ($open==14){
            $arr = table_helper_14($open_hours, $range, $interval, key($open_hours));
        }
        if(!empty($arr)){
            $arr = array($todays_date=>$arr);
            $time_arr = array_merge($time_arr,$arr);
        }
        $todays_date = date('y-m-d', strtotime($todays_date. "+1 days"));
    }

    if(key($open_hours)=='SUN'){
        reset($open_hours);
    } else{
        next($open_hours);
    }
    for($i=0; $i<$days-1; $i++)
    {
        print('for loop:'.$i.PHP_EOL);
        $arr = table_helper($open_hours, $range, $interval, key($open_hours));
        if(!empty($arr)){
            $arr = array($todays_date=>$arr);
            $time_arr = array_merge($time_arr,$arr);
        }
        $todays_date = date('y-m-d', strtotime($todays_date. "+1 days"));
        next($open_hours);
    }
    $time_arr = json_encode($time_arr);
    print($time_arr);
    return $time_arr;
}


// start, end is string, range, interval in minutes
function time_table($start, $end, $range, $interval)
{
    $time_arr=[];

    $time1 = new DateTime($start);
    $time2 = new DateTime($end);


    if($time2>$time1){
        $diff = $time1->diff($time2);
        $diff_str = $diff->format('%H:%I');
        $h = $diff->h;
        $i = $diff->i;
    }else{
        $time3 = new DateTime('24:00');
        $time4 = new DateTime('0:00');
        $diff = $time1->diff($time3);
        $diff2 = $time2->diff($time4);
        $h = $diff->h+$diff2->h;
        $i = $diff->i+$diff2->i;
        $diff_str = "$h:$i";
    }

    if($start==='0:00' and $end==='24:00'){
        $h = 24;
        $i = 0;
    }

    $mins = $h*60 + ($i);
    $iterate = ($mins/$interval)-1;

    $tmp1 = strtotime($start);

    for($i=0; $i<$iterate; $i++)
    {
        $tmp2 = $tmp1 + ($range*60);

        $t1 = date('h:ia', $tmp1);
        $t2 = date('h:ia', $tmp2);
        array_push($time_arr,"$t1-$t2");

        $tmp1 = $tmp1 + ($interval*60);
    }
//    print('diff:'.$diff_str.PHP_EOL);
//    print('mins:'.$mins.PHP_EOL);
//    print('iterate:'.$iterate.PHP_EOL);
//    var_dump($time_arr);
    return $time_arr;
}


function table_helper($open_hours, $range, $interval, $key){
    while (key($open_hours) != $key) { next($open_hours); }
    print(key($open_hours).PHP_EOL.$key.PHP_EOL);
    $arr = [];
    if(strpos(current($open_hours),',')){
        $t = explode(',',current($open_hours));
        foreach ($t as $str){
            $time = explode('-',$str);
            $start = $time[0];
            $end = $time[1];
            $tmp = time_table($start,$end,$range,$interval);
            $arr = array_merge($arr,$tmp);
        }
//        print('split is workings!!!');
    } else if(current($open_hours)=='ALL' | current($open_hours)==1){
        $start = '0:00';
        $end = '24:00';
        $arr = time_table($start,$end,$range,$interval);
    } else if(strpos(current($open_hours),'-')){
        $time = explode('-',current($open_hours));
        $start = $time[0];
        $end = $time[1];
        $arr = time_table($start,$end,$range,$interval);
        print('------- 00 is working!  '.$start."  ".$end.PHP_EOL);

    } else {

    }
    return $arr;
}



function table_helper_11($open_hours, $range, $interval, $key, $start){
    while (key($open_hours) != $key) { next($open_hours); }
    print(key($open_hours).PHP_EOL.$key.PHP_EOL);
    $arr = [];
    if(strpos(current($open_hours),',')){
        $t = explode(',',current($open_hours));
        $time = explode('-',$t[0]);
        $begin= $start;
        $end = $time[1];
        $arr =time_table($begin,$end,$range,$interval);
        $time2 = explode('-',$t[1]);
        $begin2= $time2[0];
        $end2 = $time2[1];
        $tmp = time_table($begin2,$end2,$range,$interval);
        $arr = array_merge($arr,$tmp);

    } else if(current($open_hours)=='ALL' | current($open_hours)==1){
        $begin = $start;
        $end = '24:00';
        $arr=time_table($begin,$end,$range,$interval);
    } else if(strpos(current($open_hours),'-')){
        $time = explode('-',current($open_hours));
        $begin = $start;
        $end = $time[1];
        $arr=time_table($begin,$end,$range,$interval);
        print('------- is working!11  '.$start."  ".$end.PHP_EOL);

    } else {

    }
    return $arr;
}

function table_helper_12($open_hours, $range, $interval, $key, $start){
    while (key($open_hours) != $key) { next($open_hours); }
    print(key($open_hours).PHP_EOL.$key.PHP_EOL);
    $arr = [];
    if(strpos(current($open_hours),',')){
        $t = explode(',',current($open_hours));
        $time2 = explode('-',$t[1]);
        $begin2= $start;
        $end2 = $time2[1];
        $arr = time_table($begin2,$end2,$range,$interval);

    } else if(current($open_hours)=='ALL' | current($open_hours)==1){
        $begin = $start;
        $end = '24:00';
        $arr  = time_table($begin,$end,$range,$interval);
    } else if(strpos(current($open_hours),'-')){
        $time = explode('-',current($open_hours));
        $begin = $start;
        $end = $time[1];
        $arr = time_table($begin,$end,$range,$interval);
        print('------- is working! 22 '.$start."  ".$end.PHP_EOL);

    } else {

    }
    return $arr;
}

// p1 open soon
function table_helper_13($open_hours, $range, $interval, $key){
    while (key($open_hours) != $key) { next($open_hours); }
    print(key($open_hours).PHP_EOL.$key.PHP_EOL);
    $arr = [];
    if(strpos(current($open_hours),',')){
        $t = explode(',',current($open_hours));
        $time = explode('-',$t[0]);
        $begin= $time[0];
        $end = $time[1];
        $arr = time_table($begin,$end,$range,$interval);
        $time2 = explode('-',$t[1]);
        $begin2= $time2[0];
        $end2 = $time2[1];
        $tmp = time_table($begin2,$end2,$range,$interval);
        $arr = array_merge($arr,$tmp);

    } else if(current($open_hours)=='ALL' | current($open_hours)==1){
        $begin = '0:00';
        $end = '24:00';
        $arr = time_table($begin,$end,$range,$interval);
    } else if(strpos(current($open_hours),'-')){
        $time = explode('-',current($open_hours));
        $begin = $time[0];
        $end = $time[1];
        $arr = time_table($begin,$end,$range,$interval);
        print('------- is working! 33 '.$begin."  ".$end.PHP_EOL);

    } else {

    }
    return $arr;
}

//open soon p2
function table_helper_14($open_hours, $range, $interval, $key){
    while (key($open_hours) != $key) { next($open_hours); }
    print(key($open_hours).PHP_EOL.$key.PHP_EOL);
    $arr=[];
    if(strpos(current($open_hours),',')){
        $t = explode(',',current($open_hours));
        $time2 = explode('-',$t[1]);
        $begin2= $time2[0];
        $end2 = $time2[1];
        $arr =time_table($begin2,$end2,$range,$interval);

    } else if(current($open_hours)=='ALL' | current($open_hours)==1){
        $begin = '0:00';
        $end = '24:00';
        $arr = time_table($begin,$end,$range,$interval);
    } else if(strpos(current($open_hours),'-')){
        $time = explode('-',current($open_hours));
        $begin = $time[0];
        $end = $time[1];
        $arr = time_table($begin,$end,$range,$interval);
        print('------- is working!44  '.$begin."  ".$end.PHP_EOL);

    } else {

    }
    return $arr;
}


function add_time($time, $min)
{
    print("time before add:".$time." ".$min.PHP_EOL);
    if(strpos($time,'pm')){
        $h=12;
    }else {
        $h =0;
    }
    $t = explode(':',$time);
    $h += $t[0];
    $i = $t[1];
    $mins = (int)$i+(int)$min;
    $h = $h + floor($mins/60);
    $i = ($mins)%60;
    $r = $i%15;
    $i = $i -$r + 15;
    print('gergstart time is: '."$h:$i");
    return "$h:$i";
}
