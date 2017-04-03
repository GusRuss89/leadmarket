<?php

// To do: paginate

$user_id = get_current_user_id();

$purchases_query = new WP_Query( array(
    'posts_per_page' => -1,
    'post_type'      => 'lm_purchase',
    'meta_key'       => 'user_id',
    'meta_value'     => $user_id
) );

?>

<?php if( $purchases_query->have_posts() ) : ?>
    <table class="lm-table">
        <thead>
            <tr>
                <th>Lead</th>
                <th>Purchased</th>
                <th>Paid</th>
            </tr>
        </thead>
        <tbody>
            <?php while( $purchases_query->have_posts() ) : $purchases_query->the_post(); ?>
                <?php
                $purchase_id = get_the_ID();
                $lead_id = get_post_meta( get_the_ID(), 'lead_id', true );
                $lead_title = get_post_meta( get_the_ID(), 'lead_title', true );
                $date = get_the_date();
                $price = get_post_meta( get_the_ID(), 'price', true );
                ?>
                <tr>
                    <td>
                        <a href="<?php the_permalink( $lead_id ); ?>">
                            <?php echo esc_html( $lead_title ); ?>
                        </a>
                    </td>
                    <td><?php echo esc_html( $date ); ?></td>
                    <td><?php echo lm_format_money( $price ); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>