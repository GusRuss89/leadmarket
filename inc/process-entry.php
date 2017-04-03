<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

add_action( 'init', 'lm_process_form_entry' );

function lm_process_form_entry() {

    global $lm_leadgen_form;

    // Bail if we don't have a submission
    if( !isset( $_POST['lm-form-submitted'] ) || $_POST['lm-form-submitted'] !== $lm_leadgen_form['id'] )
        return;

    // Set valid to true by default
    $form_is_valid = true;

    // Debug
    //lm_print_pre( $lm_leadgen_form );

    // Grab all the values ===
    foreach( $lm_leadgen_form['fields'] as $key => $field ) {
        if( isset( $_POST[$key] ) && '' !== $_POST[$key] ) {
            
            $field_value = $_POST[$key];

            // Sanitize first ===
            switch( $field['type'] ) {
                case 'checkbox':
                    foreach( $field_value as $i => $value ) {
                        $field_value[$i] = sanitize_text_field( $value );
                    }
                    break;
                case 'email':
                    $field_value = sanitize_email( $field_value );
                    break;
                case 'textarea':
                    $field_value = wp_kses_post( $field_value );
                    break;
                case 'text':
                default:
                    $field_value = sanitize_text_field( $field_value );
                    break;
            }

            // Then validate ===

            // Email addresses
            if( 'email' === $field['type'] && ! is_email( $field_value ) ) {
                $form_is_valid = false;
                $lm_leadgen_form['fields'][$key]['error'] = true;
                $lm_leadgen_form['fields'][$key]['error_msg'] = 'Please enter a valid email address';
            }

            // Numbers
            if( 'number' === $field['type'] && ! is_numeric( $field_value ) ) {
                $form_is_valid = false;
                $lm_leadgen_form['fields'][$key]['error'] = true;
                $lm_leadgen_form['fields'][$key]['error_msg'] = 'Please enter a numeric value';
            }

            // Update the value in the global form
            $lm_leadgen_form['fields'][$key]['value'] = $field_value;
        
        } else {
            
            // Un-filled required input
            if( $field['required'] ) {
                $form_is_valid = false;
                $lm_leadgen_form['fields'][$key]['error'] = true;
                $lm_leadgen_form['fields'][$key]['error_msg'] = 'This field is required';
            }

        }
    }

    // Return now if validation wasn't passed
    if( ! $form_is_valid ) {
        return;
    }

    // Debug
    //lm_print_pre( $lm_leadgen_form );

    // Create a new post from the entry ===
    $post_arr = array(
        'post_status'    => 'draft',
        'post_type'      => 'lm_lead',
        'comment_status' => 'closed',
        'ping_status'    => 'closed',
        'meta_input'     => array()
    );

    // Title
    $post_arr['post_title'] = $lm_leadgen_form['fields']['lead-name']['value'] . ' - ' . $lm_leadgen_form['fields']['lead-suburb']['value'];

    // Content
    $post_arr['post_content'] = $lm_leadgen_form['fields']['post-content']['value'];

    // Meta
    foreach( $lm_leadgen_form['fields'] as $key => $field ) {
        if( $key !== 'post_content' ) {
            $post_arr['meta_input'][$key] = $field['value'];
        }
    }

    // Add form id to meta
    $post_arr['meta_input']['form'] = sanitize_text_field( 'lm-form-submitted' );

    // Insert the post
    $lead_id = wp_insert_post( $post_arr );

    // Success
    if( $lead_id > 0 ) {

        // Redirect ===
        wp_redirect( get_permalink( $lm_leadgen_form['redirect_to'] ) );
        exit;

    } else {
        // Something went wrong
    }

}