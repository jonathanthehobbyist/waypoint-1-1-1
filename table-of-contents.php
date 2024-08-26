<?php
/**
 * Plugin Name: Waypoint 826 - Table of Contents
 * Description: Adds a table of contents to select pages and posts based on h2, h3 and h4 headings
 * Author: Jon Simmons
 */

// Activate plugin
/*
* from https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
* On activate, download files to proper directories
*
*/

// Activation functions

function waypoint826_enqueue_styles() {
    // Register the style
    wp_enqueue_style(
        'waypoint-style', // Handle for the stylesheet
        plugin_dir_url(__FILE__) . 'css/waypoint-style.css', // Path to the stylesheet
        array(), // Dependencies (if any)
        '1.0.0', // Version number (optional)
        'all' // Media type (optional, e.g., 'all', 'screen', 'print')
    );
}






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

    //define('table_of_contents_dir', plugin_dir_path(__FILE__));
}


// Activation calls

function waypoint826_activate () {
	//Trigger function to copy files
	//waypoint826_place_files();
	//waypoint826_define_paths();
}

register_activation_hook(__FILE__, 'waypoint826_activate' );

add_action('wp_enqueue_scripts', 'waypoint826_enqueue_styles');

//add_action( 'widgets_init', 'waypoint826_register' );



// Deactivate plugin
/*
* On deactivate, delete files from directories - clean way to handle this
*
*
*/

function waypoint826_deactivate() {
	// Remove files previously added - be damn careful

	// Define path of file(s) to be removed
	//$will_delete = get_stylesheet_directory() . '/templates/template-tableofcontents.php';


	// Check if the template already exists in the theme directory
	// Will probably need a for loop eventually
    //if (file_exists($will_delete)) {

        // Copy the file from the plugin folder to the theme folder
        //unlink($will_delete);
    //}

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




// Run plugin



function waypoint826_run() {
    // Your custom code here
    if (is_page()) {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Your JavaScript code here
        console.log('This is the home page');

        // Create the main container to hold the waypoint table of contents
        let mainContainer = document.createElement('div');
        mainContainer.className = 'waypoint826-main';
        console.log(mainContainer);

        // Append the main waypoint container to a DIV element on the page
        var parentDiv = document.querySelector('.main-wrapper');
        parentDiv.appendChild(mainContainer);
        console.log(parentDiv);


    });
    </script>
    <?php
    }
}

add_action('wp', 'waypoint826_run');






