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
                        <li>Alert! The role could have some data integrated, if you continue the following information will be removed:</li>
                        <li>- All the price related with this role</li>
                        <li>- All relations between this role and price lists, categories, etc</li>
                        <br>
                        <li>Do you want to continue?</li>
                    </ul>
                </div>
                <br>

                <form action="" method="post">

                    <div class="row ml-3 mb-2">
                        <div class="col-2">
                            <label for="wrpl-edit-pl" class="form-control-label form-control-sm" hidden >Name:</label>
                        </div>
                        <div class="col-10">
                            <input type="text" name="wrpl_role_name" id="wrpl-edit-role" class="form-control form-control-sm" hidden>
                        </div>
                    </div>
                    <br>
                    <br>
                    <button type="button" class="btn btn-default btn-sm mr-3" data-dismiss="modal">No, I don't want to remove it</button>
                    <input name="wrpl_remove_role_action"  type="submit" class="btn btn-danger btn-sm ml-3" value="Yes, I know the consequences">
                </form>
            </div>
        </div>
    </div>
</div>