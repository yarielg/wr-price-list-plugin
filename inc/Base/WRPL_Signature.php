<?php


namespace Wrpl\Inc\Base;


class WRPL_Signature
{
    public function register(){

    }

    public function verify_purchase($purchase_code,$action){
        $data = array(
            'action' => $action,
            'site' => esc_url( network_site_url() ),
            'email' => get_option( 'admin_email' ),
            'ip' => $this->get_ip(),
            'purchase_code' => $purchase_code
        );

        $response = wp_remote_post(
            'https://webreadynow.com/wp-json/wreintegration/v1/plugin_activate',
            array(
                'body' => array(
                    'data' => $data
                )
            )
        );
        if ( !is_wp_error( $response ) ) {
            return @json_decode( wp_remote_retrieve_body($response), true );
        } else {
            return $response->get_error_message();
        }
    }

    private function get_ip() {
        $ip = getenv("REMOTE_ADDR");

        if ( !in_array($ip, array('localhost', '127.0.0.1', '::1')) ) {
            return $ip;
        } else {
            $response = wp_remote_get('https://api.ipify.org/');

            if ( is_array( $response ) && ! is_wp_error( $response ) ) {
                return $response['body'];
            } else {
                return $ip;
            }
        }
    }

    public function get_license() {
        return get_option('wrpl_plugin_license', '', true);
    }

    public function is_valid() {
        $license = $this->get_license();

        if ( !empty($license) and strlen($license) == 22 ) {
            return true;
        }

        return false;
    }

    public function save_license($purchase_code) {
        if ( isset($purchase_code) && !empty($purchase_code) && strlen($purchase_code) == 22 ) {
            $response = json_decode($this->verify_purchase($purchase_code, 'activate'));
            if ( isset($response->status) && !empty($response->status) && $response->status == 'success' ) {
                update_option('wrpl_plugin_license', $purchase_code, 'yes');

                return array(
                    'status' => 'success',
                    'message' => __("Valid purchase code!", 'wr_price_list'),
                    'response' => $response
                );
            }else{
                return array(
                    'status' => 'error',
                    'message' => __("Invalid purchase code!, " . $response->msg, 'wr_price_list'),
                    'response'=> $response
                );
            }


        }

           return array(
                'status' => 'error',
                'message' => __("Invalid purchase code!", 'wr_price_list'),
            );

    }

    public function remove_license($purchase_code) {
        if ( isset($purchase_code) && !empty($purchase_code) and strlen($purchase_code) == 22 ) {
            $response = json_decode($this->verify_purchase($purchase_code, 'deactivate'));

            if ( isset($response->status) and !empty($response->status) and $response->status == 'success' ) {
                update_option('wrpl_plugin_license', '', 'yes');
                return array(
                    'status' => 'success',
                    'message' => __("This license was successfully deactivated", 'wr_price_list'),
                    'response'=> $response
                );

            }else{
                return array(
                    'status' => 'error',
                    'message' => __("The license was not removed.", 'wr_price_list'),
                    'response'=> $response
                );
            }

        }

           return array(
                'status' => 'error',
                'message' => __("Error trying to deactivated license, Invalid purchase code!", 'wr_price_list')
            );

    }
}