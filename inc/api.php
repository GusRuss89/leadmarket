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

/**
 * Check if a client has access to a particular lead
 */
function lm_client_has_purchased_lead( $lead_id, $user_id = 0 ) {
    $user_id = $user_id === 0 ? get_current_user_id() : $user_id;
    $user_leads = get_user_meta( $user_id, 'available_leads', true );
    if( is_array( $user_leads ) ) {
        return in_array( $lead_id, $user_leads );
    } else {
        return false;
    }
}

/**
 * Check if current user can view sensitive details of a given lead
 */
function lm_user_can_view_sensitive_lead( $lead_id ) {
    return lm_client_has_purchased_lead( $lead_id, get_current_user_id() );
}

/**
 * Gets the client's balance for the current month
 */
function lm_get_client_balance( $format = true ) {
    $users = LM_Users::get_instance();
    $balance = $users->get_client_balance();
    if( $format ) {
        return lm_format_money( $balance );
    } else {
        return $balance;
    }
}