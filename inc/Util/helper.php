<?php


function stdToArray($stds){
    $php_array = array();
    foreach($stds as $std){
        $items = (array)$std;
        array_push($php_array, $items);
    }
    return $php_array;
}
