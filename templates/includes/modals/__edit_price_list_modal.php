<!-- Modal -->
<div class="modal fade" id="wrpl_edit_pl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white" >
                <h5 class="modal-title" id="exampleModalLabel">Edit Price List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

               <!-- <div class="alert alert-warning fade show" role="alert">

                    <ul>
                        <li>Alert! The price list could have some data integrated, if you continue the following information will be removed:</li>
                        <li>- All the price related with this list</li>
                        <li>- All relations between this list and roles, categories, etc</li>
                        <br>
                        <li>Do you want to continue?</li>
                    </ul>
                </div>-->
                <br>

                <form action="" method="post">

                    <div class="row ml-3 mb-2">
                            <label for="wrpl-edit-pl" class="col-sm-2 form-control-sm" >Name:</label>
                            <input type="text" name="wrpl_pl_name" id="wrpl-edit-pl" class="form-control form-control-sm col-sm-9" required min="3" max="100">
                            <input type="text" name="wrpl_pl_id" id="wrpl-edit-pl-id" class="form-control form-control-sm" hidden>
                    </div>
                    <br>
                    <br>
                    <button type="button" class="btn btn-default btn-sm mr-3" data-dismiss="modal">Cancel</button>
                    <input name="wrpl_edit_pl_action"  type="submit" class="btn btn-info btn-sm ml-3" value="Edit">
                </form>
            </div>
        </div>
    </div>
</div>