

<select class="custom-select custom-select-md " name="price_list" id="price_list">
    <option id="wrpl-option-0" pl-parent="0" value="default" selected>Default Woocommerce</option>
    <?php
        foreach ($plists as $plist) {
            $name_parent = $plist['id_parent'] > 0 ? ($plist['id_parent'] == 1234567890 ? ' ( based on Default Woocommerce )' : ' ( based on ' . $price_list_controller->wrpl_get_price_list_name_by_id($plist['id_parent']) . ')' )  : '';
            echo "<option id='wrpl-option-{$plist['id']}' pl-parent='{$plist['id_parent']}' pl-factor='{$plist['factor']}' value='{$plist['id']}'>{$plist['description']} <span class='wrpl-increase'>{$name_parent}</span></option>";
        }
    ?>
</select>