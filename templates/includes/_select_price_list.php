<?php
global $wpdb;

$plists = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "wr_price_lists" );

$plists = stdToArray($plists);

?>

<select class="custom-select custom-select-md " name="price_list" id="price_list">
    <option value="default" selected>Default Woocommerce</option>
    <?php
        foreach ($plists as $plist) {
            echo "<option value='{$plist['id']}'>{$plist['description']}</option>";
        }
    ?>
</select>