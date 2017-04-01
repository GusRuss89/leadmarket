<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

/**
 * Class: LM_Users (Singleton)
 */
class LM_Users {


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

        add_action( 'init', array( $this, 'maybe_redirect_to_login' ) );

	}


    /**
     * Create a new user role
     */
    public function create_client_role() {
        add_role( 'lm_client', 'LeadMarket Client', array(
            'read' => true,
            'lm_view_leads' => true
        ) );
    }


    /**
     * Give admins 'lm_view_leads' capability
     */
    public function update_admin_caps() {
        $role = get_role( 'administrator' );
        $role->add_cap( 'lm_view_leads' ); 
    }


    /**
     * Check if user is a client
     */
    public function user_is_client() {
        return true;
    }


    /**
     * Check if user can view leads
     */
    public function user_can_view_leads() {
        return true;
    }


    /**
     * Redirect to login page if incorrect caps
     */
    public function maybe_redirect_to_login() {
        if( is_user_logged_in() )
            return;

        global $lm_protected_pages;
        if( in_array( get_the_ID(), $lm_protected_pages ) ) {
            wp_redirect( get_page_link( LM_LOGIN_PAGE ) );
            exit;
        }
    }


}

// Finally initialize code
LM_Users::get_instance();
