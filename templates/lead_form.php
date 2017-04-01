<?php

global $lm_leadgen_form;
$form = LM_Form::get_instance();

?>

<form class="lm-form" method="post" enctype="multipart/form-data" action="">

    <input type="hidden" class="lm-form--hidden" name="lm-form-submitted" value="' . esc_attr( $lm_leadgen_form['id'] ) . '">

    <?php foreach( $lm_leadgen_form['fields'] as $field_id => $field ) :
        
        // Field class
        $field['class'] = 'lm-form--item';
        if( $field['error'] ) {
            $field['class'] .= ' lm-form-item__error';
        }

        // Field label
        $label = esc_html( $field['label'] );
        if( $field['required'] ) {
            $label .= '<span class="lm-form--asterix">*</span>';
        }

        ?>

        <div class="<?php esc_attr( $field['class'] ); ?>">
            <label class="lm-form--label" for="<?php echo esc_attr( $field_id ); ?>"><?php echo $label; ?></label>
            <?php if( $field['description'] ) : ?>
                <label class="lm-form--description" for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $field['description'] ); ?></label>
            <?php endif; ?>
            <?php echo $form->get_input_html( $field_id ); ?>
            <?php if( $field['error_msg'] ) : ?>
                <p class="lm-form--error-msg"><?php echo esc_html( $field['error_msg'] ); ?></p>
            <?php endif; ?>
        </div>

    <?php endforeach; ?>

    <div class="lm-form--item lm-form-item__footer">
        <button class="lm-form--btn" type="submit">Get Quotes</button>
    </div>

</form>