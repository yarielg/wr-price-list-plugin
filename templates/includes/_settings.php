<?php include 'requests/_settings_request.php' ?>

<div class="settings_tab_container container-fluid" >
    <form action="" method="post">
        <p><?php _e('Choose a default price list to unregistered users','wr_price_list') ?></p>
        <div class="form-group row ml-1">
            <label id="default_price_list" for="default_price_list" class="form-control-sm col-sm-2"><?php _e('Price List:','wr_price_list') ?></label>
            <select name="default_price_list" class="form-control form-control-sm col-sm-9" id="default_price_list">
                <?php
                foreach ($plists as $plist) {
                    $selected = get_option('wrpl-default_list') == $plist['id'] ? 'selected' : '';
                    echo "<option value='{$plist['id']}' {$selected}>{$plist['description']}</option>";
                }
                ?>
            </select>
        </div>
        <hr>
        <p><?php _e('Check this and choose a personalized message to hide prices from unregistered users','wr_price_list') ?></p>
        <div class="form-row">
            <div class="col-4">
                <div class=" form-group custom-control custom-checkbox mb-3 ml-1 row">
                    <input type="checkbox" name="hide_price" class=" custom-control-input" id="hide_price" <?php echo get_option('wrpl-hide_price') == 0 ? '' : 'checked' ?>>
                    <label class="custom-control-label  form-control-sm" for="hide_price"><?php _e('Hide price to unregistered users','wr_price_list') ?></label>
                </div>
            </div>

            <div class="col-8">
                <label id="custom_msg_not_login_user_label" for="custom_msg_not_login_user" class="disabled"><?php _e('Custom message to unregistered users','wr_price_list') ?></label>
                <textarea name="custom_message" class="form-control" id="custom_msg_not_login_user" rows="3"  placeholder="<strong>Please</strong>, <a href='url-to-login'>login</a> to see price"  <?php echo get_option('wrpl-hide_price') == 0 ? 'disabled' : '' ?>><?php echo stripslashes(get_option('wrpl-custom_msg_no_login_user')) ?></textarea>
            </div>
        </div>

        <hr>
        <p><?php _e('Choose which method to use for price list assignment','wr_price_list') ?></p>
        <div class="form-row">
            <div class="form-check form-check-inline">
                <input class="form-check-input ml-1" type="radio" name="by_rbtn" id="rbtn_by_role" value="1" <?php echo get_option('wrpl-assign-method') == 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="rbtn_by_role"><?php _e('By Role','wr_price_list') ?> </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="by_rbtn" id="rbtn_by_category" value="2" <?php echo get_option('wrpl-assign-method') == 2 ? 'checked' : '' ?><?php echo $signature->is_valid() ? '':'disabled' ?>>
                <label id="rbtn_by_category_label" class="form-check-label" for="rbtn_by_category"><?php _e('By Category','wr_price_list') ?></label>
            </div>
        </div>

        <br><br>
        <div class="form-group">
            <input type="submit" class="btn btn-info btn-sm" name="save_settings" id="import_price_list" value="<?php _e('Save Settings','wr_price_list') ?>">
        </div>
    </form>
</div>