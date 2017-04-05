<?php

$id = get_the_ID();
$user_can_view_sensitive = lm_client_has_purchased_lead( $id );
$lead_data = lm_get_lead_data( $id );

?>

<article class="lm-lead-snippet">
    <section class="lm-lead-snippet--body">
        <header class="lm-lead-snippet--header">
            <h1 class="lm-lead-snippet--title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h1>
            <p class="lm-lead-snippet--subtitle">
                <span class="lm-lead-snippet--subtitle-item">
                    Quote requested <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ); ?> ago
                </span>
            </p>
        </header>
        <div class="lm-lead-snippet--excerpt">
            <?php the_excerpt(); ?>
        </div>
        <a class="lm-cta-link lm-lead-snippet--permalink" href="<?php the_permalink(); ?>">More details</a>
    </section>
    <?php if( $user_can_view_sensitive ) : ?>
        <section class="lm-lead-snippet--sensitive lm-lead-snippet--sensitive__bought">
            <div class="lm-lead-snippet--sensitive-content">
                <span class="lm-lead-snippet--buy-label">You have access to this lead</span>
                <a class="lm-btn lm-lead-snippet--buy-btn" href="<?php the_permalink(); ?>">Get in touch</a>
            </div>
        </section>
    <?php else : ?>
        <section class="lm-lead-snippet--sensitive lm-lead-snippet--sensitive__buy">
            <div class="lm-lead-snippet--sensitive-content">
                <span class="lm-lead-snippet--buy-label">Contact this lead for</span>
                <span class="lm-lead-snippet--buy-price"><?php echo lm_get_lead_price(); ?></span>
                <a class="lm-btn lm-lead-snippet--buy-btn" href="#">Get in touch</a>
            </div>
        </section>
    <?php endif; ?>
</article>