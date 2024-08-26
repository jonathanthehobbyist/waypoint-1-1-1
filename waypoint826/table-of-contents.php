<?php
/**
 * Plugin Name: Table of contents
 * Description: Adds a table of contents to pages and posts that use a newly installed template file called Table of Contents Template
 * Author: Jon Simmons
 */

// Add 3 files 1) ADDED template-tableofcontents.php in the active theme directory which calls 2) ADDED singular-tableofcontents.php in the active theme directory which needs 3) sidebar-tableofcontents.php in the active theme directory


// Activate plugin
/*
* from https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
* On activate, download files to proper directories
*
*/

// Activation functions

function waypoint826_place_files() {

	//function blah() copies files from the plugin directory into Wordpress core

	//define files to be inserted from plugin folder
	$source_template = plugin_dir_path(__FILE__) . 'templates/template-tableofcontents.php';

	//define destination path
	$destination_template = get_stylesheet_directory() . '/templates/template-tableofcontents.php';

	// Check if the template already exists in the theme directory
    if (!file_exists($destination_template)) {

        // Copy the file from the plugin folder to the theme folder
        copy($source_template, $destination_template);
    }
}

function waypoint826_define_paths() {
	//Global variable
    //$plugin_template_path = plugins_url ('templates/singlar-tableofcontents.php',__FILE__);
    define('table_of_contents_dir', plugin_dir_path(__FILE__));
}

function waypoint826_register() {
    register_sidebar(
        array(
            'name'          => __( 'Table of Contents Sidebar', 'textdomain' ),
            'id'            => 'table-of-contents-sidebar',
            'description'   => __( 'A table of contents sidebar for Wordpress themes.', 'textdomain' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}


// Activation calls

function waypoint826_activate () {
	//Trigger function to copy files
	waypoint826_place_files();
	waypoint826_define_paths();
}

register_activation_hook(__FILE__, 'pluginprefix_activate' );

add_action( 'widgets_init', 'waypoint826_register' );

// Deactivate plugin
/*
* On deactivate, delete files from directories - clean way to handle this
*
*
*/

function waypoint826_deactivate() {
	// Remove files previously added - be damn careful

	// Define path of file(s) to be removed
	$will_delete = get_stylesheet_directory() . '/templates/template-tableofcontents.php';


	// Check if the template already exists in the theme directory
	// Will probably need a for loop eventually
    if (file_exists($will_delete)) {

        // Copy the file from the plugin folder to the theme folder
        unlink($will_delete);
    }

}

// Deactivation calls

register_deactivation_hook(__FILE__,  'waypoint826_deactivate' );





    //Global variable
    //$plugin_template_path = plugins_url ('templates/singlar-tableofcontents.php',__FILE__);
    //define('table_of_contents_dir', plugin_dir_path(__FILE__));

        //DELETE by 9.01.24

    //adding other files, will need the conditional to check if they exist
   // $source_singular = plugin_dir_path(__FILE__) . 'templates/singular-tableofcontents.php';
   // $destination_singular = get_stylesheet_directory() . '/singular-tableofcontents.php';
    //copy($source_singular, $destination_singular);

	//adding other files, will need the conditional to check if they exist
    //$source_sidebar = plugin_dir_path(__FILE__) . '/sidebar-tableofcontents.php';
    //$destination_sidebar = get_stylesheet_directory() . '/sidebar-tableofcontents.php';
   // copy($source_sidebar, $destination_sidebar);

}





