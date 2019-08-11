

<select class="custom-select custom-select-md " name="price_list" id="price_list">
    <?php
        foreach ($plists as $plist) {
            $name_parent = $plist['id_parent'] > 0 ?  ' ( based on ' . $price_list_controller->wrpl_get_price_list_name_by_id($plist['id_parent']) . ')'  : '';
            echo "<option id='wrpl-option-{$plist['id']}' pl-parent='{$plist['id_parent']}' pl-factor='{$plist['factor']}' value='{$plist['id']}'>{$plist['description']} <span class='wrpl-increase'>{$name_parent}</span></option>";
        }
    ?>
</select>