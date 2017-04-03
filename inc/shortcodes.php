<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');


// Replace WP autop formatting
function lm_remove_wpautop( $content ) {
	$content = do_shortcode( shortcode_unautop( $content ) );
	$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content );
	return $content;
}


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
 * @param show (string) all|purchased|not-purchased
 */
function leadmarket_leads( $atts ) {
    extract( shortcode_atts( array(
		'show' => 'all'
	), $atts ) );

    $templates = new LM_Template_Loader;
    $users = LM_Users::get_instance();
	
	ob_start();
		
    if( $users->user_can_view_leads() ) {
        $templates->set_template_data( array( 'show' => $show ) );
        $templates->get_template_part( 'loop', 'leads' );
    } else {
        $templates->get_template_part( 'access_denied' );
    }

	$return = ob_get_contents();
	ob_end_clean();

	return $return;
}
add_shortcode( 'leadmarket-leads', 'leadmarket_leads' );


/**
 * Shortcode: [leadmarket-purchases]
 * Outputs the purchases for the logged-in user
 */
function leadmarket_purchases() {
    $templates = new LM_Template_Loader;
	
	ob_start();
		
    $templates->get_template_part( 'user', 'purchases' );

	$return = ob_get_contents();
	ob_end_clean();

	return $return;
}
add_shortcode( 'leadmarket-purchases', 'leadmarket_purchases' );


/**
 * Shortcode: [leadmarket-sensitive]
 * Checks if user has access to sensitive info and redacts
 * Only for use within a lead
 */
function leadmarket_sensitive( $atts, $content = null ) {
    $lead_id = get_the_ID();
    if(
        'lm_lead' === get_post_type( $lead_id ) &&
        !lm_user_can_view_sensitive_lead( $lead_id )
    ) {
        
        return 'Redacted';

    } else {
        return lm_remove_wpautop( $content );
    }

}
add_shortcode( 'leadmarket-sensitive', 'leadmarket_sensitive' );