<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

/**
 * Class: LM_Form (Singleton)
 */
class LM_Form {


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

	}


    /**
     * Do form tag
     */
    public function the_form_opening_tag( $form_id, $echo = true ) {

        global $wp;
        $action = home_url( add_query_arg( array(), $wp->request ) );

        $return = '<form class="lm-form" method="post" enctype="multipart/form-data" action="' . $action . '">';

        $return .= '<input type="hidden" class="lm-form--hidden" name="lm-form-submitted" value="' . $form_id . '">';

        if( $echo ) {
            echo $return;
        } else {
            return $return;
        }

    }


    /**
     * Close form tag
     */
    public function the_form_closing_tag( $echo = true ) {
        $return = '</form>';

        if( $echo ) {
            echo $return;
        } else {
            return $return;
        }
    }


    /**
     * Get text field
     */
    private function get_text_input( $field_id = '', $field ) {
        $attr = '';
        $attr .= ' type="' . esc_attr( $field['type'] ) . '"';
        $attr .= ' value="' . esc_attr( $field['value'] ) . '"';
        $attr .= ' class="lm-form--input lm-form--input__text"';
        $attr .= ' name="' . esc_attr( $field_id ) . '"';
        $attr .= ' id="' . esc_attr( $field_id ) . '"';
        $attr .= ' placeholder="' . esc_attr( $field['placeholder'] ) . '"';
        $attr .= ' ' . $field['attributes'];
        return '<input ' . $attr . '>';
    }


    /**
     * Get select field
     */
    private function get_select_input( $field_id = '', $field ) {
        $attr = '';
        $attr .= ' class="lm-form--input lm-form--input__select"';
        $attr .= ' name="' . esc_attr( $field_id ) . '"';
        $attr .= ' id="' . esc_attr( $field_id ) . '"';
        $attr .= ' ' . $field['attributes'];
        
        $html = '<select ' . $attr . '>';
            foreach( $field['options'] as $key => $value ) {
                $selected = $key === $field['value'] ? 'selected' : '';
                $html .= '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_html( $value ) . '</option>';
            }
        $html .= '</select>';
        return $html;
    }


    /**
     * Do field
     * @param $field [id, label, description, class, type]
     */
    public function the_field( $field_id, $echo = true ) {

        global $lm_field_defaults;
        global $lm_leadgen_form;

        $field = $lm_leadgen_form['fields'][$field_id];
        
        // Set defaults
        $field = $field + $lm_field_defaults;

        if( $field['error'] ) {
            $field['class'] .= 'lm-form-item__error';
        }

        // Get the input html
        switch( $field['type'] ) {
            case 'select':
                $input = $this->get_select_input( $field_id, $field );
                break;
            case 'text':
            case 'email':
            case 'tel':
            case 'number':
            default:
                $input = $this->get_text_input( $field_id, $field );
                break;
        }

        $label = esc_html( $field['label'] );
        if( $field['required'] ) {
            $label .= '<span class="lm-form--asterix">*</span>';
        }

        $return = '';
        $return .= '<div class="lm-form--item ' . esc_attr( $field['class'] ) . '">';
            $return .= '<label class="lm-form--label" for="' . $field_id . '">' . $label . '</label>';
            if( $field['description'] ) $return .= '<label class="lm-form--description" for="' . esc_attr( $field_id ) . '">' . esc_html( $field['description'] ) . '</label>';
            $return .= $input;
            if( $field['error_msg'] ) $return .= '<p class="lm-form--error-msg">' . esc_html( $field['error_msg'] ) . '</p>';
        $return .= '</div>';

        if( $echo ) {
            echo $return;
        } else {
            return $return;
        }

    }
    

    /**
     * Do submit button
     */
    public function the_submit_button( $text = 'Submit', $echo = true ) {
        $return = '<button class="lm-form--btn" type="submit">' . $text . '</button>';

        if( $echo ) {
            echo $return;
        } else {
            return $return;
        }
    }
    
}

// Finally initialize code
LM_Form::get_instance();
