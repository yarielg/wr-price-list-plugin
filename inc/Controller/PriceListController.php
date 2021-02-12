<?php


namespace Wrpl\Inc\Controller;


class PriceListController
{
    public function register(){

        add_action( 'wp_ajax_wrpl_updated_rule_order', array($this,'wrpl_change_rules_order' ));
        add_action( 'wp_ajax_wrpl_delete_rule', array($this,'wrpl_delete_rule' ));

    }

    /**
     * @param $id
     * @return bool
     * Get price list name by Id
     */
    function wrpl_get_price_list_name_by_id($id){
        global $wpdb;
        $plists = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "wr_price_lists WHERE id = '$id'",ARRAY_A );
        if(count($plists)>0){
            return $plists[0]['description'];
        }else{
            return false;
        }
    }

    /**
     * @param bool $all_pl
     * @return mixed
     * Get all price in associative array
     */
    function wrpl_get_price_lists($all_pl = true){
        global $wpdb;
        if($all_pl){
            $plists = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "wr_price_lists ORDER BY id",ARRAY_A );
        }else{
            $plists = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "wr_price_lists WHERE id_parent =0 ORDER BY id",ARRAY_A );
        }

        return $plists;
    }

    /***
     * @param $roles
     * Save Price list Roles
     */
    function wrpl_save_price_list_role($roles){

        foreach ($roles as $role){
                $price_list = sanitize_title_with_dashes($_POST[wrpl_valid_name($role['name'])]);
            $role = wrpl_valid_name($role['name']);
            update_option('wrpl-' . $role,$price_list);
        }
    }

    /**
     * @return int
     * @notes relation between price list and role are in options table of wp (wrpl-name-of-role,price_list_value)
     */
    function wrpl_get_user_price_list() //
    {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $roles = ( array )$user->roles;

            return ( $this->wrpl_exist_price_list_id(get_option('wrpl-'.$roles[0]))) ? get_option('wrpl-'.$roles[0]) : 1; //si no se encuentra una lista asociada se devuelve Default Woocommerce
        }else{
            return get_option('wrpl-default_list');
        }
    }

    /**
     * @param $name
     * @param int $id
     * @return bool
     * Check if exist a price list with name = $name
     */
    function wrpl_exist_price_list_name($name,$id=0){
        global $wpdb;

        $name = strtolower($name);
        $plists = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "wr_price_lists WHERE LOWER(description) = '$name' AND id!='$id'");
        if(count($plists)>0){
            return true;
        }else{
            return false;

        }
    }

    /**
     * @param $id
     * @return bool
     * Check if exist the price list
     */
    function wrpl_exist_price_list_id($id){
        global $wpdb;

        $plists = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "wr_price_lists WHERE id='$id'");
        if(count($plists)>0){
            return true;
        }else{
            return false;

        }
    }

    /**
     * @param $name
     * @param int $plist
     * @param string $factor
     * @param $blofe
     * @return array
     * Add a price list
     */
    function wrpl_add_price_list($name,$plist = 0, $factor = '',$blofe){
        global  $wpdb;

        $pls_blofe = count($this->wrpl_get_price_lists());
        $name = preg_replace('/\s+/', ' ',$name); //removing extra spacing
        if(!$this->wrpl_exist_price_list_name($name)){
            if(!$blofe && $pls_blofe > 2.365 ){
                return array('status' => 'error','type' => 1);
            }else{
                $wpdb->query("INSERT INTO $wpdb->prefix" . "wr_price_lists (description,id_parent,factor) VALUES ('$name','$plist','$factor')");
                return array('status' => 'success','type' => 2,'id' => $wpdb->insert_id);
            }

        }else{
            return array('status' => 'error','type' => 2);
        }
    }

    /***
     * @param $id
     * @return int
     *
     */
    function wrpl_get_price_list_by_id($id){
        global $wpdb;
        $plists = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "wr_price_lists WHERE id = '$id'", ARRAY_A);
        $plist = -1;
        if(count($plists)>0){
            $plist = $plists[0];
        }
        return $plist;
    }

    /**
     * @param $id
     * @return mixed
     * Remove price list
     */
    function wrpl_remove_price_list($id){
        global $wpdb;
        $wpdb->get_results("DELETE FROM $wpdb->prefix" . "wr_price_lists WHERE id = '$id' OR id_parent='$id'");
        $wpdb->get_results("UPDATE $wpdb->prefix" . "options SET option_value = 1 WHERE option_value = '$id'");
        $result = $wpdb->get_results("DELETE FROM $wpdb->prefix" . "wr_price_lists_price WHERE id_price_list = '$id'");
        return $result;
    }

    function wrpl_edit_price_list($name,$id,$factor){
        global $wpdb;
        if(!$this->wrpl_exist_price_list_name($name,$id)){
            $wpdb->query("UPDATE $wpdb->prefix" . "wr_price_lists SET description='$name',factor='$factor' WHERE id='$id'");
            return true;
        }
        return false;
    }

    /**
     * @param $name
     * @return bool
     * Check if the role exists
     */
    function wr_exist_role($name){
        global $wp_roles;
        $roles = $wp_roles->roles;
        if(in_array($name,$roles)){
            return true;
        }else{
            return false;
        }
    }
    function wrpl_add_role($name){
        if(!$this->wr_exist_role($name)){
            $result = add_role(
                wrpl_valid_name($name),
                __( $name ),
                array(
                    'read' => true,  // true allows this capability
                )
            );
            if ( null !== $result ) {
                update_option('wrpl_role-'.wrpl_valid_name($name),$name); //specifying that this role was created by WRPL
                return true;
            }
            else {
                return false;
            }
        }else return false;
    }

    function wrpl_remove_role($name){
        wp_roles()->remove_role( wrpl_valid_name($name) ); //Removing  wp role
        delete_option('wrpl_role-' . wrpl_valid_name($name)); //Removing wrpl role option
        delete_option('wrpl-' . wrpl_valid_name($name)); //removing relation role-price list/
    }

    /**
     * @param $role_name
     * @param $role_name_old
     * @return bool|mixed
     * Change the role name
     */
    function wrpl_edit_role($role_name,$role_name_old){

        global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "options WHERE option_name = '".$wpdb->prefix."user_roles'");
        $unserialize_roles = unserialize(wrpl_stdToArray($result)[0]['option_value']); //unserialize the array roles
        $role = $unserialize_roles[wrpl_valid_name($role_name_old)]; //get the old role to replace it
        $capabilities = $role['capabilities']; //copy the old role capabilities


        if(!array_key_exists(wrpl_valid_name($role_name),$unserialize_roles)){
            unset($unserialize_roles[wrpl_valid_name($role_name_old)]); //removing the role to the roles array
            $unserialize_roles[wrpl_valid_name($role_name)] = array('name'=>$role_name,'capabilities' => $capabilities); //changing key name to  the new role

            $serialized_roles = serialize($unserialize_roles);
            $result = $wpdb->query("UPDATE $wpdb->prefix" . "options SET option_value = '$serialized_roles'  WHERE option_name = '".$wpdb->prefix."user_roles'");
            if($result>0 ){ //if exist a connection between the old role and any price list 1-remove it and add the new one

                $price_list = get_option('wrpl-' . wrpl_valid_name($role_name_old)) ?: 'default'; //get the price list of the old role
                delete_option('wrpl-' . wrpl_valid_name($role_name_old)); //delete the connection between the old role with the price list
                delete_option('wrpl_role-' . wrpl_valid_name($role_name_old)); //delete all price list option this option allow difference the role created by wrpl and the others
                update_option('wrpl-' . wrpl_valid_name($role_name),$price_list); //creating the new connection with the new role
                update_option('wrpl_role-' . wrpl_valid_name($role_name),$role_name); //this option allow have the control wich role were created by WRPL
                return $unserialize_roles;
            }
        }
        return false;

    }

    /**
     * @param $cat_id
     * @param $price_list_id
     * @return bool
     * Check if exist category rule for a price list
     */
    function wrpl_exist_rule($cat_id,$price_list_id){
        global $wpdb;

        $rules = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "wr_rules WHERE id_price_list='$price_list_id' AND id_category=$cat_id");
        if(count($rules)>0){
            return true;
        }else{
            return false;

        }
    }

    /**
     * @param $cat_id
     * @param $price_list_id
     * @return string[]
     * Create category rule for a price list
     */
    function wrpl_assign_pl_to_category($cat_id,$price_list_id){
        global $wpdb;

        if($this->wrpl_exist_rule($cat_id,$price_list_id)){
            return array( 'type'=>'danger', 'msg' => 'It already exists a rule with the same parameters');
        }else{
            $wpdb->query("INSERT INTO $wpdb->prefix" . "wr_rules (id_price_list,id_category,rule_type) VALUES ('$price_list_id','$cat_id','category')");
            if($wpdb->last_error !== '') {

                $result = array( 'type'=>'danger', 'msg' => 'Something was wrong, we could not create the rule');
                return $result;

            }else{
                return array('type' => 'success', 'msg' => 'The rule was successfully created');
            }
        }

    }

    /**
     * @return mixed
     * Get the rules
     */
    function wrpl_get_rules(){
        global $wpdb;
        $rules = $wpdb->get_results("SELECT T1.id as 'id_rule',T1.id_price_list,T1.id_category,T1.priority,T2.id_parent,T2.description FROM $wpdb->prefix" . "wr_rules T1 INNER JOIN $wpdb->prefix" . "wr_price_lists T2 ON T1.id_price_list=T2.id  ORDER BY priority", ARRAY_A);

        return $rules;
    }

    /**
     * Chnange the rules order
     */
    function wrpl_change_rules_order(){
        global $wpdb;

        $positions =  isset( $_POST['positions'] ) ? (array) $_POST['positions'] : array();
        foreach($positions as $position){
            $id = sanitize_text_field($position[0]);
            $priority = sanitize_text_field($position[1]);
            $wpdb->query("UPDATE $wpdb->prefix" . "wr_rules SET priority='$priority' WHERE id='$id'");
        }
    }

    /**
     * @param int $id
     * Delete a rule by id
     */
    function wrpl_delete_rule($id = -1){
        global $wpdb;

        if(isset($_POST['id'])){
            $id = sanitize_text_field(intval($_POST['id']));
            $wpdb->get_results("DELETE FROM $wpdb->prefix" . "wr_rules WHERE id='$id'");
        }else{
            $result = $wpdb->get_results("DELETE FROM $wpdb->prefix" . "wr_rules WHERE id_category='$id'");
        }

    }

}