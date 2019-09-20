<?php
//IMPORT REQUESTS
include 'requests/_import_price_list_request.php';
?>

<div class="container-fluid">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="custom-control custom-checkbox mb-3 row">
            <input type="checkbox" class=" custom-control-input" id="check_price_list">
            <label class="custom-control-label" for="check_price_list"><?php _e('Create a new Price List','wr_price_list') ?></label>
        </div>
        <div class="form-group row">
            <label id="import_new_price_list_label" for="import_new_price_list" class="form-control-sm col-sm-2 disabled"><?php _e('New price list name','wr_price_list') ?>:</label>
            <input type="text" class="form-control form-control-sm col-sm-9" name="import_new_price_list" id="import_new_price_list" disabled required min="3" max="100">
        </div>

        <div class="form-group row">
            <label id="import_select_price_list_label" for="import_new_price_list" class="form-control-sm col-sm-2 disabled"><?php _e('Choose price list','wr_price_list') ?></label>
            <select name="price_list_id" class="form-control form-control-sm col-sm-9" id="import_select_price_list">
                <?php
                $plists = $price_list_controller->wrpl_get_price_lists(false);
                foreach ($plists as $plist) {
                    echo "<option value='{$plist['id']}'>{$plist['description']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group row">
            <label class="form-control-sm col-sm-2"><?php _e('Choose csv file to import','wr_price_list') ?></label>
            <input type="file" class=" col-sm-9" name="file_import" id="file_import" >
        </div>


        <div class="form-group">
            <input type="submit" class="btn btn-info btn-sm" name="import_price_list" id="import_price_list" value="Import" disabled>
        </div>

        <div class="import_result">

            <?php

            if(isset($imported_msgs)){
                echo '<h5 class="text-center"> ' . __('Import results','wr_price_list') . '</h5>';

                if(isset($_POST['import_new_price_list'])){
                    $new_price_list_name = sanitize_text_field($_POST['import_new_price_list']);
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong> ' . __('Price list ','wr_price_list') . $new_price_list_name . ' ' . __('was created. ','wr_price_list') .'</strong>' . ' ' . __('The products price were inserted in this new list','wr_price_list').
                    '</div>';
                }
                $count_success = 0;
                $count_failure = 0;
                $msgs = '';
                foreach ($imported_msgs as $msg){
                    if($msg['type']=='success'){
                        $count_success++;
                    }else{
                        $count_failure++;
                        $msgs .= $msg['msg'] . '</br>';
                    }

                }
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>' . __('It were inserted or updated ' ,'wr_price_list') . $count_success . __(' products successfully.','wr_price_list') .
                     '</div>';
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>' . __('It were found ','wr_price_list') . $count_failure . __(' errors, below a list with the details.','wr_price_list') .' 
                     </div>';
                echo $msgs;
            }
            ?>
        </div>

    </form>


</div>