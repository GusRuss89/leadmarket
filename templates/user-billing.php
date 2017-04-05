<div class="lm-page">
    <div class="grid">
        <div class="col m-one-third pull-right">
            <div class="lm-page--pre-sidebar">
                <div class="lm-balance">
                    <span class="lm-balance--bal"><?php echo lm_get_client_balance(); ?></span>
                    <p class="lm-balance--label">Your balance this month</p>
                </div>
            </div>
        </div>
        <div class="col m-two-thirds">
            <div class="lm-page--body">
                <h2>Your Purchases</h2>
                <?php lm_get_template_part( 'user', 'purchases' ); ?>
            </div>
        </div>
        <div class="col m-one-third">
            <div class="lm-page--sidebar">
                <?php // Invoices will go here ?>
            </div>
        </div>
    </div>
</div>