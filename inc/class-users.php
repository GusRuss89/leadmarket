<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

/**
 * Class: LM_Users (Singleton)
 */
class LM_Users {


    /**
     * Instance
     */
	private static $instance = null;


    /**
     * Get instance
     */
    public static function get_instance() {
 
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
 
        return self::$instance;
 
    }
    

    /**
     * Constructor
     */
    private function __construct() {

        add_action( 'template_redirect', array( $this, 'maybe_redirect_to_login' ) );
        add_action( 'template_redirect', array( $this, 'redirect_portal_to_leads' ) );
        add_action( 'register_form', array( $this, 'add_registration_fields' ) );
        add_action( 'user_register', array( $this, 'update_custom_user_meta' ) );
        add_action( 'profile_update', array( $this, 'update_custom_user_meta' ) );
        add_action( 'user_register', array( $this, 'set_new_user_role' ) );
        add_action( 'show_user_profile', array( $this, 'add_profile_fields' ) );
        add_action( 'edit_user_profile', array( $this, 'add_profile_fields' ) );

        add_filter( 'wp_get_nav_menu_items', array( $this, 'exclude_protected_menu_items' ), null, 3 );
        add_filter( 'registration_errors', array( $this, 'registration_errors' ) );

	}


    /**
     * Create a new user role
     */
    public function create_client_role() {
        add_role( 'lm_client', 'LeadMarket Client', array(
            'read'               => true,
            'lm_view_leads'      => true,
            'lm_view_purchases'  => true,
            'lm_create_purchase' => true
        ) );
    }


    /**
     * Give admins 'lm_view_leads' capability
     */
    public function update_admin_caps() {
        $role = get_role( 'administrator' );
        $role->add_cap( 'lm_view_leads' );
        $role->add_cap( 'lm_view_purchases' ); 
        $role->add_cap( 'lm_create_purchase' );
    }


    /**
     * Check if user is a client
     */
    public function user_is_client() {
        return true;
    }


    /**
     * Check if user can view leads
     */
    public function user_can_view_leads() {
        return current_user_can( 'lm_view_leads' );
    }


    /**
     * Check if user can view purchases
     */
    public function user_can_view_purchases() {
        return current_user_can( 'lm_view_purchases' );
    }


    /**
     * Check if user can create purchases
     */
    public function user_can_create_purchase() {
        return current_user_can( 'lm_create_purchase' );
    }


    /**
     * Don't show protected pages in menus for logged-out users
     */
    public function exclude_protected_menu_items( $items, $menu, $args ) {
        //lm_print_pre( $items );
        if( $this->user_can_view_leads() )
            return $items;
        
        global $lm_protected_pages;
        
        //lm_print_pre( $items );

        foreach ( $items as $key => $item ) {
            if( in_array( $item->object_id, $lm_protected_pages ) || in_array( $item->post_parent, $lm_protected_pages ) ) {
                unset( $items[$key] );
            }
        }

        return $items;
    }


    /**
     * Redirect to login page if incorrect caps
     */
    public function maybe_redirect_to_login() {
        if( is_user_logged_in() )
            return;

        global $lm_protected_pages;
        if( in_array( get_the_ID(), $lm_protected_pages ) ) {
            wp_redirect( get_page_link( LM_LOGIN_PAGE ) );
            exit;
        }
    }


    /**
     * Redirect portal page to leads page
     */
    public function redirect_portal_to_leads() {
        if( LM_CLIENT_PORTAL_PAGE === get_the_ID() ) {
            wp_redirect( get_page_link( LM_LEADS_PAGE ) );
            exit;
        }
    }


    /**
     * Add fields to the registration form
     */
    public function add_registration_fields() {

        $company = ( ! empty( $_POST['client_company'] ) ) ? trim( $_POST['client_company'] ) : '';
        $sales_email = ( ! empty( $_POST['client_sales_email'] ) ) ? trim( $_POST['client_sales_email'] ) : '';
        $billing_email = ( ! empty( $_POST['client_billing_email'] ) ) ? trim( $_POST['client_billing_email'] ) : '';

        ?>
            <p>
                <label for="client_sales_email"><?php _e( 'Sales Email (receives lead notifications)', 'leadmarket' ) ?><br />
                <input type="text" name="client_sales_email" id="client_sales_email" class="input" value="<?php echo esc_attr( wp_unslash( $sales_email ) ); ?>" size="25" /></label>
            </p>
            <p>
                <label for="client_billing_email"><?php _e( 'Billing Email (receives invoices)', 'leadmarket' ) ?><br />
                <input type="text" name="client_billing_email" id="client_billing_email" class="input" value="<?php echo esc_attr( wp_unslash( $billing_email ) ); ?>" size="25" /></label>
            </p>
            <p>
                <label for="client_company"><?php _e( 'Company Name', 'leadmarket' ) ?><br />
                <input type="text" name="client_company" id="client_company" class="input" value="<?php echo esc_attr( wp_unslash( $company ) ); ?>" size="25" /></label>
            </p>
        <?php

    }


    /**
     * Add fields to the profile page
     * Note: This is only used in the WordPress admin, Theme My Login fields are added
     * in the profile-form.php template in [theme]/theme-my-login/profile-form.php
     */
    public function add_profile_fields( $user ) {
        if( !is_admin() )
            return;
        ?>
        <h2>LeadMarket Fields</h2>
        <table class="form-table">
            <tr>
                <th>
                    <label for="client_company"><?php _e('Company'); ?></label>
                </th>
                <td>
                    <input type="text" name="client_company" id="client_company" value="<?php echo esc_attr( get_the_author_meta( 'client_company', $user->ID ) ); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="client_sales_email"><?php _e('Sales Email'); ?></label>
                </th>
                <td>
                    <input type="email" name="client_sales_email" id="client_sales_email" value="<?php echo esc_attr( get_the_author_meta( 'client_sales_email', $user->ID ) ); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="client_billing_email"><?php _e('Billing Email'); ?></label>
                </th>
                <td>
                    <input type="email" name="client_billing_email" id="client_billing_email" value="<?php echo esc_attr( get_the_author_meta( 'client_billing_email', $user->ID ) ); ?>" class="regular-text" />
                </td>
            </tr>
        </table>
        <?php
    }


    /**
     * Filter registration to ensure custom
     * registration fields are valid
     */
    public function registration_errors( $errors, $sanitized_user_login, $user_email ) {
    
        if ( empty( $_POST['client_company'] ) || trim( $_POST['client_company'] ) == '' ) {
            $errors->add( 'client_company_error', __( '<strong>ERROR</strong>: You must include a company name.', 'leadmarket' ) );
        }
        if ( !empty( $_POST['client_sales_email'] ) && !is_email( $_POST['client_sales_email'] ) ) {
            $errors->add( 'client_sales_email_error', __( '<strong>ERROR</strong>: Please enter a valid sales email address.', 'leadmarket' ) );
        }
        if ( !empty( $_POST['client_billing_email'] ) && !is_email( $_POST['client_billing_email'] ) ) {
            $errors->add( 'client_billing_email_error', __( '<strong>ERROR</strong>: Please enter a valid billing email address.', 'leadmarket' ) );
        }

        return $errors;
    }


    /**
     * Save custom registration fields on registration and profile update
     */
    public function update_custom_user_meta( $user_id ) {
        if ( ! empty( $_POST['client_company'] ) ) {
            update_user_meta( $user_id, 'client_company', sanitize_text_field( trim( $_POST['client_company'] ) ) );
        }
        if ( ! empty( $_POST['client_sales_email'] ) && is_email( $_POST['client_sales_email'] ) ) {
            update_user_meta( $user_id, 'client_sales_email', sanitize_text_field( trim( $_POST['client_sales_email'] ) ) );
        }
        if ( ! empty( $_POST['client_billing_email'] ) && is_email( $_POST['client_billing_email'] ) ) {
            update_user_meta( $user_id, 'client_billing_email', sanitize_text_field( trim( $_POST['client_billing_email'] ) ) );
        }
    }


    /**
     * Set role to client for new users if they were created as a subscriber
     */
    public function set_new_user_role( $user_id ) {
        $user = get_user_by( 'id', $user_id );

        if( 'subscriber' === $user->roles[0] ) {
            $user->roles[0] = 'lm_client';
        }

        wp_update_user( $user );
    }


    /**
     * Get user's balance for the month
     */
    public function get_client_balance( $user_id = 0 ) {
        $balance = 0;
        $user_id = $user_id === 0 ? get_current_user_id() : $user_id;
        $current_year = date( 'Y' );
        $current_month = date( 'n' );
        $purchases_query = new WP_Query( array(
            'posts_per_page' => -1,
            'post_type'      => 'lm_purchase',
            'meta_key'       => 'user_id',
            'meta_value'     => $user_id,
            'year'           => $current_year,
            'monthnum'       => $current_month
        ) );

        if( is_array( $purchases_query->posts ) ) {
            foreach( $purchases_query->posts as $purchase ) {
                $price = get_post_meta( $purchase->ID, 'price', true );
                $price = floatval( $price );
                if( is_numeric( $price ) ) {
                    $balance += $price;
                }
            }
        }
        return $balance;
    }


}

// Finally initialize code
LM_Users::get_instance();
