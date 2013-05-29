<?php
// Add new taxonomy, make it hierarchical (like categories)
$labels = array(
	'name'                => _x( 'Memes', 'Memes' ),
	'singular_name'       => _x( 'Meme', 'Meme' ),
	'search_items'        => __( 'Search Memes' ),
	'all_items'           => __( 'All Memes' ),
	'parent_item'         => __( 'Parent Meme' ),
	'parent_item_colon'   => __( 'Parent Meme:' ),
	'edit_item'           => __( 'Edit Meme' ), 
	'update_item'         => __( 'Update Meme' ),
	'add_new_item'        => __( 'Add New Meme' ),
	'new_item_name'       => __( 'New Meme Name' ),
	'menu_name'           => __( 'Meme' )
); 	

$args = array(
	'hierarchical'        	=> true,
	'labels'              	=> $labels,
	'show_ui'             	=> true,
	'show_admin_column'  	=> true,
	'query_var'           	=> true,
);

register_taxonomy( 'meme', array( 'memecake' ), $args );