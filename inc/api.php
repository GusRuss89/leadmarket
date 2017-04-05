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

/**
 * Gets the lead data for a particular lead
 */
function lm_get_lead_data( $lead_id = 0 ) {
    global $lm_leadgen_form;

    $id = $lead_id === 0 ? get_the_ID() : $lead_id;
    $form = get_post_meta( $id, 'form', true ); // For now we know which form this will be
    $form = $lm_leadgen_form;
    $details = array();
    foreach( $form['fields'] as $field_id => $field ) {
        if( $field_id !== 'post-content' ) {
            array_push( $details, array(
                'label'     => $field['label'],
                'value'     => get_post_meta( $id, $field_id, true ),
                'sensitive' => $field['sensitive']
            ) );
        }
    }

    return $details;
}

/**
 * Get the lead price for a particular lead
 */
function lm_get_lead_price( $format = true ) {
    global $lm_lead_price;
    if( $format ) {
        return lm_format_money( $lm_lead_price );
    } else {
        return $lm_lead_price;
    }
}

/**
 * Echo the html for a detail list
 * @param $arr array of label-value pairs
 * @param $show (string) all|open|sensitive
 * @param $twocol bool
 */
function lm_do_detail_list( $arr, $show = 'all', $twocol = false ) {
    $class = 'lm-uldl';
    if( $twocol ) {
        $class .= ' lm-uldl__twocol';
    }
    ?>
    <ul class="<?php echo $class; ?>">
        <?php foreach( $arr as $detail ) : ?>
            <?php if( is_array( $detail['value'] ) ) : ?>
                <?php $detail['value'] = implode( ', ', $detail['value'] ); ?>
            <?php endif; ?>
            <?php if( trim( $detail['value'] ) !== '' && $detail['label'] !== 'post-content' ) : ?>
                <?php if( 
                    $show === 'all' ||
                    ( $show === 'sensitive' && $detail['sensitive'] ) ||
                    ( $show === 'open' && !$detail['sensitive'] )
                ) : ?>
                    <li>
                        <dl>
                            <dt><?php echo esc_html( $detail['label'] ); ?>:</dt>
                            <?php if( $detail['sensitive'] ) : ?>
                                <dd><?php echo do_shortcode( '[leadmarket-sensitive]' . esc_html( $detail['value'] ) . '[/leadmarket-sensitive]' ); ?></dd>
                            <?php else : ?>
                                <dd><?php echo esc_html( $detail['value'] ); ?></dd>
                            <?php endif; ?>
                        </dl>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <?php
}