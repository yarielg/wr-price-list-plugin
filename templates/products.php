<?php
use Inc\Controller\PriceListController;
use Inc\Controller\ProductController;
$price_list_controller =  new PriceListController(); //Init all the function about price list
$product_controller =  new ProductController(); //Init all the function about price list
$plists = $price_list_controller->wrpl_get_price_lists();
$roles = stdToArray(wrpl_roles());
$tab = $_GET['tab'];
?>

<br>
<div class="wrpl-page">


    <div id="modal-overlay"></div>
    <div class="wrpl_loader"></div>

    <div class="container-fluid">

        <div id="wrpl_tabs" class="container">
            <ul  class="nav nav-pills">
                <li>
                    <a id="products"
                       href="<?php echo ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=products'?>"
                       class="<?php echo ($tab == 'products' || !isset($tab)) ? 'active' : '' ?>">
                        Products
                    </a>
                </li>
                <li>
                    <a id="price_list_by_roles"
                       href="<?php echo ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=price_list_by_roles'?>"
                       class="<?php echo $tab == 'price_list_by_roles'  ? 'active' : '' ?>">
                        Price by Role
                    </a>
                </li>
                <li>
                    <a id="create_prices_list_and_role"
                       href="<?php echo ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=create_prices_list_and_role'?>"
                       class="<?php echo $tab == 'create_prices_list_and_role'  ? 'active' : '' ?>">
                        Price Lists & Roles
                    </a>
                </li>
                <li>
                    <a id="import_export"
                       href="<?php echo ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=import_export'?>"
                       class="<?php echo $tab == 'import_export'  ? 'active' : '' ?>">
                        Import/Export
                    </a>
                </li>
                <li>
                    <a id="settings"
                       href="<?php echo ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=settings'?>"
                       class="<?php echo $tab == 'settings'  ? 'active' : '' ?>">
                        Settings
                    </a>
                </li>
            </ul>

            <div id="wrpl_container_tab"  class="tab-content clearfix">

                <?php
                    if($tab == 'products' || !isset($tab)){
                ?>
                        <div class="tab-pane show active" id="tab_products">
                            <div class="row">
                                <div class="col-7">
                                    <p>Choose a Price list to start change price quickly: </p>
                                </div>
                                <div class="col-5">
                                    <?php include 'includes/_select_price_list.php' ?>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="container-fluid">
                                    <div class="row px-3">
                                        <div class="col-12" id="datatable_container">

                                            <?php include 'includes/_table_products.php'?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }else if($tab == 'price_list_by_roles'){
                ?>

                        <div class="tab-pane show active" id="price_list_by_role">
                            <?php include 'includes/_price_list_by_role.php' ?>
                        </div>

                <?php

                    }else if($tab == 'create_prices_list_and_role'){
                ?>
                        <div class="tab-pane show active" id="create_prices_list_and_role">
                            <?php include 'includes/_create_price_list_and_role.php' ?>
                        </div>
                <?php
                    }else if($tab == 'import_export'){
                        ?>
                        <div class="tab-pane show active" id="import_export">
                            <?php include 'includes/_import_export.php' ?>
                        </div>
                        <?php
                    }
                ?>
            </div> <!-- END div.wrpl_container_tab -->
        </div> <!-- END div#wrpl_tabs -->


    </div>
</div>

