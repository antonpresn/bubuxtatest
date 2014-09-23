<?php

/**
 * Plugin Name: Ajax posts gallery
 * Plugin URI: http://
 * Description: Adds new post type to existing ones, allows to view posts as gallery with ajax loading
 * Version: 1.0
 * Author: Anton Presnyakov
 * Author URI: http://
 * License: MIT
 */

// including new post type registration routines
require_once(sprintf("%s/post-types/ajp-post-type.php", dirname(__FILE__)));

// Installation and uninstallation hooks
register_activation_hook(__FILE__, 'ajp_activate');
register_deactivation_hook(__FILE__, 'ajp_deactivate');

/**
 * activating plugin
 */
function ajp_activate(){
	
}

/**
 * deactivating plugin
 */
function ajp_deactivate(){
	
}

// adding actions
add_action( 'wp_enqueue_scripts', 'ajp_enqueue' );

if ( is_admin() ) {
	add_action( 'wp_ajax_ajp_refresh', 'ajp_refresh_callback' );
	add_action( 'wp_ajax_nopriv_ajp_refresh', 'ajp_refresh_callback' );
}

/**
 * Enqueue js script
 * @param string $hook
 */
function ajp_enqueue ( $hook ) {
   
	wp_enqueue_script ( 'ajax-script', plugins_url( '/js/ajax.js', __FILE__ ), array('jquery') );
	wp_enqueue_style( 'ajp-style', plugins_url( '/css/style.css', __FILE__ ) );
	
	wp_localize_script ( 'ajax-script', 'ajp_object',
		array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'url' => 1234 ) );
}

/**
 * Callback on refreshing with ajax
 */
function ajp_refresh_callback () {
	ajp_output_content($_POST);
	die ();
}


add_action( 'get_posts', 'ajp_get_posts' );
//get_posts();
function ajp_get_posts ( $args ) {
	//print_r($args);
}

// filters
add_filter ( 'term_link', 'ajp_category_link' );

/**
* Filter the term link.
* Here used to change category link on ajp-post pages
* @since 2.5.0
*
* @param string $termlink Term link URL.
* @param object $term     Term object.
* @param string $taxonomy Taxonomy slug.
*/
function ajp_category_link ( $termlink) {
	if ( !is_page_template('page-templates/ajp-post.php') ) {
		return $termlink;
	}
	
	if ( isset($_REQUEST['page_id']) ) {
		$termlink .= "&page_id=$_REQUEST[page_id]";
	}
	
	return $termlink;
}

/**
 * Main ajp post content function 
 * @global WP_Query $wp_query
 * @param type $data
 */
function ajp_output_content ($data) {
	
	global $wp_query;
	// Start the Loop.

	$args = array( 'post_type' => 'ajppost', 'posts_per_page' => 1 );

	if( isset($data ['cat']) ) { 
		$args ['cat'] = intval ( $data ['cat'] );
	}

	if( isset($data ['ajppost']) ) { 
		$args ['ajppost'] = strval ( $data ['ajppost'] );
	}
	
	if (empty($data ['cat']) && empty($data ['ajppost']) ){
		$same_term = false;
	}else{
		$same_term = true;
	}
	
	$wp_query = new WP_Query( $args );
	while ( $wp_query->have_posts() ) : $wp_query->the_post();
		// Include the page content template.
		get_template_part( 'content', 'page' );
		
		//twentyfourteen_post_thumbnail();
		
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	endWhile;		
	
	// Previous/next post navigation.
	ajp_twentyfourteen_post_nav($same_term);
}

/**
 * Post navigation
 */
function ajp_twentyfourteen_post_nav($same_term = true) {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( $same_term, '', true );
	$next     = get_adjacent_post( $same_term, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	?>
	<nav class="ajp-post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'twentyfourteen' ); ?></h1>
		<div class="">
			<?php
			if ( is_attachment() && $previous ) :
				previous_post_link( '%link', __( '<span class="dashicons ajp-prev dashicons-arrow-left-alt2"><p class="screen-reader-text">Published In</p></span>', 'twentyfourteen' ) );
			else :
				if ( $previous ) {
					previous_post_link( '%link', __( '<span class="dashicons ajp-next  dashicons-arrow-left-alt2"><p class="screen-reader-text">Previous post</p></span>', 'twentyfourteen' ) );
				}
				
				if ( $next ) {
					next_post_link( '%link', __( '<span class="dashicons ajp-prev dashicons-arrow-right-alt2"><p class="screen-reader-text">Next post</p></span>', 'twentyfourteen' ) );
				}
			endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}