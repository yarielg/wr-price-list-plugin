<?php


namespace Wrpl\Inc\Controller;
use Wrpl\Inc\Controller\PriceListController;

class ProductController{

    public $price_list_controller;

    function __construct()
    {
        $this->price_list_controller =  new PriceListController();
    }

    public function register(){

        //get all products
        add_action( 'wp_ajax_wrpl_get_products', array($this,'getProducts' ));

        //edit price
        add_action( 'wp_ajax_wrpl_edit_price', array($this,'editPrice' ));

        //get categories
        add_action( 'wp_ajax_wrpl_get_categories', array($this,'getProductParentCategories' ));

        //when a category is removed
        add_action('delete_product_cat', array($this,'wrpl_delete_cat'), 10,4);

    }

    /**
     * @return array
     * Get all products
     */
    function getAllProducts(){
        global $wpdb;
        $products = $wpdb->get_results(
           $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts WHERE post_type IN (%s,%s) AND post_status NOT IN (%s,%s)", 'product','product_variation','auto-draft','trash','_sku'),ARRAY_A
        );
        //remove product with parent = 0 and variations
        $final_products = array();
        foreach ($products as $product){
            if($this->isProductHasVariations($product['ID'])){
                continue;
            }
            array_push($final_products,$product);
        }
        return $final_products;
    }
    function getProducts(){
        $price_list = intval(sanitize_text_field($_POST['price_list']));
        $start = intval(sanitize_text_field($_POST['start']));
        $length = intval(sanitize_text_field($_POST['length']));
        $search = sanitize_title(trim($_POST['search']['value']));
        $category_id = intval(sanitize_text_field($_POST['category_id']));
        $all_products = $this->getAllProducts();
        $count_products =  count($all_products);

        $products_cat_search = $this->getProductByCategory($category_id,$search,$length,$start);
        $products_with_search  = $products_cat_search['data'];
        if($search == "" && $category_id == 0){
            $count_products_with_search = count($all_products);
        }else{
            //$products_with_search = $this->wrpl_filter_category_product($products_with_search,$search);

           // $count_products = $this->getProductByCategory($category_id,$search,$length,$start)['total'];
            $count_products_with_search = $products_cat_search['total'];
        }
        $products = $products_with_search;
        $products_data = array();
        foreach ($products as $product){

                $image_values = wp_get_attachment_image_src( get_post_thumbnail_id($product['ID']), 'single-post-thumbnail' );
                $product['image'] = $image_values[0];
                $product['price'] = $this->getRegularPrice($product['ID'],$price_list);
                $product['sku'] = ($this->getSku($product['ID'] ) == '') ? '<span class="badge badge-danger">NO SKU</span>' : $this->getSku($product['ID']) ;
                $product['sale_price'] = $this->getSalesPrice($product['ID'],$price_list);
                if($product['post_type'] == 'product_variation'){
                    $product['edit_url'] = WRPL_ADMIN_URL . 'post.php?post=' . $product['post_parent'] . '&action=edit';
                    $wc_prod_var_o = wc_get_product( $product['ID'] );
                    $product['guid']      = $wc_prod_var_o->get_permalink();
                    $product['categories'] = get_the_terms( $product['post_parent'], 'product_cat' ) ?  wrpl_convert_to_separate_value(get_the_terms( $product['post_parent'], 'product_cat' ),'name') : '';
                }else{
                    $product['edit_url'] = WRPL_ADMIN_URL . 'post.php?post=' . $product['ID'] . '&action=edit';
                    $product['guid'] = get_permalink($product['ID']);
                    $product['categories'] = get_the_terms( $product['ID'], 'product_cat' ) ?  wrpl_convert_to_separate_value(get_the_terms( $product['ID'], 'product_cat' ),'name') : '';
                }
                array_push($products_data,$product);
        }
        echo json_encode(array('data'=>$products_data,'recordsTotal'=>$count_products,'recordsFiltered' => $count_products_with_search));
        wp_die();
    }

    /**
     * @param $cat_id
     * @param $search
     * @param $limit
     * @param $offset
     * @return array
     * Get products by category
     */
    function getProductByCategory($cat_id,$search,$limit,$offset){
        global $wpdb;

        $products = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "posts  WHERE post_type IN ('product_variation','product') AND post_status NOT IN ('auto-draft','trash')  AND ( post_title LIKE '%$search%') ORDER BY ID",ARRAY_A);
        // $products = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "posts  LEFT JOIN $wpdb->prefix" . "postmeta ON ID=post_id WHERE post_type IN ('product_variation','product') AND post_status NOT IN ('auto-draft','trash') AND meta_key = '_sku' AND ( post_title LIKE '%$search%' OR meta_value LIKE '%$search%') ORDER BY post_id",ARRAY_A);
        $final_products = array();
        foreach ($products as $product){
            if(!$this->isProductHasVariations($product['ID'])){
                if($cat_id > 0){
                    if($product['post_type'] == 'product_variation'){
                        if( $this->wrpl_product_has_category($cat_id,$product['post_parent'])){
                            array_push($final_products,$product);
                        }
                    }else{
                        if( $this->wrpl_product_has_category($cat_id,$product['ID'])){
                            array_push($final_products,$product);
                        }
                    }
                }else{
                    array_push($final_products,$product);
                }
            }
        }
        return array('total' => count($final_products),'data' => array_slice($final_products,$offset,$limit));
    }

    /**
     * @param $cat_id
     * @param $product_id
     * @return bool
     * Check if a product belong to certain category (need improvement)
     */
    function wrpl_product_has_category($cat_id,$product_id){
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "term_relationships WHERE term_taxonomy_id='$cat_id' AND object_id='$product_id'", ARRAY_A);
        if(count($results) > 0){
            return true;
        }
        return false;
    }

    /**
     * @param $products
     * @param $search
     * @return array
     */
    function wrpl_filter_category_product($products,$search){
        $final_products = array();
        foreach ($products as $product){
            $categories = wrpl_convert_to_separate_value(get_the_terms( $product['ID'], 'product_cat' ),'name');
                if( strpos($categories,$search)  !== false ){
                array_push($final_products,$product);
            }
        }
        return $final_products;
    }

    /**
     * @param $id
     * @param $price_type
     * @return int|mixed
     * Get the default price
     */
    function getPriceDefault($id,$price_type){
        global $wpdb;
        $products = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "postmeta WHERE post_id = %d AND meta_key = %s", $id ,$price_type)
        );
        $products = wrpl_stdToArray($products);
        if(count($products)>0){
            return $products[0]['meta_value'];
        }
        return 0;
    }

    /**
     * @param $id
     * @param $price_list
     * @param $price_type
     * @return int
     */
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

        if($price_list == 1){
            return $this->getPriceDefault($id,'_sale_price');
        }else{
            $pricelist_controller =  new PriceListController();
            $pl_object = $pricelist_controller->wrpl_get_price_list_by_id($price_list);
            $price_list = $pl_object['id_parent'] > 0 ? $pl_object['id_parent'] : $price_list;

            if($pl_object['id_parent'] == 0){
                return $this->getPriceNotDefault($id,$price_list,'sale_price');
            }else{

                if($pl_object['id_parent'] == 1){
                    return $pl_object['factor'] >= 1 ? 0 : $this->getPriceDefault($id,'_regular_price') * $pl_object['factor'];;
                }else{
                    return $pl_object['factor'] >= 1 ? 0 : $this->getPriceNotDefault($id,$price_list,'price')*$pl_object['factor'];
                }
            }
        }
    }

    function getRegularPrice($id,$price_list){

        if($price_list == 1){
            return $this->getPriceDefault($id,'_regular_price');
        }else {
            $pricelist_controller = new PriceListController();
            $pl_object = $pricelist_controller->wrpl_get_price_list_by_id($price_list);
            $price_list = $pl_object['id_parent'] > 0 ? $pl_object['id_parent'] : $price_list;

            if ($pl_object['id_parent'] == 0) { //Si es una lista padre
                return $this->getPriceNotDefault($id, $price_list, 'price');//Dame el precio regular de esta lista precio
            } else { //si es una lista hija

                if ($pl_object['id_parent'] == 1) { //Si es Woocommerce Default List
                    //retornamos el precio de la lista padre por el factor
                    return $pl_object['factor'] >= 1 ? $this->getPriceDefault($id, '_regular_price') * $pl_object['factor'] : $this->getPriceDefault($id, '_regular_price') ;
                } else {
                    return $pl_object['factor'] >= 1 ? $this->getPriceNotDefault($id, $price_list, 'price') * $pl_object['factor'] : $this->getPriceNotDefault($id, $price_list, 'price') ;
                }
            }
        }
    }

    function getSku($id){
        global $wpdb;

        $products = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "postmeta WHERE post_id = %d AND meta_key = %s", $id,'_sku'),ARRAY_A
        );
        return count($products) > 0 ? $products[0]['meta_value'] : '' ;
    }

    function  isProductHasVariations($id){
        global $wpdb;

        $products = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts WHERE post_parent = %d AND post_type = %s", $id,'product_variation')
        );

        if(count(wrpl_stdToArray($products))>0){
            return true;
        }
        return false;
    }
    function getImageVariationByIdVariation($id_variation){
        global $wpdb;
        $postmeta_table =  $wpdb->prefix . 'postmeta';
        $variations = $wpdb->get_results("SELECT * FROM " . $postmeta_table . " WHERE meta_key = '_thumbnail_id'  AND post_id = '$id_variation'");

        $variations = wrpl_stdToArray($variations);
        $image_url = -1;
        if(count($variations)>0){
            $image_url = wp_get_attachment_image_url( $variations[0]['meta_value'], 'post-thumbnail' );
        }

        return $image_url ;
    }

    /**
     * @param $id
     * @param $price_list
     * @param $price
     * @param $sale_price
     * Insert or Update new price for a product belong to a certain price list
     */
    function updateOrInsertPrice($id,$price_list,$price,$sale_price){
        global $wpdb;
        $products = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "wr_price_lists_price WHERE id_price_list = %d AND id_product = %d", $price_list,$id)
        );
        $products = wrpl_stdToArray($products);
        if(count($products)>0){
            $wpdb->query("UPDATE $wpdb->prefix" . "wr_price_lists_price SET price='$price',sale_price = '$sale_price' WHERE id_price_list='$price_list' AND id_product = '$id'");

        }else{
            $wpdb->query("INSERT INTO $wpdb->prefix" . "wr_price_lists_price (id_price_list, id_product, price, sale_price) VALUES ('$price_list', '$id', '$price','$sale_price')");
        }
    }

    /**
     * Edit the product price for a price list
     */
    function editPrice(){

        $post_id = intval(sanitize_text_field($_POST['id']));
        $price = floatval(sanitize_text_field($_POST['price']));
        $sale_price = floatval(sanitize_text_field($_POST['sale_price']));
        $price_list = intval(sanitize_text_field($_POST['price_list']));

        if($price_list == 1){

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

    /**
     * @param $id
     * @param $price_list
     * @param $key
     * @param int $factor
     * @return float[]|int[]
     * Get the max and mix of a variable product that do not belong to the Default List
     */
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

    /**
     * @param $id
     * @param $price_type
     * @param int $factor
     * @return array
     * Get the max and mix of a variable product that belong to the Default List
     */
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
                $min = floatval($variation['meta_value']);
            }
        }

        $min = $min === 1234567890 ? 0 : $min;
        return array('min' => floatval($min*$factor), 'max' => floatval($max*$factor));
    }


    /**
     * @param $id
     * @param $price_list
     * @param $price_type
     * @return array|float[]|int[]
     * Get min a max of a variable product
     */
    function getMinMaxPriceVariation($id,$price_list,$price_type){
        $key = $price_type == '_regular_price' ? 'price' : 'sale_price';

        if($price_list == 1){
            $max_min = $this->getMinMaxPriceVariationDefault($id,$price_type);
        }else{
            $pricelist_controller = new PriceListController();
            $pl_object = $pricelist_controller->wrpl_get_price_list_by_id($price_list);
            $price_list = $pl_object['id_parent'] > 0 ? $pl_object['id_parent'] : $price_list;
            if($pl_object['id_parent'] == 0){
                $max_min = $this->getMinMaxPriceVariationNoDefault($id,$price_list,$key);
            } else {

                if ($pl_object['id_parent'] == 1) {
                    $max_min = $this->getMinMaxPriceVariationDefault($id,'_regular_price',$pl_object['factor']);
                } else {
                    // var_dump($this->getPriceNotDefault($id,$price_list,'price'));
                    $max_min = $this->getMinMaxPriceVariationNoDefault($id,$price_list,'price',$pl_object['factor']);
                }
            }
        }
        return $max_min;
    }

    /**
     * @param $post_id
     * Remove price transient
     */
    function wrpl_remove_product_price_caching($post_id){
        global $wpdb;

        $wpdb->query('DELETE FROM ' .$wpdb->prefix . 'options WHERE option_name LIKE "%_transient_timeout_wc_var_prices_' . $post_id . '%"');
        $wpdb->query('DELETE FROM ' .$wpdb->prefix . 'options WHERE option_name LIKE "%_transient_wc_var_prices_' . $post_id . '%"');

    }

    /**
     * @param $products
     * @param $price_list
     * @return array
     * Import products
     */
    function wrpl_import_products($products,$price_list){

        $results = array();
        foreach($products as $product){
            $sku = trim($product[0]);
            $product_id = wc_get_product_id_by_sku($sku);
            if($product_id > 0){ // if the sku exists
                $regular_price = floatval($product[1]);
                $sale_price = floatval($product[2]);

                if($price_list == 1){

                    if($sale_price < $regular_price && $regular_price > 0 ){
                        array_push($results, array('type' => 'success','msg' => __('The product with sku: ','wr_price_list') . $sku . __(' was updated','wr_price_list') ));
                        update_post_meta($product_id, '_regular_price', $regular_price);
                        update_post_meta($product_id, '_price', $sale_price);
                        update_post_meta($product_id, '_sale_price', $sale_price);
                    }else{
                        array_push($results, array('type' => 'failure','msg' => __('The product with sku: ','wr_price_list') . $sku . __(' was no inserted, you must follow this rule: regular > sale and regular > 0','wr_price_list')));
                    }

                }else{
                    if($sale_price < $regular_price && $regular_price > 0 ){
                        $this->updateOrInsertPrice($product_id,$price_list,$regular_price,$sale_price);
                        array_push($results, array('type' => 'success','msg' => __('The product with sku: ','wr_price_list') . $sku . __(' was updated','wr_price_list') ));
                    }else {
                        array_push($results, array('type' => 'failure','msg' => __('The product with sku: ','wr_price_list') . $sku . __(' was no inserted, you must follow this rule: regular > sale and regular > 0','wr_price_list')));
                    }
                }

            } else{
                array_push($results, array('type' => 'failure','msg' => __('The product with sku: ','wr_price_list') . $sku . __(' was not found','wr_price_list') ));
            }

        }//endforeach
        return $results;
    }

    /**
     * @param $id
     * @return bool
     * if the category a parent category?
     */
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
        if(count(wrpl_stdToArray(get_terms( 'product_cat', $cat_args )))>0){
            return true;
        }

        return false;
    }

    /**
     * @param $id
     * @return array
     * Get all the product that belong to child categories
     */
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
        $product_categories = wrpl_stdToArray($product_categories);
        $categories = array();
        foreach ($product_categories as $category){
            $category['text'] = $category['name'];
            $category['plist'] = get_option('wrpl_cat_' . $category['term_id']) ?: 1;
            if($this->hasChildren($category['term_id'])){
                $category['nodes'] = $this->getProductChildCategories($category['term_id']);
            }
            // $category['nodes'] = $this->getProductChildCategories($category['term_id']);
            array_push($categories,$category);
        }
        return $categories;
    }

    /**
     * Get the parent categories
     */
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
        $product_categories =  wrpl_stdToArray($product_categories);
        $categories = array();
        foreach ($product_categories as $category){
            $category['text'] = $category['name'];
            $category['plist'] = get_option('wrpl_cat_' . $category['term_id']) ?: 1;
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

    /***
     * @param $rules
     * @return mixed|null
     * Get top priority rules
     */
    function wrpl_get_rules_with_most_priority($rules){
        $priority_rule = null;
        if(count($rules)>0){
            $priority_rule = $rules[0];
            for ($i = 0; $i < count($rules); $i++){
                if($priority_rule >  $rules[$i+1]['priority']){
                    $priority_rule = $rules[$i+1];
                }
            }
        }
        return $priority_rule;
    }

    /**
     * @param $id_cat
     * @param $rules
     * @return array
     * Get rules by category id
     */
    function wrpl_get_rules_by_category_id($id_cat,$rules){
        $matched_rules = array();
        foreach ($rules as $rule){
            if($rule['id_category'] == $id_cat){
                array_push($matched_rules,$rule);
            }
        }
        return $matched_rules;
    }

    /**
     * @param $id
     * @return int|mixed
     * Get price list by category
     */
    function wrpl_get_price_list_by_category($id){
        $rules = $this->price_list_controller->wrpl_get_rules();
        $categories = array();
        if(get_the_terms( $id, 'product_cat' )){
            $categories = wrpl_stdToArray(get_the_terms( $id, 'product_cat' ));
        }
        $mached_rules =  array();
        foreach ($rules as $rule){
            foreach ($categories as $category){
                if($rule['id_category'] == $category['term_id']){

                    array_push($mached_rules,$rule);

                }
            }
        }
        if(count($mached_rules)>0){
            return $mached_rules[0]['id_price_list'];
        }else{
            return 1;
        }

    }

    /**
     * @param $term
     * @param $tt_id
     * @param $deleted_term
     * @param $object_ids
     *
     */
    function wrpl_delete_cat($term, $tt_id, $deleted_term, $object_ids){
        $this->price_list_controller->wrpl_delete_rule($term);
      //  wp_die();
    }

    /**
     * @return array
     * Get all product categories
     */
    function wrpl_get_all_product_cat(){

        $orderby = 'name';
        $order = 'asc';
        $hide_empty = false ;
        $cat_args = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
        );

        $product_categories = get_terms( 'product_cat', $cat_args );

        if( !empty($product_categories) ){

            return wrpl_stdToArray($product_categories);
        }else{
            return [];
        }
    }

}