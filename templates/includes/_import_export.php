<?php
//IMPORT REQUESTS
include 'requests/_import_price_list_request.php';
?>

<div class="container-fluid">
    <form action="" method="post" enctype="multipart/form-data">
        <div class=" form-group custom-control custom-checkbox mb-3 row">
            <input type="checkbox" class=" custom-control-input" id="check_price_list">
            <label class="custom-control-label" for="check_price_list">Create new Price List</label>
        </div>
        <div class="form-group row">
            <label id="import_new_price_list_label" for="import_new_price_list" class="form-control-sm col-sm-2 disabled">Enter the price list name:</label>
            <input type="text" class="form-control form-control-sm col-sm-9" name="import_new_price_list" id="import_new_price_list" disabled required min="3" max="100">
        </div>

        <div class="form-group row">
            <label id="import_select_price_list_label" for="import_new_price_list" class="form-control-sm col-sm-2 disabled">Choose a price list:</label>
            <select name="price_list_id" class="form-control form-control-sm col-sm-9" id="import_select_price_list">
                <option value="default" selected>Default Woocommerce</option>
                <?php
                foreach ($plists as $plist) {
                    echo "<option value='{$plist['id']}'>{$plist['description']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group row">
            <label class="form-control-sm col-sm-2">Choose the csv file to export:</label>
            <input type="file" class=" col-sm-9" name="file_import" id="file_import" >
        </div>


        <div class="form-group">
            <input type="submit" class="btn btn-info btn-sm" name="import_price_list" id="import_price_list" value="Import" disabled>
        </div>

        <div class="import_result">

            <?php

            if(isset($imported_msgs)){
                echo '<h5 class="text-center">Import Results</h5>';

                if(isset($_POST['import_new_price_list'])){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Price List ' .$_POST['import_new_price_list'] . ' was created!</strong> The products price were inserted in this new list.
                     </div>';
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
                        <strong>It were inserted or updated ' . $count_success .' products successfully.
                     </div>';
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>It were found ' . $count_failure .' errors, below a list with the details.
                     </div>';
                echo $msgs;
            }
            ?>
        </div>

    </form>


</div>