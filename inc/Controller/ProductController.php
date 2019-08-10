<?php


namespace Wrpl\Inc\Controller;
use Wrpl\Inc\Controller\PriceListController;

class ProductController{

#
    public function register(){

        //get all products
        add_action( 'wp_ajax_wrpl_get_products', array($this,'getProducts' ));

        //edit price
        add_action( 'wp_ajax_wrpl_edit_price', array($this,'editPrice' ));

        //get categories
        add_action( 'wp_ajax_wrpl_get_categories', array($this,'getProductParentCategories' ));
    }

    function getAllProducts(){
        global $wpdb;
        $products = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts  LEFT JOIN $wpdb->prefix" . "postmeta ON ID=post_id WHERE post_type IN (%s,%s) AND post_status NOT IN (%s,%s) AND meta_key = %s", 'product','product_variation','auto-draft','trash','_sku')
        );
        return $products;
    }

    function getProducts(){
        global $wpdb;
        $price_list = $_POST['price_list'];
        $start = $_POST['start'];
        $length = $_POST['length'];
        $search = trim($_POST['search']['value']);

        $all_products = $this->getAllProducts();
        $count_products = count($all_products);

        $products_with_search  = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "posts  LEFT JOIN $wpdb->prefix" . "postmeta ON ID=post_id WHERE post_type IN ('product_variation','product') AND post_status NOT IN ('auto-draft','trash') AND meta_key = '_sku' AND ( post_title LIKE '%$search%' OR meta_value LIKE '%$search%') ORDER BY post_id LIMIT $start,$length");

        if($search == ""){
            $count_products_with_search = $count_products;
        }else{
            $count_products_with_search = count($products_with_search);
        }
        $products = stdToArray($products_with_search);
        $products_data = array();
        foreach ($products as $product){
            if(! $this->isProductHasVariations($product['ID'])){ //not including products with variations
                $image_values = wp_get_attachment_image_src( get_post_thumbnail_id($product['ID']), 'single-post-thumbnail' );
                $product['image'] = $image_values[0];
                $product['price'] = $this->getRegularPrice($product['ID'],$price_list);
                $product['sku'] = $product['meta_value'];
                $product['sale_price'] = $this->getSalesPrice($product['ID'],$price_list);
                if($product['post_type'] == 'product_variation'){
                    $product['edit_url'] = WRPL_ADMIN_URL . 'post.php?post=' . $product['post_parent'] . '&action=edit';
                    $product['guid'] = get_permalink($product['post_parent']);
                }else{
                    $product['edit_url'] = WRPL_ADMIN_URL . 'post.php?post=' . $product['ID'] . '&action=edit';
                }
                array_push($products_data,$product);
            }
        }


        echo json_encode(array('data'=>$products_data,'recordsTotal'=>$count_products,'recordsFiltered'=>$count_products_with_search));
        wp_die();
    }

    function getPriceDefault($id,$price_type){
        global $wpdb;
        $products = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "postmeta WHERE post_id = %d AND meta_key = %s", $id ,$price_type)
        );
        $products = stdToArray($products);
        if(count($products)>0){
            return $products[0]['meta_value'];
        }
        return 0;
    }

    function getPriceNotDefault($id,$price_list,$price_type){
        global $wpdb;
        $products = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "wr_price_lists_price WHERE id_product = %d AND id_price_list = %d", $id ,$price_list),ARRAY_A
        );
        if(count($products)>0){
            return $products[0][$price_type];
        }
        return 0;
    }
    function getSalesPrice($id,$price_list){

        if($price_list == 'default'){
            return $this->getPriceDefault($id,'_sale_price');
        }else{
            $pricelist_controller =  new PriceListController();
            $pl_object = $pricelist_controller->wrpl_get_price_list_by_id($price_list);
            $price_list = $pl_object['id_parent'] > 0 ? $pl_object['id_parent'] : $price_list;

            if($pl_object['id_parent'] == 0){
                return $this->getPriceNotDefault($id,$price_list,'sale_price');
            }else{

                if($pl_object['id_parent'] == '1234567890'){
                    return $pl_object['factor'] >= 1 ? 0 : $this->getPriceDefault($id,'_sale_price')*$pl_object['factor'];;
                }else{
                    return $pl_object['factor'] >= 1 ? 0 : $this->getPriceNotDefault($id,$price_list,'price')*$pl_object['factor'];
                }
            }
        }
    }

    function getRegularPrice($id,$price_list){

        if($price_list == 'default'){
            return $this->getPriceDefault($id,'_regular_price');
        }else {
            $pricelist_controller = new PriceListController();
            $pl_object = $pricelist_controller->wrpl_get_price_list_by_id($price_list);
            $price_list = $pl_object['id_parent'] > 0 ? $pl_object['id_parent'] : $price_list;

            if ($pl_object['id_parent'] == 0) {
                return $this->getPriceNotDefault($id, $price_list, 'price');
            } else {

                if ($pl_object['id_parent'] == '1234567890') {
                    return $pl_object['factor'] >= 1 ? $this->getPriceDefault($id, '_regular_price') * $pl_object['factor'] : $this->getPriceDefault($id, '_regular_price') ;
                } else {
                    // var_dump($this->getPriceNotDefault($id,$price_list,'price'));
                    return $pl_object['factor'] >= 1 ? $this->getPriceNotDefault($id, $price_list, 'price') * $pl_object['factor'] : $this->getPriceNotDefault($id, $price_list, 'price') ;
                }
            }
        }
    }

    function getVariationByProductId($id){
        global $wpdb;

        $variations = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts WHERE post_type = %s AND post_parent = $id", 'product_variation')
        );
        $variations = stdToArray($variations);
        return count($variations) > 0 ? $variations : array() ;
    }

    function  isProductHasVariations($id){
        global $wpdb;

        $products = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts WHERE post_parent = %d AND post_type = %s", $id,'product_variation')
        );

        if(count(stdToArray($products))>0){
            return true;
        }
        return false;
    }
    function getImageVariationByIdVariation($id_variation){
        global $wpdb;
        $postmeta_table =  $wpdb->prefix . 'postmeta';
        $variations = $wpdb->get_results("SELECT * FROM " . $postmeta_table . " WHERE meta_key = '_thumbnail_id'  AND post_id = '$id_variation'");

        $variations = stdToArray($variations);
        $image_url = -1;
        if(count($variations)>0){
            $image_url = wp_get_attachment_image_url( $variations[0]['meta_value'], 'post-thumbnail' );
        }

        return $image_url ;
    }

    function updateOrInsertPrice($id,$price_list,$price,$sale_price){
        global $wpdb;
        $products = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "wr_price_lists_price WHERE id_price_list = %d AND id_product = %d", $price_list,$id)
        );
        $products = stdToArray($products);
        if(count($products)>0){
            $wpdb->query("UPDATE $wpdb->prefix" . "wr_price_lists_price SET price='$price',sale_price = '$sale_price' WHERE id_price_list='$price_list' AND id_product = '$id'");

        }else{
            $wpdb->query("INSERT INTO $wpdb->prefix" . "wr_price_lists_price (id_price_list, id_product, price, sale_price) VALUES ('$price_list', '$id', '$price','$sale_price')");
        }
    }

    function editPrice(){

        $post_id = $_POST['id'];
        $price = $_POST['price'];
        $sale_price = $_POST['sale_price'];
        $price_list = $_POST['price_list'];



        if($price_list == 'default'){

            if($sale_price < $price && $sale_price > 0 ){ //S<R and S>0
                update_post_meta($post_id, '_regular_price', $price);
                update_post_meta($post_id, '_price', $sale_price);
                update_post_meta($post_id, '_sale_price', $sale_price);
                $result = array('success' => 'Sale Price Updated and Regular Price Updated');
            }else{
                update_post_meta($post_id, '_regular_price', $price);
                update_post_meta($post_id, '_price', $price);
                delete_post_meta($post_id, '_sale_price');
                $result = array('success' => 'Regular Price Updated and Sale Price Deleted');
            }

        }else{
            $this->updateOrInsertPrice($post_id,$price_list,$price,$sale_price);

            $result = array('success' => 'Regular Price Updated and Sale Price Deleted');
        }
        $this->wrpl_remove_product_price_caching($post_id);
        wc_delete_product_transients( $post_id );
        clean_post_cache( $post_id );
        wp_update_post(array('ID'=> $post_id,'post_modified' => date('Y-m-d H:i:s'), 'post_modified_gmt' => date('Y-m-d H:i:s')));
        echo json_encode($result);
        wp_die();
    }

    function getMinMaxPriceVariationNoDefault($id,$price_list,$key,$factor=1){
        global $wpdb;
        $max = 0;
        $min = 1234567890;
        $variations = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "wr_price_lists_price INNER JOIN $wpdb->prefix" . "posts ON id_product = $wpdb->prefix" . "posts.ID WHERE post_parent = $id AND id_price_list = %d",$price_list),ARRAY_A
        );

        foreach($variations as $variation){
            if($variation[$key] > $max){
                $max = $variation[$key];
            }
            if($variation[$key] < $min && $variation[$key] != 0){ //la variación menor no pueder ser 0, en caso que sea 0 no se tomara para mostrar en el product page ya que existe una condicion en PriceLIst.php en el metodo wrpl_woocommerce_price_html() if(  $min_max_sale['min']>0 && $min_max_sale['min']<$min_max['min']) que si 0 o menor
                $min = $variation[$key];
            }
        }
        $min = $min === 1234567890 ? 0 : $min;
        return array('min' => $min*$factor, 'max' => $max*$factor);
    }

    function getMinMaxPriceVariationDefault($id,$price_type,$factor = 1){
        global $wpdb;
        $variations = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts INNER JOIN $wpdb->prefix" . "postmeta ON ID=post_id WHERE post_type = %s AND post_parent = $id AND meta_key = %s", 'product_variation',$price_type),ARRAY_A
        );

        $max = 0;
        $min = 1234567890;
        foreach($variations as $variation){
            if($variation['meta_value'] > $max){
                $max = $variation['meta_value'];
            }
            if($variation['meta_value'] < $min){
                $min = $variation['meta_value'];
            }
        }

        $min = $min === 1234567890 ? 0 : $min;
        return array('min' => $min*$factor, 'max' => $max*$factor);
    }


    //return max and min price variation for a specific product
    function getMinMaxPriceVariation($id,$price_list,$price_type){
        $key = $price_type == '_regular_price' ? 'price' : 'sale_price';

        if($price_list == 'default'){
            $max_min = $this->getMinMaxPriceVariationDefault($id,$price_type);
        }else{
            $pricelist_controller = new PriceListController();
            $pl_object = $pricelist_controller->wrpl_get_price_list_by_id($price_list);
            $price_list = $pl_object['id_parent'] > 0 ? $pl_object['id_parent'] : $price_list;
            if($pl_object['id_parent'] == 0){
                $max_min = $this->getMinMaxPriceVariationNoDefault($id,$price_list,$key);
            } else {

                if ($pl_object['id_parent'] == '1234567890') {
                    $max_min = $this->getMinMaxPriceVariationDefault($id,'_regular_price',$pl_object['factor']);
                } else {
                    // var_dump($this->getPriceNotDefault($id,$price_list,'price'));
                    $max_min = $this->getMinMaxPriceVariationNoDefault($id,$price_list,'price',$pl_object['factor']);
                }
            }
        }
        return $max_min;
    }

    function wrpl_remove_product_price_caching($post_id){
        global $wpdb;

        $wpdb->query('DELETE FROM ' .$wpdb->prefix . 'options WHERE option_name LIKE "%_transient_timeout_wc_var_prices_' . $post_id . '%"');
        $wpdb->query('DELETE FROM ' .$wpdb->prefix . 'options WHERE option_name LIKE "%_transient_wc_var_prices_' . $post_id . '%"');

    }
    function wrpl_import_products($products,$price_list){

        $results = array();
        foreach($products as $product){
            $sku = trim($product[0]);
            $product_id = wc_get_product_id_by_sku($sku);
            if($product_id > 0){ // if the sku exists
                $regular_price = floatval($product[1]);
                $sale_price = floatval($product[2]);

                if($price_list == 'default'){

                    if($sale_price < $regular_price && $regular_price > 0 ){
                        array_push($results, array('type' => 'success','msg' => 'The product with sku: ' . $sku . ' was updated' ));
                        update_post_meta($product_id, '_regular_price', $regular_price);
                        update_post_meta($product_id, '_price', $sale_price);
                        update_post_meta($product_id, '_sale_price', $sale_price);
                    }else{
                        array_push($results, array('type' => 'failure','msg' => 'The product with sku: ' . $sku . ' was no inserted, you must follow this rule: regular > sale and regular > 0'));
                    }

                }else{
                    if($sale_price < $regular_price && $regular_price > 0 ){
                        $this->updateOrInsertPrice($product_id,$price_list,$regular_price,$sale_price);
                        array_push($results, array('type' => 'success','msg' => 'The product with sku: ' . $sku . ' was updated' ));
                    }else {
                        array_push($results, array('type' => 'failure','msg' => 'The product with sku: ' . $sku . ' was no inserted, you must follow this rule: regular > sale and regular > 0'));
                    }
                }

            } else{
                array_push($results, array('type' => 'failure','msg' => 'The product with sku: ' . $sku . ' was not found' ));
            }

        }//endforeach
        return $results;
    }
    function hasChildren($id){
        $orderby = 'name';
        $order = 'asc';
        $hide_empty = false ;
        $cat_args = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
            'parent' => $id
        );
        if(count(stdToArray(get_terms( 'product_cat', $cat_args )))>0){
            return true;
        }

        return false;
    }
    function getProductChildCategories($id){
        $orderby = 'name';
        $order = 'asc';
        $hide_empty = false ;
        $cat_args = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
            'parent' => $id
        );

        $product_categories = get_terms( 'product_cat', $cat_args );
        $product_categories = stdToArray($product_categories);
        $categories = array();
        foreach ($product_categories as $category){
            $category['text'] = $category['name'];
            if($this->hasChildren($category['term_id'])){
                $category['nodes'] = $this->getProductChildCategories($category['term_id']);
            }
            // $category['nodes'] = $this->getProductChildCategories($category['term_id']);
            array_push($categories,$category);
        }
        return $categories;
    }

    function getProductParentCategories(){
        $orderby = 'name';
        $order = 'asc';
        $hide_empty = false ;
        $cat_args = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
            'parent' => 0
        );

        $product_categories = get_terms( 'product_cat', $cat_args );
        $product_categories =  stdToArray($product_categories);
        $categories = array();
        foreach ($product_categories as $category){
            $category['text'] = $category['name'];
            if($this->hasChildren($category['term_id'])){
                $category['nodes'] = $this->getProductChildCategories($category['term_id']);
            }
            array_push($categories,$category);
        }


        if( !empty($product_categories) ){
            echo json_encode($categories);
        }
        else{
            echo json_encode(array('error' => 'There are not categories'));
        }
        wp_die();
    }
}