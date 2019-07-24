<?php


function stdToArray($stds){
    $php_array = array();
    foreach($stds as $std){
        $items = (array)$std;
        array_push($php_array, $items);
    }
    return $php_array;
}

function wrpl_roles() {

    global $wp_roles;

    $roles = $wp_roles->roles;

    return $roles;

}

function wrpl_valid_name($name){
    $valid_id = str_replace(" ","_",$name);
    $valid_id = strtolower($valid_id);
    return $valid_id;
}


