<?php $categories = $product_controller->wrpl_get_all_product_cat(); ?>
<div class="col-8">

</div>
<div class="col-4">
    <select class="custom-select custom-select-sm mr-3 pr-3" name="categories_select_search" id="categories_select_search">
        <option value="0">--<?php _e('All Categories','wr_price_list')?>--</option>
        <?php
        foreach ($categories as $category) {
            echo "<option  value='{$category['term_id']}'>{$category['name']}</option>";
        }
        ?>
    </select>
</div>
