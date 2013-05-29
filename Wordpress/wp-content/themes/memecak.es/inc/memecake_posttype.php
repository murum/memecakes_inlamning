<?php
$labels = array(
	'name'               => _x( 'Memecakes', 'memecakes' ),
	'singular_name'      => _x( 'Memecake', 'memecake' ),
	'add_new'            => _x( 'Add New', 'memecake' ),
	'add_new_item'       => __( 'Add New Memecake' ),
	'edit_item'          => __( 'Edit Memecake' ),
	'new_item'           => __( 'New Memecake' ),
	'all_items'          => __( 'All Memecakes' ),
	'view_item'          => __( 'View Memecake' ),
	'search_items'       => __( 'Search Memecakes' ),
	'not_found'          => __( 'No memecakes found' ),
	'not_found_in_trash' => __( 'No memecakes found in the Trash' ),
	'parent_item_colon'  => '',
	'menu_name'          => 'Memecake'
);

$args = array(
	'labels'        => $labels,
	'description'   => 'Memecakes from the users',
	'public'        => true,
	'menu_position' => 5,
	'supports'      => array( 'title', 'editor', 'thumbnail', 'author'),
	'has_archive'   => true,
	'hierarchical'	=> true,
);

register_post_type('memecake',$args);