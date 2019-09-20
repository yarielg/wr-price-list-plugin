<?php
use Wrpl\Inc\Controller\PriceListController;
use Wrpl\Inc\Controller\ProductController;
use Wrpl\Inc\Base\WRPL_Signature;
$price_list_controller =  new PriceListController(); //Init all the function about price list
$product_controller =  new ProductController(); //Init all the function about price list
$signature = new WRPL_Signature();
$plists = $price_list_controller->wrpl_get_price_lists();


$roles = wrpl_roles();
$tab = 'products';
$section = '';
if(isset($_GET['tab'])){
    $tab = sanitize_title($_GET['tab']);
    if(isset($_GET['section'])){
        $section = 'category';
    }
}

?>

<br>
<div class="wrpl-page">


    <div id="modal-overlay"></div>
    <div class="wrpl_loader"></div>

    <div class="container-fluid">

        <div id="wrpl_tabs" class="container">
            <ul  class="nav nav-pills  flex-column flex-md-row">
                <li>
                    <a id="products"
                       href="<?php echo esc_url(WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=products')?>"
                       class="<?php echo ($tab == 'products' || !isset($tab)) ? 'active' : '' ?>">
                        Products
                    </a>
                </li>
                <li>
                    <a id="price_list_by_roles"
                       href="<?php echo esc_url(WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=price_list_by_roles')?>"
                       class="<?php echo $tab == 'price_list_by_roles'  ? 'active' : '' ?>">
                        Price List By
                    </a>
                </li>
                <li>
                    <a id="create_prices_list_and_role"
                       href="<?php echo esc_url(WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=create_prices_list_and_role')?>"
                       class="<?php echo $tab == 'create_prices_list_and_role'  ? 'active' : '' ?>">
                        Price Lists & Roles
                    </a>
                </li>
                <li id="li_link_import" <?php echo !$signature->is_valid() ? 'name="blofe"' : ''?> <?php echo !$signature->is_valid() ? ' style="display:none;"' : ''?>>
                    <a id="import_export"
                       href="<?php echo esc_url(WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=import_export')?>"
                       class="<?php echo $tab == 'import_export'  ? 'active' : '' ?>">
                        Import
                    </a>
                </li>
                <li>
                    <a id="settings"
                       href="<?php echo esc_url(WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=settings')?>"
                       class="<?php echo $tab == 'settings'  ? 'active' : '' ?>">
                        Settings
                    </a>
                </li>
                <li>
                    <a id="license"
                       href="<?php echo esc_url(WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=license')?>"
                       class="<?php echo $tab == 'license'  ? 'active' : '' ?>">
                        License
                    </a>
                </li>
            </ul>

            <div id="wrpl_container_tab"  class="tab-content clearfix">

                <?php
                if($tab == 'products' || !isset($tab)){
                    ?>
                    <div class="tab-pane show active" id="tab_products">
                        <div class="row">

                            <div class=" col-4">
                                <p><?php _e('Choose a Price list to start change price quickly: ','wr_price_list') ?></p>
                            </div>
                            <div class="col-8">
                                <?php include 'includes/_select_price_list.php' ?>
                            </div>
                        </div>
                        <div class="row">
                               <?php include "includes/_filters_products.php"; ?>
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
                        <?php
                        if( get_option('wrpl-assign-method') == 2){
                            include 'includes/_price_list_by_category.php';
                        }else{
                            include 'includes/_price_list_by_role.php';
                        }
                        ?>
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
                        <?php include 'includes/_import_export.php'; ?>
                    </div>
                    <?php
                }else if($tab == 'settings'){
                    ?>
                    <div class="tab-pane show active" id="settings">
                        <?php include 'includes/_settings.php'; ?>
                    </div>
                    <?php
                }else if($tab == 'license'){
                    ?>
                    <div class="tab-pane show active" id="license">
                        <?php include 'includes/_license.php'; ?>
                    </div>
                    <?php
                }else{
                    ?>
                    <div class="tab-pane show active" id="license">
                       <h3>Are you Lost?</h3>
                        <p>Please here some useful link:</p>
                        <ul>
                            <li><a id="products"
                                   href="<?php echo esc_url(WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=products')?>"
                                   class="<?php echo ($tab == 'products' || !isset($tab)) ? 'active' : '' ?>">
                                    Change prices
                                </a></li>
                            <li>
                                <a id="price_list_by_roles"
                                   href="<?php echo esc_url(WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=price_list_by_roles')?>"
                                   class="<?php echo $tab == 'price_list_by_roles'  ? 'active' : '' ?>">
                                    Assign User Role to Price List
                                </a>
                            </li>
                            <li>
                                <a id="create_prices_list_and_role"
                                   href="<?php echo esc_url(WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=create_prices_list_and_role')?>"
                                   class="<?php echo $tab == 'create_prices_list_and_role'  ? 'active' : '' ?>">
                                    Crea Price List and Roles
                                </a>
                            </li>
                            <li>
                                <a id="settings"
                                   href="<?php echo esc_url( WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=settings')?>"
                                   class="<?php echo $tab == 'settings'  ? 'active' : '' ?>">
                                    Settings
                                </a>
                            </li>
                            <li>
                        </ul>
                    </div>
                    <?php
                }
                ?>

            </div> <!-- END div.wrpl_container_tab -->
        </div> <!-- END div#wrpl_tabs -->


    </div>
    <br>
    <div class="container">
        <div class="row">
            <div class="col">
                <p>***Read the <a href="https://www.webreadynow.com/en/wr-price-list-doc/">DOC</a> and learn everything WR Price List Manager offers you.</p>
            </div>
        </div>
    </div>
</div>

