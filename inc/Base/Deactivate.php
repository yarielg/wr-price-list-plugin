<?php

/*
*
* @package yariko		
*
*/

namespace Wrpl\Inc\Base;

class Deactivate{

    public static function deactivate(){
        $signature = new WRPL_Signature();
        if($signature->is_valid()){
            $result = $signature->remove_license($signature->get_license());
        }
        flush_rewrite_rules();
    }
}	
