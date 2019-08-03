<?php
if(isset($_POST['save_settings'])){
    //define default price list
    update_option('wrpl-default_list',$_POST['default_price_list']);

    //hidde price
    if($_POST['hide_price'] == 'on'){
            update_option('wrpl-custom_msg_no_login_user',$_POST['custom_message']);
            update_option('wrpl-hide_price', 1);
    }else{
        update_option('wrpl-hide_price', 0);
    }
}
?>