<?php
if(isset($_POST['save_settings'])){
    //define default price list
    update_option('wrpl-default_list',$_POST['default_price_list']); //Price List by default for no login user
    update_option('wrpl-assign-method',$_POST['by_rbtn']); //Assign method 1- by cat 2- by role

    //hidde price
    if(isset($_POST['hide_price'])){
        if($_POST['hide_price'] == 'on'){
            update_option('wrpl-custom_msg_no_login_user',$_POST['custom_message']);
            update_option('wrpl-hide_price', 1);
        }
    }else{
        update_option('wrpl-hide_price', 0);
    }


}
?>