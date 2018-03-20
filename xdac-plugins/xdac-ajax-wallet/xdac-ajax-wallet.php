<?php
/*
Plugin Name: Ajax user wallet address
*/

add_action( 'wp_enqueue_scripts', 'xdac_account_balance_enqueue' );
function xdac_account_balance_enqueue() {
    wp_register_script( 'account-balance', plugins_url( '/xdac-account-balance.js', __FILE__ ) );
    wp_register_script( 'account-balance-web3', 'https://cdn.jsdelivr.net/npm/web3@0.19.0/dist/web3.js', null, null, true );
    wp_enqueue_script( 'account-balance' );
    wp_enqueue_script( 'account-balance-web3' );
    wp_localize_script('account-balance', 'ajax_var', array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajaxnonce'),
        'logged_in' => get_current_user_id(),
        'plugin_url' => plugins_url('xdac-ajax-wallet')

    ));
}

add_action( 'wp_ajax_save_wallet_address', 'save_wallet_address' );
function save_wallet_address() {
    $wallet_address = $_POST['address'];
    update_user_meta( get_current_user_id(), 'wallet_address', $wallet_address );
    echo $wallet_address;
    wp_die();
}

add_action( 'wp_ajax_get_wallet_address', 'get_wallet_address' );
function get_wallet_address() {
    $wallet_address = get_user_meta(get_current_user_id(), 'wallet_address');
    echo $wallet_address[0];
    wp_die();
}

//add columns to User panel list page
function add_user_columns($column) {
    $column['wallet_address'] = 'Wallet Address';
    return $column;
}
add_filter( 'manage_users_columns', 'add_user_columns' );

//add the data
function add_user_column_data( $val, $column_name, $user_id ) {
    $user = get_userdata($user_id);
    switch ($column_name) {
        case 'wallet_address' :
            return $user->wallet_address;
            break;
        default:
    }
    return;
}
add_filter( 'manage_users_custom_column', 'add_user_column_data', 10, 3 );

add_action('pre_user_query','xdac_extend_user_search');



function xdac_extend_user_search( $u_query ){
    // make sure that this code will be applied only for user search
    if ( $u_query->query_vars['search'] ){
        $search_query = trim( $u_query->query_vars['search'], '*' );
        if ( $_REQUEST['s'] == $search_query ){
            global $wpdb;

            // let's search by users first name
            $u_query->query_from .= " JOIN {$wpdb->usermeta} wa ON wa.user_id = {$wpdb->users}.ID AND wa.meta_key = 'wallet_address'";

            // what fields to include in the search
            $search_by = array( 'user_login', 'user_email', 'wa.meta_value');

            // apply to the query
            $u_query->query_where = 'WHERE 1=1' . $u_query->get_search_sql( $search_query, $search_by, 'both' );
        }
    }
}
