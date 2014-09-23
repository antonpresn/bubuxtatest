<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

		</div><!-- #main -->

		<footer id="colophon" class="site-footer" role="contentinfo">
			<?php	
				$q = array();
				if ( isset($_REQUEST['page_id']) ){
					$q[] = "page_id=$_REQUEST[page_id]"; 	
				}
				
				if ( isset($_REQUEST['post_id']) ){
					$q[] = "post_id=$_REQUEST[post_id]"; 	
				}
				
				if ( isset($_REQUEST['cat']) ){
					$q[] = "cat=$_REQUEST[cat]"; 	
				}
				
				$query_string = implode('&', $q);

				if(!empty($query_string)){
					$query_string = "?".$query_string;
				}
			?>
			<?php get_sidebar( 'footer' ); ?>
			<div class="social-buttons">
				<fb:like class="fb-social-button" href="<?php echo site_url($query_string); ?>" layout="standard" action="like" show_faces="true" share="true"></fb:like>				
			</div>
			<div class="site-info">
				<?php do_action( 'twentyfourteen_credits' ); ?>
				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'twentyfourteen' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'twentyfourteen' ), 'WordPress' ); ?></a>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>