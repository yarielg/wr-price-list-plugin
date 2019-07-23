<br>
<div class="wrpl-page">


    <div id="modal-overlay"></div>
    <div class="wrpl_loader"></div>

    <div class="container-fluid">

        <div id="wrpl_tabs" class="container">
            <ul  class="nav nav-pills">
                <li class="active">
                    <a  href="#tab_products" data-toggle="tab" class="active show">Products</a>
                </li>
                <li><a href="#2a" data-toggle="tab">Price Lists</a>
                </li>
                <li><a href="#3a" data-toggle="tab">Rules</a>
                </li>
                <li><a href="#4a" data-toggle="tab">Settings</a>
                </li>
            </ul>

            <div class="tab-content clearfix">
                <div class="tab-pane active" id="tab_products">
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


                <div class="tab-pane" id="2a">
                    <h3>We use the class nav-pills instead of nav-tabs which automatically creates a background color for the tab</h3>
                </div>


                <div class="tab-pane" id="3a">
                    <h3>We applied clearfix to the tab-content to rid of the gap between the tab and the content</h3>
                </div>

                <div class="tab-pane" id="4a">
                    <h3>We use css to change the background color of the content to be equal to the tab</h3>
                </div>
            </div>
        </div>


    </div>
</div>

