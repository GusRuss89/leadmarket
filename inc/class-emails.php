<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

/**
 * Class: LM_Email (Singleton)
 */
class LM_Email {


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

        add_action( 'wp_insert_post', array( $this, 'send_admin_notification' ), 10, 3 );

	}


    /**
     * Send a notification to Admin
     */
    public function send_admin_notification( $post_id, $post, $update ) {
        
        // We only want to send notifications on the first save of lead posts
        if( $update || 'lm_lead' !== get_post_type( $post_id ) )
            return false;

        global $lm_leadgen_form;
        $post_meta = get_post_meta( $post_id );
        $to = get_option( 'admin_email' );
        $subject = 'New lead: ' . $post->post_title;
        
        $html = 'New lead awaiting moderation. <a href="' . get_edit_post_link( $post_id ) . '">Moderate now</a>.';
        foreach( $post_meta as $key => $field ) {
            $html .= '<p><strong>' . esc_html( $lm_leadgen_form['fields'][$key]['label'] ) . '</strong><br />';
            $html .= esc_html( get_post_meta( $post_id, $key, true ) ) . '</p>';
        }
        $html .= '<h2>Message</h2>';
        $html .= apply_filters( 'the_content', $post->post_content );

        $this->send( $to, $subject, $html );
    }


    /**
     * Send (sets html content type)
     */
    private function send( $to, $subject, $html ) {
        add_filter( 'wp_mail_content_type', array( $this, 'set_mail_html' ) );
        wp_mail( $to, $subject, $html );
        remove_filter( 'wp_mail_content_type', array( $this, 'set_mail_html' ) );
    }


    /**
     * Set content type
     */
    public function set_mail_html() {
        return 'text/html';
    }
    
}

// Finally initialize code
LM_Email::get_instance();
