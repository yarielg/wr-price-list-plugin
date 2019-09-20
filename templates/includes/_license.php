<?php

if($_POST['wrpl_submit_license']){
    $purchase_code = sanitize_text_field($_POST['wrpl_signature']);
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
        <div class="col-md-7">
            <p>**The license is just required for the premiun version.</p>
            <div class="card ">
                <div class="card-header">
                    Plugin License
                        <?php
                        if($signature->is_valid()){
                            echo '<span  class="p-1 badge badge-success float-right">Valid License</span>';
                        }else{
                            echo '<span  class="p-1 badge badge-danger float-right">Required License</span>';
                        }
                        ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <p class="card-text">In order to enable all plugin functionality including demo content installation, you first need to validate your theme license by entering the purchase code below.</p>
                    <form action="" method="post">
                        <div class="form-row">
                            <div class="col-9">
                                <input type="text" name="wrpl_signature" class="form-control form-control-sm w-100" placeholder="xxxxxxxxxxxxxxxxxxxxxx" required value="<?php echo $signature->get_license() ?>" >
                            </div>
                            <div class="col-3">
                                <input type="submit" name="wrpl_submit_license" class="btn btn-sm btn-info" value="<?php echo $signature->is_valid() ? 'Remove license' : 'Add License'; ?>" >
                            </div>

                        </div>
                    </form>
                </div>
                <div class="card-footer text-muted">
                    Note! One plugin license per domain is allowed. For any issues with theme activation please refer to the plugin's doc. If registered elsewhere please deactivate that license or purchase another plugin copy.
                </div>
            </div>
        </div>

        <div class="col-md-5 mt-3">
                <div class="card mb-5 mb-lg-0">
                    <div class="card-body">
                        <h5 class="card-title text-muted text-uppercase text-center">Premium</h5>
                        <h6 class="card-price text-center">$12.00</h6>
                        <hr>
                        <ul class="fa-ul">
                            <li><span class="fa-li"><i class="fas fa-check"></i></span><strong>- Unlimited Price List.</strong></li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>- Create Discount Based on Price List.</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>- Price List by Product Categories.</li>

                            <li><span class="fa-li"><i class="fas fa-check"></i></span>- Create Price Rules.</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>- Import Prices from CSV.</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>- Support 24/7.</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>- Features Added every Month.</li>
                        </ul>
                        <a href="https://www.webreadynow.com/en/wr-price-list-manager-woocommerce/" target="_blank" class="btn btn-block btn-danger text-uppercase btn-sm">Buy Now</a>
                    </div>
            </div>
        </div>
    </div>
</div>