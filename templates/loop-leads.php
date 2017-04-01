<?php

global $lm_leadgen_form;
$templates = new LM_Template_Loader;
   
$leads_query = new WP_Query( array(
    'post_type' => 'lm_lead'
) );

?>

<?php if( $leads_query->have_posts() ) : ?>

    <div class="lm-leads">
        <?php while( $leads_query->have_posts() ) : $leads_query->the_post(); ?>

            <div class="lm-leads--lead">
                <?php $templates->get_template_part( 'lead', 'in_archive' ); ?>
            </div>

        <?php endwhile; ?>
    </div>

<?php else: ?>

    <div class="lm-no-leads">
        <p>No leads found.</p>
    </div>

<?php endif; ?>