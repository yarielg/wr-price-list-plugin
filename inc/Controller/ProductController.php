<?php

namespace Inc\Controller;

class ProductController{



    public function register(){

        //get all products
        add_action( 'wp_ajax_nopriv_get_products', array($this,'getProducts' ));
        add_action( 'wp_ajax_get_products', array($this,'getProducts' ));

        //edit price
        add_action( 'wp_ajax_nopriv_edit_price', array($this,'editPrice' ));
        add_action( 'wp_ajax_edit_price', array($this,'editPrice' ));

    }

    function getProducts(){
        global $wpdb;
        $price_list = $_POST['price_list'];
        $start = $_POST['start'];
        $length = $_POST['length'];
        $search = $_POST['search']['value'];
        $draw = $_POST['draw'];


            $products = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts  LEFT JOIN $wpdb->prefix" . "postmeta ON ID=post_id WHERE post_type IN (%s,%s) AND post_status NOT IN (%s,%s) AND meta_key = %s", 'product','product_variation','auto-draft','trash','_sku')
            );
            $products_with_search  = $wpdb->get_results(
                 $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts  LEFT JOIN $wpdb->prefix" . "postmeta ON ID=post_id WHERE post_type IN (%s,%s) AND post_status NOT IN (%s,%s) AND meta_key = %s AND meta_value LIKE '%$search%' ORDER BY post_id LIMIT %d,%d", 'product','product_variation','auto-draft','trash','_sku',$start,$length)
            );
            $count_products = count($products);
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
                    array_push($products_data,$product);
                }
            }


        echo json_encode(array('data'=>$products_data,'recordsTotal'=>$count_products,'recordsFiltered'=>$count_products_with_search,'draw'=>$draw));
        wp_die();
    }

    function getSalesPrice($id,$price_list){
        global $wpdb;

        if($price_list == 'default'){
            $products = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "postmeta WHERE post_id = %d AND meta_key = %s", $id ,'_sale_price')
            );
            $products = stdToArray($products);
            if(count($products)>0){
                return $products[0]['meta_value'];
            }
        }else{
            $products = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "wr_price_lists_price WHERE id_product = %d AND id_price_list = %d", $id ,$price_list)
            );
            $products = stdToArray($products);
            if(count($products)>0){
                return $products[0]['sale_price'];
            }
        }

        return 0;
    }

    function getRegularPrice($id,$price_list){
        global $wpdb;

        if($price_list == 'default'){
            $products = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "postmeta WHERE post_id = %d AND meta_key = %s", $id ,'_regular_price')
            );
            $products = stdToArray($products);
            if(count($products)>0){
                return $products[0]['meta_value'];
            }
        }else{
            $products = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "wr_price_lists_price WHERE id_product = %d AND id_price_list = %d", $id ,$price_list)
            );
            $products = stdToArray($products);
            if(count($products)>0){
                return $products[0]['price'];
            }
        }

        return 0;
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
        global $wpdb;

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
        echo json_encode($result);
        wp_die();
    }

    //return max and min price variation for a specific product
    function getMinMaxPriceVariation($id,$price_list,$price_type){
        global $wpdb;
        $key = $price_type == '_regular_price' ? 'price' : 'sale_price';
        $max = 0;
        $min = PHP_FLOAT_MAX;
        if($price_list>0){
            $variations = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "wr_price_lists_price INNER JOIN $wpdb->prefix" . "posts ON id_product = $wpdb->prefix" . "posts.ID WHERE post_parent = 35 AND id_price_list = %d",$price_list)
            );
            $variations = stdToArray($variations);
            foreach($variations as $variation){
                if($variation[$key] > $max){
                    $max = $variation[$key];
                }
                if($variation[$key] < $min){
                    $min = $variation[$key];
                }
            }
        }else{
            $variations = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts INNER JOIN $wpdb->prefix" . "postmeta ON ID=post_id WHERE post_type = %s AND post_parent = $id AND meta_key = %s", 'product_variation',$price_type)
            );

            $variations = stdToArray($variations);
            $max = 0;
            $min = PHP_FLOAT_MAX;
            foreach($variations as $variation){
                if($variation['meta_value'] > $max){
                    $max = $variation['meta_value'];
                }
                if($variation['meta_value'] < $min){
                    $min = $variation['meta_value'];
                }
            }
        }


        return array('min' => $min, 'max' => $max);
    }

}