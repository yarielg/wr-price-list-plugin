<div class="wrpl-page">

    <div class="container-fluid">
        <h1>Poducts</h1>
        <br>
        <div class="row">
            <div class="col-12 text-center">
                <select class="custom-select custom-select-md w-50" name="price_list" id="price_list">
                    <option value="default" selected>Default Woocommerce</option>
                    <option value="distributor" selected>Distributor</option>
                    <option value="dealer" selected>Dealer</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="container-fluid">
                <div class="row px-3">
                    <div class="col-12">

                        <?php include 'includes/_table_products.php'?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

