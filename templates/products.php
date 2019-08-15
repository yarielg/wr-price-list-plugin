<?php
use Wrpl\Inc\Controller\PriceListController;
use Wrpl\Inc\Controller\ProductController;
$price_list_controller =  new PriceListController(); //Init all the function about price list
$product_controller =  new ProductController(); //Init all the function about price list
$plists = $price_list_controller->wrpl_get_price_lists();
$roles = stdToArray(wrpl_roles());
//$categories = $product_controller->getProductParentCategories();
$tab = 'products';
$section = '';
if(isset($_GET['tab'])){
    $tab = $_GET['tab'];
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
            <ul  class="nav nav-pills">
                <li>
                    <a id="products"
                       href="<?php echo WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=products'?>"
                       class="<?php echo ($tab == 'products' || !isset($tab)) ? 'active' : '' ?>">
                        Products
                    </a>
                </li>
                <li>
                    <a id="price_list_by_roles"
                       href="<?php echo WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=price_list_by_roles'?>"
                       class="<?php echo $tab == 'price_list_by_roles'  ? 'active' : '' ?>">
                        Price List By
                    </a>
                </li>
                <li>
                    <a id="create_prices_list_and_role"
                       href="<?php echo WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=create_prices_list_and_role'?>"
                       class="<?php echo $tab == 'create_prices_list_and_role'  ? 'active' : '' ?>">
                        Price Lists & Roles
                    </a>
                </li>
                <li>
                    <a id="import_export"
                       href="<?php echo WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=import_export'?>"
                       class="<?php echo $tab == 'import_export'  ? 'active' : '' ?>">
                        Import
                    </a>
                </li>
                <li>
                    <a id="settings"
                       href="<?php echo WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=settings'?>"
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

                            <div class="col-3">
                                <p>Choose a Price list to start change price quickly: </p>
                            </div>
                            <div class="col-6">
                                <?php include 'includes/_select_price_list.php' ?>
                            </div>
                            <div class="col-3"></div>
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
                        <!--<a id="price_list_by_roles"
                           href="<?php /*echo WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=price_list_by_roles'*/?>"
                           class="<?php /*echo $section == ''  ? 'active' : '' */?>">
                            By Role
                        </a> |
                        <a id="price_list_by_roles"
                           href="<?php /*echo WRPL_ADMIN_URL . 'admin.php?page=wrpl-products-menu&tab=price_list_by_roles&section=category'*/?>"
                           class="<?php /*echo $section == 'category'  ? 'active' : '' */?>">
                            By Category
                        </a>-->
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
                }
                ?>

            </div> <!-- END div.wrpl_container_tab -->
        </div> <!-- END div#wrpl_tabs -->


    </div>
</div>

