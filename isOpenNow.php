<?php

// time zone is the current time / might change to time_zone later on
// check if we should show time slot for today or not
// period 1 = 11, period 2 = 12
// period 1 soon = 13, period 2 soon = 14
function isOpenNow($open_hours, $time_zone)
{
    $res = 0;
    $today = date('D');
    $today = strtoupper($today);
    //$open_hours = json_decode($open_hours);
    reset($open_hours);
    if(key($open_hours)!='ALL'){
        while(key($open_hours)!=$today) {next($open_hours);}
    }
//    print('todays day: '.$today."   openhours:".key($open_hours)."  hours:".current($open_hours).PHP_EOL);
    if(current($open_hours) == "CLOSED" | current($open_hours) == -1)
    {
//        print('falseeeeeeeeeeeeeeeeeeeeeeeeeeeee');
        return false;
    } elseif (current($open_hours) == "ALL" | current($open_hours) == 1) {
//        print('alllllllllllllalllllllllllllllllll');
        return true;
    } else {
        if( strpos(current($open_hours),',')){
            $split = explode(',', current($open_hours));
            $str1=$split[0];
            $str2=$split[1];
            $p1 = time_compare($str1,$time_zone);
            $p2 = time_compare($str2,$time_zone);
            if($p1===true) {
//                print('1111111111111111111111');
                $res=11;
            }else if($p1==='soon'){
//                print('111111111111111113333333333333333333333333333333331');
                $res=13;
            }else if($p2===true){
//                print('11111111111111111333222222222222222222222222222222');
                $res=12;
            }else if($p2==='soon'){
//                print('11111111111111111344444444444444444444444444444');
                $res=14;
            }else{
//                print('falseeeeeeeeeeeeeeeeeeeeeeeeeeeee');
                return false;
            }
        } else {
            $t = time_compare(current($open_hours), $time_zone);
            if($t==='soon'){
//                print('圖圖圖圖圖圖圖圖圖圖田'.$t);
                $res=13;
            }
            else if ($t===true){
//                print('圖圖圖圖圖圖圖圖圖圖22222222222222222222田'.$t);
                $res=11;
            }else{
//                print('twatawjgnoawgwaiogwigw');
            }
        }

    }
    print("res:".$res.PHP_EOL);
    return $res;
}

// Compare time in linux timestamp format
function time_compare($time_range, $current_time){
    $time_piece = explode("-",$time_range);
    $start = new DateTime($time_piece[0]);
    $end = new DateTime($time_piece[1]);
    $time = new DateTime($current_time);

    if($time >= $start && $time <= $end | $time >= $start && $time >= $end && $start>$end)
    {
//        print("currently open");
        return true;
    }else if($time <= $start && $time <= $end) {
//        print("opening sooooon");
        return 'soon';
    }else if ($time <= $start && $time >= $end){
//        print(" open sonnoooooooon");
        return 'soon';
    }else{
        return false;
    }
}
//
//$open_hours = '{"MON":"09:00-17:00,20:00-23:00","TUE":"CLOSED","WED":"09:00-17:00,19:00-3:00","THU":"09:00-17:00","FRI":"09:00-17:00,22:00-03:00","SAT":"10:00-11:00,13:00-14:00","SUN":"ALL"}';
//$time_zone = '3:01pm';
//var_dump(isOpenNow($open_hours,$time_zone));
