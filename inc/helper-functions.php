<?php

// Public leadmarket functions

// Print pre - deubbing helper
if( ! function_exists( 'lm_print_pre' ) ) {
    function lm_print_pre( $arr, $title = '' ) {
        if( $title ) echo '<h3>' . $title . '</h3>';
        echo '<pre>' . print_r( $arr, true ) . '</pre>';
    }
}
