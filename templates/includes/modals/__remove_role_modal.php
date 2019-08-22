<!-- Modal -->
<div class="modal fade" id="wrpl_remove_role_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white" >
                <h5 class="modal-title" id="exampleModalLabel">Remove Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="alert alert-warning fade show" role="alert">

                    <ul>
                        <li><?php _e('Alert! The role could have some data integrated, if you continue the following information will be removed:','wr_price_list') ?></li>
                        <li>- <?php _e('All the price related with this role','wr_price_list') ?> </li>
                        <li>- <?php _e('All relations between this role and price lists, categories, etc','wr_price_list') ?></li>
                        <br>
                        <li><?php _e('Do you want to continue?','wr_price_list') ?></li>
                    </ul>
                </div>
                <br>

                <form action="" method="post">

                    <div class="row ml-3 mb-2">
                        <div class="col-2">
                            <label for="wrpl-edit-pl" class="form-control-label form-control-sm" hidden ><?php _e('Name:','wr_price_list') ?></label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="wrpl_role_name" id="wrpl-edit-role" class="form-control form-control-sm" hidden>
                        </div>
                    </div>
                    <br>
                    <br>
                    <button type="button" class="btn btn-default btn-sm mr-3" data-dismiss="modal"><?php _e('No, I don\'t want to remove it','wr_price_list')?></button>
                    <input name="wrpl_remove_role_action"  type="submit" class="btn btn-danger btn-sm ml-3" value="<?php _e('Yes, I know the consequences','wr_price_list') ?>">
                </form>
            </div>
        </div>
    </div>
</div>