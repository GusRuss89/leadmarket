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
        add_action( 'register_form', array( $this, 'add_profile_fields' ) );
        add_filter( 'registration_errors', array( $this, 'registration_errors' ) );
        add_action( 'user_register', array( $this, 'user_register' ) );
        add_action( 'user_register', array( $this, 'set_new_user_role' ) );

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
     * Add fields to the registration form
     */
    public function add_profile_fields() {

        // Company
        $company = ( ! empty( $_POST['client_company'] ) ) ? trim( $_POST['client_company'] ) : '';

        ?>
            <p>
                <label for="client_company"><?php _e( 'Company', 'leadmarket' ) ?><br />
                <input type="text" name="client_company" id="client_company" class="input" value="<?php echo esc_attr( wp_unslash( $company ) ); ?>" size="25" /></label>
            </p>
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

        return $errors;
    }


    /**
     * Save custom registration fields on registration
     */
    public function user_register( $user_id ) {
        if ( ! empty( $_POST['client_company'] ) ) {
            update_user_meta( $user_id, 'client_company', sanitize_text_field( trim( $_POST['client_company'] ) ) );
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
