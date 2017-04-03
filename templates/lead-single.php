<?php

global $lm_leadgen_form;

$id = get_the_ID();
$user_can_view_sensitive = lm_client_has_purchased_lead( $id );
$form = get_post_meta( $id, 'form', true ); // For now we know which form this will be
$form = $lm_leadgen_form;
$details = array();
$sensitive = array();
foreach( $form['fields'] as $field_id => $field ) {
    if( $field['sensitive'] ) {
        $array =& $sensitive;
    } else {
        $array =& $details;
    }
    array_push( $array, array(
        'label' => $field['label'],
        'value' => get_post_meta( $id, $field_id, true )
    ) );
}

// Debug
//lm_print_pre( $meta );

?>

<article class="lm-lead">
    <section class="lm-lead--body">
        <header class="lm-lead--header">
            <h1 class="lm-lead--title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h1>
            <p class="lm-lead--subtitle">
                <span class="lm-lead--subtitle-item">
                    Quote requested <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ); ?> ago
                </span>
            </p>
        </header>
        <div class="lm-lead--details">
            <ul class="lm-uldl">
                <?php foreach( $details as $detail ) : ?>
                    <?php if( is_array( $detail['value'] ) ) : ?>
                        <?php $detail['value'] = implode( ', ', $detail['value'] ); ?>
                    <?php endif; ?>
                    <li>
                        <dl>
                            <dt><?php echo esc_html( $detail['label'] ); ?></dt>
                            <dd><?php echo esc_html( $detail['value'] ); ?></dd>
                        </dl>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="lm-lead--excerpt">
            <?php the_excerpt(); ?>
            <a class="lm-fancy-link lm-lead--permalink" href="<?php the_permalink(); ?>">More details</a>
        </div>
    </section>
    <section class="lm-lead--sensitive">
        <span class="lm-lead--buy-label">Contact this lead for</span>
        <span class="lm-lead--buy-price">$8.99</span>
        <a class="lm-btn lm-lead--buy-btn" href="<?php echo lm_get_buy_lead_link( get_the_ID() ); ?>">Get in touch</a>
        <ul class="lm-uldl">
            <?php foreach( $sensitive as $detail ) : ?>
                <?php if( is_array( $detail['value'] ) ) : ?>
                    <?php $detail['value'] = implode( ', ', $detail['value'] ); ?>
                <?php endif; ?>
                <li>
                    <dl>
                        <dt><?php echo esc_html( $detail['label'] ); ?></dt>
                        <dd><?php echo do_shortcode( '[leadmarket-sensitive]' . esc_html( $detail['value'] ) . '[/leadmarket-sensitive]' ); ?></dd>
                    </dl>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</article>