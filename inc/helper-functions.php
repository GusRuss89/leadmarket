<?php

// Public leadmarket functions

// Print pre - debugging helper
if( ! function_exists( 'lm_print_pre' ) ) {
    function lm_print_pre( $arr, $title = '' ) {
        if( $title ) echo '<h3>' . $title . '</h3>';
        echo '<pre>' . print_r( $arr, true ) . '</pre>';
    }
}

// Format money
if( ! function_exists( 'lm_format_money' ) ) {
    function lm_format_money( $val ) {
        return '$' . number_format_i18n( $val, 2 );
    }
}