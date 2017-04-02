<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

/**
 * Class: LM_Purchases (Singleton)
 */
class LM_Purchases {


    /**
     * Instance
     */
	private static $instance = null;


    /**
     * Get instance
     */
    public static function get_instance() {
 
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
 
        return self::$instance;
 
    }
    

    /**
     * Constructor
     */
    private function __construct() {

        add_action( 'init', array( $this, 'purchase_lead' ) );

	}


    /**
     * Add a purchase and update user meta list of available leads
     */
    public function purchase_lead() {

        // Bail early
        if( ! isset( $_GET['purchase'] ) || ! isset( $_GET['lead_id'] ) )
            return;

        // Make sure user is logged in
        // To do: redirect to the original url after login
        if( ! is_user_logged_in() ) {
            wp_redirect( get_page_link( LM_LOGIN_PAGE ) );
            exit;
        }

        // Make sure permissions are ok
        $users = LM_Users::get_instance();
        if( ! $users->user_can_create_purchase() ) {
            wp_redirect( get_page_link( LM_ACCESS_DENIED_PAGE ) );
            exit;
        }

        // Do the stuff!
        $lead = get_post( $_GET['lead_id'] );
        if( ! is_object( $lead ) )
            return; // Something went wrong
        $lead_id = $lead->ID;
        $lead_title = $lead->post_title;
        $lead_price = 8.99; // Temporary obv
        $user = wp_get_current_user();
        $user_id = $user->ID;
        $user_name = $user->display_name;

        $post_arr = array(
            'post_title'     => $lead_title . ' - ' . $lead_price,
            'post_content'   => '',
            'post_status'    => 'publish',
            'post_type'      => 'lm_purchase',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'meta_input'     => array(
                'lead_id'    => $lead_id,
                'lead_title' => $lead_title,
                'price'      => $lead_price,
                'user_id'    => $user_id,
                'user_name'  => $user_name
            )
        );

        // Insert the post
        $purchase_id = wp_insert_post( $post_arr );

        // Update the user meta
        $user_available_leads = get_user_meta( $user_id, 'available_leads', true );
        if( !is_array( $user_available_leads ) ) {
            $user_available_leads = array();
        }
        array_push( $user_available_leads, $lead_id );
        update_user_meta( $user_id, 'available_leads', $user_available_leads );

        // Redirect to the single lead page
        wp_redirect( get_permalink( $lead_id ) );
        exit;

    }
    
}

// Finally initialize code
LM_Purchases::get_instance();
