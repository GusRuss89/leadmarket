<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');


/**
 * Shortcode: [leadmarket-form]
 * Outputs the leadmarket lead-gen form
 */
function leadmarket_form() {
    $templates = new LM_Template_Loader;
	
	ob_start();
		
    $templates->get_template_part( 'lead_form' );

	$return = ob_get_contents();
	ob_end_clean();

	return $return;
}
add_shortcode( 'leadmarket-form', 'leadmarket_form' );


/**
 * Shortcode: [leadmarket-leads]
 * Outputs the leadmarket leads
 */
function leadmarket_leads() {

    $templates = new LM_Template_Loader;
    $users = LM_Users::get_instance();
	
	ob_start();
		
    if( $users->user_can_view_leads() ) {
        $templates->get_template_part( 'loop', 'leads' );
    } else {
        $templates->get_template_part( 'access_denied' );
    }

	$return = ob_get_contents();
	ob_end_clean();

	return $return;
}
add_shortcode( 'leadmarket-leads', 'leadmarket_leads' );