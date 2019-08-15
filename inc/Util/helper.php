<?php


function stdToArray($stds){
        $php_array = array();
        for($i = 0; $i<count($stds);$i++){
            $item = (array)$stds[$i];
            array_push($php_array, $item);
        }
        return $php_array;
}

function wrpl_roles() {

    global $wp_roles;

    $roles = $wp_roles->roles;
    return $roles;

}

function wrpl_valid_name($name){
    $valid_id = trim($name);
    $valid_id = str_replace(" ","_",$valid_id);
    $valid_id = strtolower($valid_id);
    return $valid_id;
}

function wrpl_convert_to_separate_value($array,$key ){
    $array = stdToArray($array);
    $result =  '';
    for ($i = 0; $i < count($array); $i++){
        $result .= $array[$i][$key] . ($i < count($array) - 1 ? ',' : '');
    }

    return $result;
}


