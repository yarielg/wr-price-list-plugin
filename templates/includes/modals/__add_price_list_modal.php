<!-- Modal -->
<div class="modal fade" id="wrpl_add_pl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white" >
                <h5 class="modal-title" id="exampleModalLabel">Add Price List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <br>

                <form action="" method="post" class="p-1 ml-3">
                    <div class="row ml-3 mb-2">
                        <label for="wrpl-add_pl_name" class="col-sm-2 form-control-sm" >Name:</label>
                        <input type="text" name="wrpl_pl_name" id="wrpl-add_pl_name" class="form-control form-control-sm col-sm-9" required ">
                    </div>
                    <div class="row ml-3 mb-2">
                        <label for="wrpl-add_pl_base" class="col-sm-2 form-control-sm" >Based:</label>
                        <select class="custom-select form-control form-control-sm col-sm-9" name="price_list" id="price_list">
                            <option value="0" selected>-- No Price List --</option>
                            <?php
                            $plists = $price_list_controller->wrpl_get_price_lists(false);
                            foreach ($plists as $plist) {
                                echo "<option value='{$plist['id']}'>{$plist['description']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row ml-3 mb-2">
                        <label for="wrpl-add_pl_factor" id="wrpl-add_pl_factor_label" class="col-sm-2 form-control-sm disabled" >Factor:</label>
                        <input type="number" name="wrpl_pl_factor" id="wrpl-add_pl_factor" class="form-control form-control-sm col-sm-9" step="0.01" required min="0" max="100" disabled>
                    </div>
                    <br>
                    <div class="form-group row">
                        <button type="button" class="btn btn-default btn-sm mr-3" data-dismiss="modal">Cancel</button>
                        <input name="wrpl_new_price_list"  type="submit" class="btn btn-info btn-sm ml-3" value="Add">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>