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
				
				global $wp_query;
				
				$posts = $wp_query->get_posts ();
				
				if ( !empty ($posts) && (isset ($posts[0])) ){
					$post = $posts [0];					
					$link = get_ajppost_link($post, $_REQUEST);
				} else {
					$link = '';
				}				
				
			?>
			<?php get_sidebar( 'footer' ); ?>
			<div class="social-buttons">
				<fb:like class="fb-social-button social-iframe" href="<?php echo $link; ?>" layout="standard" action="like" show_faces="true" share="true"></fb:like>				
				
				<div class="social-iframe" id="vk_like"></div>
				<script type="text/javascript">
					VK.Widgets.Like("vk_like", {type: "button"});
				</script>
				
				<div class="social-iframe" id="ok_shareWidget"></div>
				<script>
					var odnokl_url = "<?php echo $link; ?>";
					
					!function (d, id, did, st) {
					  var js = d.createElement("script");
					  js.src = "http://connect.ok.ru/connect.js";
					  js.onload = js.onreadystatechange = function () {
					  if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
						if (!this.executed) {
						  this.executed = true;
						  setTimeout(function () {
							OK.CONNECT.insertShareWidget(id,did,st);
						  }, 0);
						}
					  }};
					  d.documentElement.appendChild(js);
					}(document,"ok_shareWidget",odnokl_url,"{width:170,height:30,st:'rounded',sz:20,ck:3}");
				</script>

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