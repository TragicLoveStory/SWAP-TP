<?php 
date_default_timezone_set('Singapore');
$start = new DateTime(date('Y-m-d',1672588800));
$end = new DateTime(date('Y-m-d',1672848000));
$days  = $end->diff($start)->format('%a');
$days+=1;
echo $days."<br>";
for($i = $start; $i <= $end; $i->modify('+1 day')){
    echo $i->format("Y-m-d")."<br>";
}
?>