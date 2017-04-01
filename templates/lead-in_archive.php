<article class="lm-lead-snippet">
    <section class="lm-lead-snippet--body">
        <header class="lm-lead-snippet--header">
            <h1 class="lm-lead-snippet--title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h1>
            <p class="lm-lead-snippet--subtitle">
                <span class="lm-lead-snippet--subtitle-item">240L / week</span>
                <span class="lm-lead-snippet--subtitle-item">
                    Quote requrested <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ); ?> ago
                </span>
            </p>
        </header>
        <div class="lm-lead-snippet--excerpt">
            <?php the_excerpt(); ?>
            <a class="lm-fancy-link lm-lead-snippet--permalink" href="<?php the_permalink(); ?>">More details</a>
        </div>
    </section>
    <section class="lm-lead-snippet--buy">
        <div class="lm-lead-snippet--buy-content">
            <span class="lm-lead-snippet--buy-label">Contact this lead for</span>
            <span class="lm-lead-snippet--buy-price">$8.99</span>
            <a class="lm-btn lm-lead-snippet--buy-btn" href="#">Get in touch</a>
        </div>
    </section>
</article>