<?php

global $lm_leadgen_form;
$templates = new LM_Template_Loader;

$user_id = get_current_user_id();
$user_purchased_leads = get_user_meta( $user_id, 'available_leads', true );
$leads_query_args = array(
    'post_type' => 'lm_lead'
);

if( isset( $data->show ) && is_array( $user_purchased_leads ) ) {
    if( $data->show === 'purchased' ) {
        $leads_query_args['post__in'] = $user_purchased_leads;
    } else if( $data->show === 'not-purchased' ) {
        $leads_query_args['post__not_in'] = $user_purchased_leads;
    }
}
   
$leads_query = new WP_Query( $leads_query_args );

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