<!-- Modal -->
<div class="modal fade" id="wrpl_remove_pl_modal" data-backdrop="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white" >
                <h5 class="modal-title" id="exampleModalLabel">Remove Price List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="alert alert-warning fade show" role="alert">

                    <ul>
                        <li><?php _e('Alert! The price list could have some data integrated, if you continue the following information will be removed:','wr_price_list') ?></li>
                        <li>- <?php _e('All the price related with this list','wr_price_list') ?> </li>
                        <li>- <?php _e('All relations between this list and roles, categories, etc','wr_price_list') ?></li>
                        <br>
                        <li><?php _e('Do you want to continue?','wr_price_list') ?></li>
                    </ul>

                </div>
                <br>

                <form action="" method="post">
                    <input name="wrpl_pl_id" id="wrpl-pl" hidden>
                        <button type="button" class="btn btn-warning btn-sm mr-3 mb-3" data-dismiss="modal">No, I don't want to remove it</button>
                        <input name="wrpl_remove_pl_action"  type="submit" class="btn btn-danger btn-sm ml-md-3 " value="Yes, I know the consequences">
                </form>
            </div>
        </div>
    </div>
</div>