

<select class="custom-select custom-select-md " name="price_list" id="price_list">
    <option value="default" selected>Default Woocommerce</option>
    <?php
        foreach ($plists as $plist) {
            echo "<option value='{$plist['id']}'>{$plist['description']}</option>";
        }
    ?>
</select>