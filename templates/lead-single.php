<?php

$id = get_the_ID();
$user_can_view_sensitive = lm_client_has_purchased_lead( $id );
$details = lm_get_lead_data( $id );

?>

<article class="lm-lead">
    <header class="lm-lead--header">
        <h1 class="lm-lead--title"><?php the_title(); ?></h1>
        <p class="lm-lead--subtitle">
            <span class="lm-lead--subtitle-item">
                Quote requested <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ); ?> ago
            </span>
        </p>
    </header>
    <div class="grid">
        <div class="col m-two-thirds">
            <section class="lm-lead--body">
                <div class="lm-lead--details">
                    <?php lm_do_detail_list( $details, 'open', true); ?>
                </div>
                <div class="lm-lead--content">
                    <h2>Customer comments</h2>
                    <?php the_content(); ?>
                </div>
            </section>
        </div>
        <div class="col m-one-third">
            <section class="lm-lead--sensitive">
                <?php if( ! $user_can_view_sensitive ) : ?>
                    <div class="lm-lead--buy">
                        <span class="lm-lead--buy-label">Contact this lead for</span>
                        <span class="lm-lead--buy-price"><?php echo lm_get_lead_price(); ?></span>
                        <a class="lm-btn lm-lead--buy-btn" href="<?php echo lm_get_buy_lead_link( get_the_ID() ); ?>">Get in touch</a>
                    </div>
                <?php endif; ?>
                <div class="lm-lead--sensitive-content">
                    <?php if( $user_can_view_sensitive ) : ?>
                        <h2 class="lm-lead--sensitive-title">Contact</h2>
                    <?php endif; ?>
                    <?php lm_do_detail_list( $details, 'sensitive' ); ?>
                </div>
            </section>
        </div>
    </div>
</article>