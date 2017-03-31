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

	}


    /**
     * Create a new user role
     */
    public function create_client_role() {
        add_role( 'lm_client', 'LeadMarket Client', array(
            'read',
            'lm_view_leads'
        ) );
    }


    /**
     * Check if user is a client
     */
    public function lm_is_client() {

    }



    
}

// Finally initialize code
LM_Email::get_instance();
