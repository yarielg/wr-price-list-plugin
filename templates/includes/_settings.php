<?php include 'requests/_settings_request.php' ?>

<div class="settings_tab_container container-fluid" >
    <form action="" method="post">
        <p>Choose a default price list to show to no registered user</p>
        <div class="form-group row ml-1">
            <label id="default_price_list" for="default_price_list" class="form-control-sm col-sm-2">Price list:</label>
            <select name="default_price_list" class="form-control form-control-sm col-sm-9" id="default_price_list">
                <option value="default">Default Woocommerce</option>
                <?php
                foreach ($plists as $plist) {
                    $selected = get_option('wrpl-default_list') == $plist['id'] ? 'selected' : '';
                    echo "<option value='{$plist['id']}' {$selected}>{$plist['description']}</option>";
                }
                ?>
            </select>
        </div>
        <hr>
        <p>Check this and choose a custom message for hiding the price to no login user</p>
        <div class="form-row">
            <div class="col-4">
                <div class=" form-group custom-control custom-checkbox mb-3 ml-1 row">
                    <input type="checkbox" name="hide_price" class=" custom-control-input" id="hide_price" <?php echo get_option('wrpl-hide_price') == 0 ? '' : 'checked' ?>>
                    <label class="custom-control-label  form-control-sm" for="hide_price">Hide Price to no login user</label>
                </div>
            </div>

            <div class="col-8">
                <label id="custom_msg_not_login_user_label" for="custom_msg_not_login_user" class="disabled">Custom message for no login user</label>
                <textarea name="custom_message" class="form-control" id="custom_msg_not_login_user" rows="3"  placeholder="<strong>Please</strong>, <a href='url-to-login'>login</a> to see price"  <?php echo get_option('wrpl-hide_price') == 0 ? 'disabled' : '' ?>><?php echo stripslashes(get_option('wrpl-custom_msg_no_login_user')) ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-info btn-sm" name="save_settings" id="import_price_list" value="Save settings">
        </div>
    </form>
</div>