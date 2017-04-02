<article class="lm-lead">
    <section class="lm-lead--body">
        <header class="lm-lead--header">
            <h1 class="lm-lead--title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h1>
            <p class="lm-lead--subtitle">
                <span class="lm-lead--subtitle-item">240L / week</span>
                <span class="lm-lead--subtitle-item">
                    Quote requested <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ); ?> ago
                </span>
            </p>
        </header>
        <div class="lm-lead--excerpt">
            <?php the_excerpt(); ?>
            <a class="lm-fancy-link lm-lead--permalink" href="<?php the_permalink(); ?>">More details</a>
        </div>
    </section>
    <section class="lm-lead--buy">
        <div class="lm-lead--buy-content">
            <span class="lm-lead--buy-label">Contact this lead for</span>
            <span class="lm-lead--buy-price">$8.99</span>
            <a class="lm-btn lm-lead--buy-btn" href="<?php echo lm_get_buy_lead_link( get_the_ID() ); ?>">Get in touch</a>
        </div>
    </section>
</article>