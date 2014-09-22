<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// binding actions
add_action ( 'init', 'ajp_post_type');

const AJP_POST_TYPE = 'ajppost';

/**
 * registering new post type
 */
function ajp_post_type(){
	$labels = array(
		'name'               => _x( 'A-Post', 'Ajax gallery post', 'ajax-posts' ),
		'singular_name'      => _x( 'A-Post', 'Post', 'ajax-posts' ),
		'menu_name'          => _x( 'A-Posts', 'A-Posts', 'ajax-posts' ),
		'name_admin_bar'     => _x( 'A-Post', 'A-Post', 'ajax-posts' ),
		'add_new'            => _x( 'Add New', 'A-Post', 'ajax-posts' ),
		'add_new_item'       => __( 'Add New A-Post', 'ajax-posts' ),
		'new_item'           => __( 'New A-Post', 'ajax-posts' ),
		'edit_item'          => __( 'Edit A-Post', 'ajax-posts' ),
		'view_item'          => __( 'View A-Post', 'ajax-posts' ),
		'all_items'          => __( 'All A-Posts', 'ajax-posts' ),
		'search_items'       => __( 'Search A-Posts', 'ajax-posts' ),
		'parent_item_colon'  => __( 'Parent A-Post:', 'ajax-posts' ),
		'not_found'          => __( 'No A-Posts found.', 'ajax-posts' ),
		'not_found_in_trash' => __( 'No A-Posts found in Trash.', 'ajax-posts' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => AJP_POST_TYPE ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null
		
	);

	register_post_type( AJP_POST_TYPE, $args );
}