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


	add_action( 'wp_ajax_ajp_refresh', 'ajp_refresh_callback' );
	add_action( 'wp_ajax_nopriv_ajp_refresh', 'ajp_refresh_callback' );


/**
 * Enqueue js script
 * @param string $hook
 */
function ajp_enqueue ( $hook ) {
   
	wp_enqueue_script ( 'ajax-script', plugins_url( '/js/ajax.js', __FILE__ ), array('jquery') );
	wp_enqueue_style( 'ajp-style', plugins_url( '/css/style.css', __FILE__ ) );
	
	$url = '';
	$page_id = 0;
	
	if ( isset ($_REQUEST['page_id']) ) {
		$page_id = $_REQUEST['page_id'];
	}
	
	wp_localize_script ( 'ajax-script', 'ajp_object',
		array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'url' => $url, 'page_id' => $page_id) );
}

/**
 * Callback on refreshing with ajax
 */
function ajp_refresh_callback () {
	
	ob_start ();
	$current_post = ajp_output_content ( $_POST );
	$content = ob_get_clean ();
	
	$same_term = !empty ( $_POST['cat'] );	
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( $same_term, '', true );
	$next     = get_adjacent_post ( $same_term, '', false );
	$previous_link = null;
	$next_link = null;
	$current_link = null; 
	
	if ( $previous ) {
		$previous_link = get_ajppost_link ($previous, $_REQUEST);
	}

	if ( $next ) {
		$next_link = get_ajppost_link ($next, $_REQUEST);
	}
	
	if ( $current_post ) {
		$current_link = get_ajppost_link($current_post, $_REQUEST);
	}
	
	header ("Content-type: application/json");
	
	echo json_encode ( array('content' => $content, 'next_link' => $next_link, 'previous_link' => $previous_link, 'url' => $current_link) );
	die ();
}

/**
 * Forms link to post with ajppost type on a requested page
 * 
 * @param type $post
 * @param type $request
 * @return string
 */
function get_ajppost_link ($post, $request){
	if ( empty($post) ){
		return '';
	}
	
	$extra = '';
	
	if ( isset($request['page_id']) ) {
	//	$extra .= "&page_id=$request[page_id]";
	}
	
//	$q = array();
//	if ( isset($_REQUEST['page_id']) ){
//		$q[] = "page_id=$_REQUEST[page_id]"; 	
//	}
//
//	if ( isset($_REQUEST['post_id']) ){
//		$q[] = "post_id=$_REQUEST[post_id]"; 	
//	}
//
//	if ( isset($_REQUEST['cat']) ){
//		$q[] = "cat=$_REQUEST[cat]"; 	
//	}
//
//	$query_string = implode('&', $q);
//
//	if(!empty($query_string)){
//		$query_string = "?".$query_string;
//	}
//	
	$link = get_permalink( $post ) . $extra;
	
	return $link;
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

	$args = array( 'post_type' => 'ajppost', 'posts_per_page' => 1, 'order' => 'ASC' );

	if( isset($data ['cat']) ) { 
		$args ['cat'] = intval ( $data ['cat'] );
	}

	if( isset($data ['ajppost']) ) { 
		$args ['ajppost'] = strval ( $data ['ajppost'] );
	}
	
//	if (empty($data ['cat']) && empty($data ['ajppost']) ){
//		$same_term = false;
//	}else{
//		$same_term = true;
//	}
	
	$wp_query = new WP_Query( $args );
	while ( $wp_query->have_posts() ) : $wp_query->the_post();
		// Include the page content template.
		get_template_part( 'content', 'page' );
				
		if ( isset ($wp_query->posts[$wp_query->current_post]) ) {
			$current_post = $wp_query->posts [$wp_query->current_post];
		}
		
	endWhile;		
	
	return ( isset ($current_post) ? $current_post : array());	
}

/**
 * Post navigation
 */
function ajp_twentyfourteen_post_nav($same_term = true) {

	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( $same_term, '', true );
	$next     = get_adjacent_post( $same_term, '', false );

	$previous_link = $next_link = null;
	
	if ( $previous ) {
		$previous_link = get_ajppost_link ($previous, $_REQUEST);
	}

	if ( $next ) {
		$next_link = get_ajppost_link ($next, $_REQUEST);
	}
	
	?>
	<nav class="ajp-post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'twentyfourteen' ); ?></h1>
		<div class="">
			<a class="ajp-prev-link <?php if (!$previous_link) echo 'hidden'; ?>" href="<?php echo $previous_link; ?>"><?php echo __( '<span class="genericon ajp-prev genericon-leftarrow"><p class="screen-reader-text">Previous post</p></span>', 'twentyfourteen' ); ?></a>
			<a class="ajp-next-link <?php if (!$next_link) echo 'hidden'; ?>" href="<?php echo $next_link; ?>"><?php echo __( '<span class="genericon ajp-next genericon-rightarrow"><p class="screen-reader-text">Next post</p></span>', 'twentyfourteen' ); ?></a>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}