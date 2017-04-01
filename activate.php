<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class LM_Update {
    
    function __construct() {
		
		add_action( 'admin_init', array( $this, 'upgrade_routine' ) );

	}


    /**
     * Update routine - runs if the plugin version has changed
     */
    public function upgrade_routine() {

        if( LM_PLUGIN_VER !== get_option('leadmarket_options[plugin_ver]') ) {
            
            // Update the version stored in options
            update_option('cf7md_options[plugin_ver]', LM_PLUGIN_VER );

            // Create the client role and give admins full capabilities
            $users = LM_Users::get_instance();
            $users->create_client_role();
            $users->update_admin_caps();

        }

    }
    
    
}

new LM_Update();