<?php
if(isset($_POST['save_settings'])){
    //define default price list
    update_option('wrpl-default_list',sanitize_text_field(intval($_POST['default_price_list'])));
    update_option('wrpl-assign-method',sanitize_text_field(intval($_POST['by_rbtn'])));
    update_option('wrpl-format-price-method',sanitize_text_field(intval($_POST['rbtn_format_price'])));

    //hide price
    if(isset($_POST['hide_price'])){
        if($_POST['hide_price'] == 'on'){
            //allow any input from the user(user is the admin, so he can decide the input here).
            //this set a default message to unregistered users, so here could have html tags.
            update_option('wrpl-custom_msg_no_login_user', wp_filter_post_kses($_POST['custom_message']));
            update_option('wrpl-hide_price', 1);
        }
    }else{
        update_option('wrpl-hide_price', 0);
    }


}
?>