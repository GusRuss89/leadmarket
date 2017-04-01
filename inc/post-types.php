<?php

// Register Leads Custom Post Type
function lm_lead_post_type() {

	$labels = array(
		'name'                  => _x( 'Leads', 'Post Type General Name', 'leadmarket' ),
		'singular_name'         => _x( 'Lead', 'Post Type Singular Name', 'leadmarket' ),
		'menu_name'             => __( 'Leads', 'leadmarket' ),
		'name_admin_bar'        => __( 'Leads', 'leadmarket' ),
		'archives'              => __( 'Lead Archives', 'leadmarket' ),
		'attributes'            => __( 'Lead Attributes', 'leadmarket' ),
		'parent_item_colon'     => __( 'Parent Lead:', 'leadmarket' ),
		'all_items'             => __( 'All Leads', 'leadmarket' ),
		'add_new_item'          => __( 'Add New Lead', 'leadmarket' ),
		'add_new'               => __( 'Add New', 'leadmarket' ),
		'new_item'              => __( 'New Lead', 'leadmarket' ),
		'edit_item'             => __( 'Edit Lead', 'leadmarket' ),
		'update_item'           => __( 'Update Lead', 'leadmarket' ),
		'view_item'             => __( 'View Lead', 'leadmarket' ),
		'view_items'            => __( 'View Leads', 'leadmarket' ),
		'search_items'          => __( 'Search Lead', 'leadmarket' ),
		'not_found'             => __( 'Not found', 'leadmarket' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'leadmarket' ),
		'featured_image'        => __( 'Featured Image', 'leadmarket' ),
		'set_featured_image'    => __( 'Set featured image', 'leadmarket' ),
		'remove_featured_image' => __( 'Remove featured image', 'leadmarket' ),
		'use_featured_image'    => __( 'Use as featured image', 'leadmarket' ),
		'insert_into_item'      => __( 'Insert into lead', 'leadmarket' ),
		'uploaded_to_this_item' => __( 'Uploaded to this lead', 'leadmarket' ),
		'items_list'            => __( 'Leads list', 'leadmarket' ),
		'items_list_navigation' => __( 'Leads list navigation', 'leadmarket' ),
		'filter_items_list'     => __( 'Filter leads list', 'leadmarket' ),
	);
	$rewrite = array(
		'slug'                  => 'leads',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Lead', 'leadmarket' ),
		'description'           => __( 'Customers who have completed your lead gen form', 'leadmarket' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-id-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
	);
	register_post_type( 'lm_lead', $args );

}
add_action( 'init', 'lm_lead_post_type', 0 );