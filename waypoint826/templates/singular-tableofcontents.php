<?php
/**
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

//this is included within the plugin directory...

get_header();
?>

<main id="site-content">

	<?php

	if ( have_posts() ) {

		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );
		}
	}

	?>

</main><!-- #site-content -->
<?php 
	//original
	//get_sidebar( 'tableofcontents' ); 

	//calling out to plugin directory
	//$plugin_file_get = plugin_dir_path(__FILE__) . 'table-of-contents/sidebar-tableofcontents.php';
	//require_once($plugin_file_get);

?>

<?php 

	//not sure what this is, commenting out for now
    //get_template_part( 'template-parts/footer-menus-widgets' ); 

?>

<?php
get_footer();
