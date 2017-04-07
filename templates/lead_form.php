<?php

global $lm_leadgen_form;
$form = LM_Form::get_instance();
$form_id = esc_attr( $lm_leadgen_form['id'] );

wp_enqueue_script( 'recaptcha-api', 'https://www.google.com/recaptcha/api.js', array(), true );

?>

<script>
    // Submit handler
    var onSubmit = function(){
        document.getElementById('<?php echo $form_id; ?>').submit();
    };
    // Show some loading feedback while recaptcha api stuff happens
    jQuery(document).ready(function($){
        $('.g-recaptcha').click(function(){
            $(this).addClass('lm-btn__is-loading');
        });
    });
</script>

<form class="lm-form" id="<?php echo $form_id; ?>" method="post" enctype="multipart/form-data" action="">

    <?php if( $lm_leadgen_form['error_msg'] !== '' ) : ?>
        <div class="lm-form--item">
            <p class="lm-form--error-msg lm-form--global-error"><?php echo esc_html( $lm_leadgen_form['error_msg'] ); ?></p>
        </div>
    <?php endif; ?>

    <?php do_action( 'lm_form_before_fields', $lm_leadgen_form ); ?>
    
    <input type="hidden" class="lm-form--hidden" name="lm-form-submitted" value="<?php echo esc_attr( $lm_leadgen_form['id'] ); ?>">

    <?php foreach( $lm_leadgen_form['fields'] as $field_id => $field ) :
        
        // Field class
        $field['class'] = 'lm-form--item';
        $field['class'] .= $field['error'] ? ' lm-form--item__error' : '';
        $field['class'] .= $field['s-width'] ? ' lm-form--item__s-' . $field['s-width'] : '';
        $field['class'] .= $field['m-width'] ? ' lm-form--item__m-' . $field['m-width'] : '';
        $field['class'] .= $field['l-width'] ? ' lm-form--item__l-' . $field['l-width'] : '';

        // Field label
        $label = esc_html( $field['label'] );
        if( $field['required'] ) {
            $label .= '<span class="lm-form--asterix">*</span>';
        }

        ?>

        <div class="<?php echo esc_attr( $field['class'] ); ?>">

            <?php do_action( 'lm_form_before_field_label', $field_id, $field, $lm_leadgen_form ); ?>

            <label class="lm-form--label" for="<?php echo esc_attr( $field_id ); ?>"><?php echo $label; ?></label>

            <?php do_action( 'lm_form_before_field_description', $field_id, $field, $lm_leadgen_form ); ?>

            <?php if( $field['description'] ) : ?>
                <label class="lm-form--description" for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $field['description'] ); ?></label>
            <?php endif; ?>

            <?php do_action( 'lm_form_before_field', $field_id, $field, $lm_leadgen_form ); ?>

            <?php echo $form->get_input_html( $field_id, $field ); ?>

            <?php do_action( 'lm_form_after_field', $field_id, $field, $lm_leadgen_form ); ?>

            <?php if( $field['error_msg'] ) : ?>
                <p class="lm-form--error-msg"><?php echo esc_html( $field['error_msg'] ); ?></p>
            <?php endif; ?>

            <?php do_action( 'lm_form_after_field_error', $field_id, $field, $lm_leadgen_form ); ?>

        </div>

    <?php endforeach; ?>

    <?php do_action( 'lm_form_before_footer', $lm_leadgen_form ); ?>

    <div class="lm-form--item lm-form-item__footer">

        <?php do_action( 'lm_form_before_submit', $lm_leadgen_form ); ?>

        <button
            class="g-recaptcha lm-form--btn lm-btn"
            data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"
            data-callback="onSubmit">
            Get Quotes
        </button>

        <?php do_action( 'lm_form_after_submit', $lm_leadgen_form ); ?>

    </div>

    <?php do_action( 'lm_form_after_footer', $lm_leadgen_form ); ?>

</form>