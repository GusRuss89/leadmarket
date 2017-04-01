<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');


/**
 * Shortcode: [leadmarket-form]
 * Outputs the leadmarket lead-gen form
 */
function leadmarket_form() {

    global $lm_leadgen_form;
    $form = LM_Form::get_instance();
	
	ob_start();
		
    $form->the_form_opening_tag( $lm_leadgen_form['id'] );

        foreach( $lm_leadgen_form['fields'] as $key => $field ) {
            $form->the_field( $key );
        }

        $form->the_submit_button( 'Get Quotes' );

    $form->the_form_closing_tag();

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

    if( !$users->user_can_view_leads() ) {
        $templates->get_template_part( 'access_denied' );
        return;
    }
	
	ob_start();
		
    $templates->get_template_part( 'loop', 'leads' );

	$return = ob_get_contents();
	ob_end_clean();

	return $return;
}
add_shortcode( 'leadmarket-leads', 'leadmarket_leads' );