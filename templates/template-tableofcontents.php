<?php
/**
 * Template Name: Table of Contents Template
 * Template Post Type: post, page
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

// Original call
get_template_part( 'singular' );

function insert_content_after_main() {
    // Your content or PHP include
    include(table_of_contents_dir . 'templates/sidebar-tableofcontents.php');
}
add_action('get_footer', 'insert_content_after_main');


// This isn't working...

// installed into active theme directory, so calling out to plugin directory
// $plugin_template_path = plugins_url ('templates/singlar-tableofcontents.php',__FILE__);
// if (file_exists($plugin_template_path)) {
	//include($plugin_template_path);
//} else {
	//echo $plugin_template_path;

//}