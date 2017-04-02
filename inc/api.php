<?php

/**
 * ==============================================
 * Publicly available functions for use in themes
 * ==============================================
 */

/**
 * Get a template part using the heirarchy of
 * child-theme > parent theme > plugin templates folder
 */
function lm_get_template_part( $str1, $str2 = '' ) {
    $templates = new LM_Template_Loader;
    if( $str2 ) {
        $templates->get_template_part( $str1, $str2 );
    } else {
        $templates->get_template_part( $str1 );
    }
}

/**
 * Get the link to buy a lead
 */
function lm_get_buy_lead_link( $lead_id ) {
    return add_query_arg( array(
        'purchase' => '',
        'lead_id'  => $lead_id
    ) );
}