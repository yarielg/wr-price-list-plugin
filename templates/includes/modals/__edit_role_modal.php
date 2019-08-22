<!-- Modal -->
<div class="modal fade" id="wrpl_edit_role_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white" >
                <h5 class="modal-title" id="exampleModalLabel"><?php _e('Edit Role','wr_price_list') ?> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <br>

                <form action="" method="post">

                    <div class="form-group row ml-3 mb-2">
                            <label for="wrpl-edit-pl" class=" form-control-sm col-sm-2" ><?php _e('Name:','wr_price_list') ?></label>
                            <input type="text" name="wrpl_role_name" id="wrpl-edit-role" class="form-control form-control-sm col-sm-9" required min="3" max="100">
                            <input type="text" name="wrpl_role_old_name" id="wrpl-edit-old_role" hidden>
                    </div>
                    <br>
                    <br>
                    <button type="button" class="btn btn-default btn-sm mr-3" data-dismiss="modal"><?php _e('Cancel','wr_price_list') ?></button>
                    <input name="wrpl_edit_role_action"  type="submit" class="btn btn-info btn-sm ml-3" value="<?php _e('Edit','wr_price_list') ?>">
                </form>
            </div>
        </div>
    </div>
</div>