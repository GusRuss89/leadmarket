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
 * Set constants
 */
define( 'LM_PLUGIN_VER', '0.0.7' );
define( 'LM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


/**
 * Temporary constants (should be mostly turned into options)
 */
define( 'LM_LEADS_PAGE', 35 );
define( 'LM_LOGIN_PAGE', 37 );
define( 'LM_ACCESS_DENIED_PAGE', 41 );
global $lm_protected_pages;
$lm_protected_pages = array( LM_LEADS_PAGE );
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
    'placeholder' => '',
    'sensitive'   => false
);
$lm_leadgen_form = array(
    'id'          => 'lead-gen-form',
    'redirect_to' => 2,
    'fields'      => array(
        'waste-types' => array(
            'label'       => 'Rubbish type',
            'description' => 'What types of waste do you need collected? Select all that apply.',
            'type'        => 'checkbox',
            'required'    => true,
            'options'     => array(
                'General waste' => 'General waste',
                'Cardboard'     => 'Cardboard',
                'Recycling'     => 'Recycling'
            )
        ) + $lm_field_defaults,
        'waste-amount' => array(
            'label'       => 'Estimated amount of waste per week (litres)',
            'description' => 'How many litres of waste do you think you\'ll need removed per week? As a guide, a council wheelie bin holds 240L.',
            'type'        => 'number',
            'required'    => true
        ) + $lm_field_defaults,
        'waste-collection-schedule' => array(
            'label'       => 'Collection schedule',
            'description' => 'How many times per week would you like your bins emptied? Any particular days?',
            'required'    => true
        ) + $lm_field_defaults,
        'post-content' => array(
            'label' => 'Any other details',
            'type'  => 'textarea'
        ) + $lm_field_defaults,
        'lead-name' => array(
            'label'    => 'Your name',
            'required' => true
        ) + $lm_field_defaults,
        'lead-email' => array(
            'label'     => 'Email address',
            'type'      => 'email',
            'required'  => true,
            'sensitive' => true
        ) + $lm_field_defaults,
        'lead-company-name' => array(
            'label'    => 'Company name',
            'required' => true,
            'sensitive' => true
        ) + $lm_field_defaults,
        'lead-phone-number' => array(
            'label'    => 'Phone number',
            'type'     => 'tel',
            'required' => true,
            'sensitive' => true
        ) + $lm_field_defaults,
        'lead-street-address' => array(
            'label'    => 'Street address',
            'type'     => 'text',
            'required' => true,
            'sensitive' => true
        ) + $lm_field_defaults,
        'lead-address-l2' => array(
            'label'    => 'Address line 2',
            'type'     => 'text',
            'sensitive' => true
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
require_once plugin_dir_path( __FILE__ ) . 'inc/class-gamajo-template-loader.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/class-template-loader.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/class-form.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/class-emails.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/class-users.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/class-purchases.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/process-entry.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/shortcodes.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/api.php';
require_once plugin_dir_path( __FILE__ ) . 'activate.php';