<?php
// Start epoch indicates the beginning date of subscription

// last epoch indicates the termination date of the subscription

// time units ie: daily, weekly, monthly, yearly


// period ie: is the number of occurences of the time units ie: 2 weeks
function get_next_billing_date($start_epoch,$last_epoch,$time_units,$period){
// check if the start epoch is defined
if(!$start_epoch){
    echo "Start epoch must be defined";
    return;
}
if(!$last_epoch){
    $last_epoch=$start_epoch;
}
//if period is not defined we assume period is 1
if(!$period){
    $period=1;
} 
// we define all time_units
$known_units=array(
    'daily'=>1,
    'weekly'=>7,
    'monthly'=>1,
    'yearly'=>1,
);
if(!array_key_exists($time_units,$known_units)){
    echo "time unit".$time_units."is not known";
    return;
}
$duration=$known_units[$time_units];
$duration=$duration*$period;


$start= new DateTime($start_epoch);
$last=new DateTime($last_epoch);
$year=(int)$last->format('Y');
$month=(int)$last->format('n');
$day=(int)$last->format('d');
$hms=$start->format('G:i:s');
switch ($time_units){
    case 'daily':
    case 'weekly':
        $day+=$duration;
        break;
    case 'monthly':
        $month+=$duration;
        break;
    case 'yearly':
        $year+=$duration;
        break;
    default:
    echo 'Unknown time unit';
    return;

}
if($day>31){
    // number of days in a given month
   $num_of_days=$last->format('t');
   while($day>31){
    $day= $day-$num_of_days;
    $month+=1;
   } 
   
}

if($month>12){
    $num_of_days=$last->format('t');
   if($day>$num_of_days){
        $day=$day-$num_of_days;
        $month+=1;
    }
    $month=$month-12;
    $year+=1;
}

while(true){
    
    $next=new DateTime("{$year}-{$month}-{$day} {$hms} UTC");
    if($next->format('n')==$month){
      
        break;
    }
    else{
        $day--;
    }
}
return (int) $next->format('U') ;
// end of function
}
// Test code
$verbs= array(
    'daily'=>12,
    'weekly'=>14,
    'monthly'=>7,
    'yearly'=>5,
);
$start_at=date("Y/m/d");
foreach($verbs as $time_unit =>$period){
   
    $next=get_next_billing_date($start_at,null,$time_unit,$period);
    $next_date=date("Y/m/d",$next);
    echo"From {$start_at} after {$period} in {$time_unit} the next billing date will be {$next_date}\n ";
}
?>
