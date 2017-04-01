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
     * Get text field
     */
    private function get_text_input( $field_id = '', $field ) {
        $attr = '';
        $attr .= 'type="' . esc_attr( $field['type'] ) . '"';
        $attr .= ' value="' . esc_attr( $field['value'] ) . '"';
        $attr .= ' class="lm-form--input lm-form--input__text"';
        $attr .= ' name="' . esc_attr( $field_id ) . '"';
        $attr .= ' id="' . esc_attr( $field_id ) . '"';
        $attr .= ' placeholder="' . esc_attr( $field['placeholder'] ) . '"';
        $attr .= ' ' . $field['attributes'];
        return '<input ' . $attr . '>';
    }


    /**
     * Get textarea
     */
    private function get_textarea( $field_id = '', $field ) {
        $attr = '';
        $attr .= 'class="lm-form--input lm-form--input__textarea"';
        $attr .= ' name="' . esc_attr( $field_id ) . '"';
        $attr .= ' id="' . esc_attr( $field_id ) . '"';
        $attr .= ' placeholder="' . esc_attr( $field['placeholder'] ) . '"';
        $attr .= ' ' . $field['attributes'];
        return '<textarea ' . $attr . '>' . esc_textarea( $field['value'] ) . '</textarea>';
    }


    /**
     * Get select field
     */
    private function get_select_input( $field_id = '', $field ) {
        $attr = '';
        $attr .= 'class="lm-form--input lm-form--input__select"';
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
     * Get input html
     */
    public function get_input_html( $field_id ) {

        global $lm_leadgen_form;
        $field = $lm_leadgen_form['fields'][$field_id];
        
        switch( $field['type'] ) {
            case 'textarea':
                $input = $this->get_textarea( $field_id, $field );
                break;
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

        return $input;
    }
    
}

// Finally initialize code
LM_Form::get_instance();
