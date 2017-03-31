<?php

/**
 *
 * @link              https://profiles.wordpress.org/gusruss89/
 * @since             1.0.0
 * @package           leadmarket
 *
 * @wordpress-plugin
 * Plugin Name:       LeadMarket
 * Plugin URI:        
 * Description:       Collect leads and sell them to your clients
 * Version:           1.0.0
 * Author:            Angus Russell
 * Author URI:        https://profiles.wordpress.org/gusruss89/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       leadmarket
 * Domain Path:       /languages
 */

// don't load directly
if (!defined('ABSPATH')) die('-1');


/**
 * We'll temporarily define our form here
 */
global $lm_leadgen_form;
global $lm_field_defaults;
$lm_field_defaults = array(
    'label'       => '',
    'description' => '',
    'class'       => '',
    'type'        => 'text',
    'value'       => '',
    'required'    => false,
    'error'       => false,
    'error_msg'   => '',
    'attributes'  => '',
    'placeholder' => ''
);
$lm_leadgen_form = array(
    'id'          => 'lead-gen-form',
    'redirect_to' => 2,
    'fields'      => array(
        'post-content' => array(
            'label' => 'Any other details',
            'type'  => 'textarea'
        ) + $lm_field_defaults,
        'lead-name' => array(
            'label'    => 'Your name',
            'required' => true
        ) + $lm_field_defaults,
        'lead-email' => array(
            'label'    => 'Email address',
            'type'     => 'email',
            'required' => true
        ) + $lm_field_defaults,
        'lead-company-name' => array(
            'label'    => 'Company name',
            'required' => true
        ) + $lm_field_defaults,
        'lead-phone-number' => array(
            'label'    => 'Phone number',
            'type'     => 'tel',
            'required' => true
        ) + $lm_field_defaults,
        'lead-street-address' => array(
            'label'    => 'Street address',
            'type'     => 'text',
            'required' => true
        ) + $lm_field_defaults,
        'lead-address-l2' => array(
            'label'    => 'Address line 2',
            'type'     => 'text',
        ) + $lm_field_defaults,
        'lead-suburb' => array(
            'label'    => 'Suburb',
            'type'     => 'text',
            'required' => true
        ) + $lm_field_defaults,
        'lead-state' => array(
            'label'    => 'State',
            'type'     => 'select',
            'options'  => array(
                'ACT' => 'ACT',
                'NSW' => 'NSW',
                'NT'  => 'NT',
                'QLD' => 'QLD',
                'SA'  => 'SA',
                'TAS' => 'TAS',
                'VIC' => 'VIC',
                'WA'  => 'WA'
            ),
            'required' => true
        ) + $lm_field_defaults,
        'lead-postcode' => array(
            'label'      => 'Post code',
            'type'       => 'number',
            'attributes' => 'minlength="4" maxlength="4"',
            'required'   => true
        ) + $lm_field_defaults,
    )
);


/**
 * Require files
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/helper-functions.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/post-types.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/class-form.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/class-emails.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/process-entry.php';
require_once plugin_dir_path( __FILE__ ) . 'frontend/shortcodes.php';