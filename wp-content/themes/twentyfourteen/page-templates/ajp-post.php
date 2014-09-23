<?php
/**
 * Template Name: Ajax post gallery Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header('ajp-post'); ?>

<div id="main-content" class="main-content">

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
				ajp_output_content($_REQUEST);
			?>
		</div><!-- #content -->
		
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
//get_sidebar(); 
get_footer('ajp-post');
