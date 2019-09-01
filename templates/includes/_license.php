<?php
use Wrpl\Inc\Base\WRPL_Signature;
$signature = new WRPL_Signature();

if($_POST['wrpl_submit_license']){
    $purchase_code = trim($_POST['wrpl_signature']);
    $response = null;
    if($signature->is_valid()){
        $response = $signature->remove_license($purchase_code);
    }else{
        $response = $signature->save_license($purchase_code);
    }
    $type_alert = $response['status'] == 'success' ? 'success' : 'danger';
    echo '<div class="alert alert-'. $type_alert .'" role="alert">'
         . $response['message'] .
        '</div>';
}

?>

<div class="container">
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="card ">
                <div class="card-header">
                    Plugin License
                        <?php
                        if($signature->is_valid()){
                            echo '<span  class="p-1 badge badge-success float-right">Valid License</span>';
                        }else{
                            echo '<span  class="p-1 badge badge-danger float-right">Invalid License</span>';
                        }
                        ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <p class="card-text">In order to enable all plugin functionality including demo content installation, you first need to validate your theme license by entering the purchase code below.</p>
                    <form action="" method="post">
                        <div class="form-row">
                            <div class="col-9">
                                <input type="text" name="wrpl_signature" class="form-control form-control-sm w-100" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" required value="<?php echo $signature->get_license() ?>" >
                            </div>
                            <div class="col-3">
                                <input type="submit" name="wrpl_submit_license" class="btn btn-sm btn-info" value="<?php echo $signature->is_valid() ? 'Remove license' : 'Add License'; ?>" >
                            </div>

                        </div>
                    </form>
                </div>
                <div class="card-footer text-muted">
                    Note! One plugin license per domain is allowed. For any issues with theme activation please refer to this article. If registered elsewhere please deactivate that license or purchase another plugin copy.
                </div>
            </div>
        </div>
        <div class="col-2"></div>
    </div>
</div>