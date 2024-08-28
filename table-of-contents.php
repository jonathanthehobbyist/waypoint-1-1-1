<?php
/**
 * Plugin Name: Waypoint 826 - Table of Contents
 * Description: Adds a table of contents to select pages and posts based on h2, h3 and h4 headings
 * Author: Jon Simmons
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


/*
function wporg_add_custom_box() {
    $screens = [ 'post', 'wporg_cpt' ];
    foreach ( $screens as $screen ) {
        add_meta_box(
            'wporg_box_id',                 // Unique ID
            'Custom Meta Box Title',      // Box title
            'wporg_custom_box_html',  // Content callback, must be of type callable
            $screen,                            // Post type
              'normal',                             // Context (normal, side, advanced)
        'high'                                // Priority (default, high, low)
        );
    }
}
add_action( 'add_meta_boxes', 'wporg_add_custom_box' ); 
*/


function wporg_custom_box_html( $post ) {

    // Retrieve current value from the database
    $value = get_post_meta( $post->ID, '_wporg_meta_key', true );

    // Add a nonce field for security
    wp_nonce_field( 'wporg_save_postdata', 'wporg_nonce' );

    ?>

    <label for="wporg_field">Description for this field</label>
    <select name="wporg_field" id="wporg_field" class="postbox">
        <option value="">Select something...</option>
        <option value="something" <?php selected( $value, 'something' ); ?>>Something</option>
        <option value="else" <?php selected( $value, 'else' ); ?>>Else</option>
    </select>
    <!--br />
    <input type="checkbox" id="wporg_field" name="wporg_field" value="checked" />
    <label for="wporg_field">Enable => Waypoint | Table of Contents</label>
    <br /><div><p>This will add Waypoint to the left side of the post</p></div>
    <hr>
     <div><p><b>Elements to include (dont use yet)</b></p></div>
     <input type="checkbox" id="enable_waypoint826_checkbox" name="enable_waypoint826_checkbox" value="" /> 
     <label for="enable_waypoint826_checkbox">h1</label>
     <br />
     <input type="checkbox" id="enable_waypoint826_checkbox" name="enable_waypoint826_checkbox" value="" /> 
     <label for="enable_waypoint826_checkbox">h2</label>
     <br />
     <input type="checkbox" id="enable_waypoint826_checkbox" name="enable_waypoint826_checkbox" value="" /> 
     <label for="enable_waypoint826_checkbox">h3</label>
     <br />
     <input type="checkbox" id="enable_waypoint826_checkbox" name="enable_waypoint826_checkbox" value="" /> 
     <label for="enable_waypoint826_checkbox">h4</label>
     <br />
     <input type="checkbox" id="enable_waypoint826_checkbox" name="enable_waypoint826_checkbox" value="" /> 
     <label for="enable_waypoint826_checkbox">h5</label>
     <br />
     <br /><div><p>This will add Waypoint to the left side of the post</p></div><br /-->

    <?php
} 





 function my_custom_plugin_add_meta_box() {
    add_meta_box(
        'my_custom_plugin_meta_box',          // Unique ID
        'Post settings for Waypoint table of contents',                   // Box title
        'wporg_custom_box_html', // Content callback, must be of type callable
        ['post', 'page', 'portfolio'],        // Post types where the meta box should appear
        'normal',                             // Context (normal, side, advanced)
        'high'                                // Priority (default, high, low)
    );
}

add_action('add_meta_boxes', 'my_custom_plugin_add_meta_box');

/*function wporg_save_postdata( $post_id ) {
    error_log("POST data: " . print_r($_POST, true));
    if ( array_key_exists( 'wporg_field', $_POST ) ) {
        
        update_post_meta(
            $post_id,
            '_wporg_meta_key',
            $_POST['wporg_field']
        );
    }
}
add_action( 'save_post', 'wporg_save_postdata' );*/

function wporg_save_postdata( $post_id ) {
    // Log the POST data for debugging
    //error_log("POST data: " . print_r($_POST, true));
    error_log("Save function triggered for post ID: $post_id");

    // Security checks
    if ( ! isset( $_POST['wporg_nonce'] ) || ! wp_verify_nonce( $_POST['wporg_nonce'], 'wporg_save_postdata' ) ) {
        error_log("Nonce verification failed.");
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        error_log("Autosave detected, skipping save.");
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        error_log("User does not have permission to edit this post.");
        return;
    }

    // Check if the input field exists in the POST request
    if ( array_key_exists( 'wporg_field', $_POST ) ) {
        $sanitized_value = sanitize_text_field( $_POST['wporg_field'] );

        // Update the meta field in the database
        error_log("POST data: " . print_r($_POST, true));
        update_post_meta(
            $post_id,
            '_wporg_meta_key',
            $sanitized_value
        );

        // Log the saved value for debugging
        error_log("wporg_field saved with value: $sanitized_value");
    } else {
        error_log("wporg_field not found in POST data.");
    }
}

add_action( 'save_post', 'wporg_save_postdata' );


/*http://localhost:8888/wp-admin/index.php


function my_custom_plugin_meta_box_callback($post) {
    // Output your custom fields or content here

    //enabled single check box
    echo '<input type="checkbox" id="enable_waypoint826_checkbox" name="enable_waypoint826_checkbox" value="" /> ';
    echo '<label for="enable_waypoint826_checkbox">Add Waypoint Table of Contents to this content</label>';
    echo '<br /><div><p>This will add Waypoint to the left side of the post</p></div><br />';
    

    echo '<hr><br />';
    echo '<label for="my_custom_field">Anchor to element with class or ID:</label>';
    echo '&nbsp;&nbsp;<input type="text" id="my_custom_field" name="my_custom_field" value="" />';
    echo '<br /><div><p>Choose a container or element - Waypoint will use that element to position from the left side of the viewport</p></div><hr>';

echo '<br /><div><p><b>Elements to include</b></p></div>';
    echo '<input type="checkbox" id="enable_waypoint826_checkbox" name="enable_waypoint826_checkbox" value="" /> ';
    echo '<label for="enable_waypoint826_checkbox">h1</label>';
    echo '<input type="checkbox" id="enable_waypoint826_checkbox" name="enable_waypoint826_checkbox" value="" /> ';
    echo '<label for="enable_waypoint826_checkbox">h2</label>';
    echo '<input type="checkbox" id="enable_waypoint826_checkbox" name="enable_waypoint826_checkbox" value="" /> ';
    echo '<label for="enable_waypoint826_checkbox">h3</label>';
    echo '<input type="checkbox" id="enable_waypoint826_checkbox" name="enable_waypoint826_checkbox" value="" /> ';
    echo '<label for="enable_waypoint826_checkbox">h4</label>';
    echo '<input type="checkbox" id="enable_waypoint826_checkbox" name="enable_waypoint826_checkbox" value="" /> ';
    echo '<label for="enable_waypoint826_checkbox">h5</label>';
    echo '<br /><div><p>This will add Waypoint to the left side of the post</p></div><br />';
}

*/

/* add_action('save_post', 'my_custom_plugin_save_meta_box_data');

function my_custom_plugin_save_meta_box_data($post_id) {
    // Check if our nonce is set.
    if (!isset($_POST['my_custom_plugin_nonce'])) {
        return $post_id;
    }
    
    $nonce = $_POST['my_custom_plugin_nonce'];

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($nonce, 'my_custom_plugin_save_meta_box_data')) {
        return $post_id;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Check the user's permissions.
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    }

    // Sanitize user input.
    $my_data = sanitize_text_field($_POST['my_custom_field']);

    // Update the meta field in the database.
    update_post_meta($post_id, '_my_custom_field', $my_data);
} */

//Saving data on posts?
/*
// Example in a template file or a plugin function
$my_saved_data = get_post_meta(get_the_ID(), '_my_custom_field', true);

if (!empty($my_saved_data)) {
    echo '<p>Custom Field Value: ' . esc_html($my_saved_data) . '</p>';
}

// Saving options on a settings page?
update_option('my_plugin_option', sanitize_text_field($_POST['my_plugin_option']));

//retrieve settings via
$my_plugin_option = get_option('my_plugin_option', 'default_value');

if ($my_plugin_option) {
    echo '<p>Plugin Option Value: ' . esc_html($my_plugin_option) . '</p>';
}


*/


// Need scripts or styles?
/* 
add_action('admin_enqueue_scripts', 'my_custom_plugin_enqueue_scripts');

function my_custom_plugin_enqueue_scripts($hook) {
    // Only load on the post editor screen
    if ('post.php' != $hook && 'post-new.php' != $hook) {
        return;
    }

    wp_enqueue_script('my_custom_plugin_script', plugin_dir_url(__FILE__) . 'js/custom-script.js', array('jquery'), '1.0', true);
    wp_enqueue_style('my_custom_plugin_style', plugin_dir_url(__FILE__) . 'css/custom-style.css');
}
*/


function waypoint826_place_files() {}

function waypoint826_define_paths() {}

function waypoint826_activate () {} // Activation calls

register_activation_hook(__FILE__, 'waypoint826_activate' );

add_action('wp_enqueue_scripts', 'waypoint826_enqueue_styles');

function waypoint826_deactivate() {}  // Deactivate plugin

register_deactivation_hook(__FILE__,  'waypoint826_deactivate' );

function waypoint826_run() {

            /* 
            * 
            *   PROGRAMMATIC - Pass in post-id or page-id for enabled
            * 
            */ 

    


    if (is_page()) {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // console.log('This is the home page');

        // Create the main container to hold the waypoint table of contents
        let mainContainer = document.createElement('div');
        mainContainer.className = 'waypoint826-main';
        mainContainer.id = 'waypoint826-primary-container';

        // Append the main waypoint container to a DIV element on the page
        var parentDiv = document.querySelector('.main-wrapper');
        parentDiv.appendChild(mainContainer);


            /* 
            * 
            *   PROGRAMMATIC - pass in list of h1, h2, h3 etc
            * 
            */ 


        // Create the list of h2 and/or h3 and/or h4
        var headings = document.querySelectorAll("h4");

        // var headings = document.querySelectorAll("h2, h3, h4");
        const list = document.createElement('ol');
        list.classList.add('list-wrapper');

        // Create a header or title area
        var contentParagraph = document.createElement('p');
        contentParagraph.className = "content";
        contentParagraph.innerHTML = "Contents";
        // use this .appendChild(contentParagraph);

        for (i=0; i<headings.length; i++) {

            // Tests to see if there's a span element inside the h2, h3, h4
            if(headings[i].getElementsByTagName('span')[0]) {

                var listOfH2InnerText = headings[i].getElementsByTagName('span')[0];

            } else {
                continue;
            }

            // Duplicates how the h2, h3, h4 is written - 'dirty version'
           var innerSpan = listOfH2InnerText.innerText;
           console.log(innerSpan);

           // Cleans up the string to make it into a usable class name / on-page anchor link
           var str = listOfH2InnerText.innerText;
           str = str.replace(/^\s/g, ''); //matches any space at the beginning of an input
           str = str.replace(/\s+/g, '-'); //matches 1 or more spaces and converts to a dash
           str = str.replace(/[1234567890?\u201c\u201d.!\#',’>\:\;\=<_~`/"\(\)&+]/g, '').toLowerCase(); //matches 
           // Takes h2 innerHTML, replaces spaces (1) with dashes, (2) replaces all other banned digitals with nothing, and (3)makes it lowercase

           //console.log(str);

           // Assign a unique ID to the h2, h3, h4 tag based on its position
            listOfH2InnerText.id = str;
            
            // Create a list item and link for each h2, h3, h4
            const listItem = document.createElement('li');
            const link = document.createElement('a');
            link.href = "#" + str;
            link.innerHTML = innerSpan;

            // append
            listItem.appendChild(link);
            list.appendChild(listItem);

        } //end for loop

        // Fetch the newly created parent div where you want to insert the new element
        
        if (mainContainer) {
             // If parent div has first child, insert mainContainer before first child
            if (mainContainer.firstChild) {
                 mainContainer.insertBefore(list, mainContainer.firstChild);
            } else {
                // If mainContainer has 0 children, append
                mainContainer.appendChild(list);
                //tocDiv.appendChild(content);
                //tocDiv.appendChild(list);
            }
        }

        // Set the right-hand position of the waypoint826 plugin
        function positionMainContainer() {

                    /* 
                    * 
                    *   PROGRAMMATIC - pass in page element to attach to instead of hard-code
                    * 
                    */ 
          
            // .main-container .row-container
            const contentElement = document.querySelector('.limit-width');
            // Get the computed styles for the contentElement
            const computedStyle = window.getComputedStyle(contentElement);
            // Access the left margin
            const marginLeft = computedStyle.marginLeft;
            const marginTop = computedStyle.marginTop;

            // Computer style returns a string not a number

            const marginLeftValue = parseFloat(computedStyle.marginLeft);
            var marginTopValue = parseFloat(computedStyle.marginTop);
         
            //Resize content
         
            // NEED NOTE HERE
            var relativeRect = headings[0].getBoundingClientRect();

            // Set the absolute div's position
            //mainContainer.style.top = relativeRect.top + 'px'; // Align vertically
            mainContainer.style.left = (marginLeftValue - mainContainer.offsetWidth) + 'px'; // align R to L edge
            console.log(marginLeftValue);

        } // end positionMainContainer


        // Run the function when the page loads
        window.addEventListener('load', positionMainContainer);

        // Run the function whenever the window is resized

        window.addEventListener('resize', () => {
            setTimeout(() => {
                positionMainContainer();
                //const rect = headings[0].getBoundingClientRect();
                //console.log(rect);
            }, 0); // Adjust delay as needed
        });

        // Find all elements with margin 0 auto and shrink + push to the left by some px

        // First, find all elems and add them to an array
        // Experiment 8/27/24 may delete later
        function findAutoMarginElements() {
            const allElements = document.querySelectorAll('*');
            var autoMarginElements = [];

                allElements.forEach(element => {
                    const computedStyle = window.getComputedStyle(element);
                    const marginLeft = computedStyle.marginLeft;
                    const marginRight = computedStyle.marginRight;
                    const marginTop = computedStyle.marginTop;
                    const marginBottom = computedStyle.marginBottom;

                    if (marginLeft === 'auto' && marginRight === 'auto') {
                        autoMarginElements.push(element);
                    }
                });

                return autoMarginElements;
        }

        var elementsWithAutoMargin = findAutoMarginElements();
 

        // Removes .active class from li 
        window.addEventListener('scroll', function() {
        // If scrolled to the very top
        if (window.scrollY === 0) {
            //console.log('at top');
            // Find all active menu items and remove the 'active' class
            document.querySelectorAll('.list-wrapper li.active').forEach(item => {
                item.classList.remove('active');

                });
            }
        });

        // Offset from Browswer window top

        // const distanceFromTop = headings[0].getBoundingClientRect();  don't get rid of yet
        var menuHeight = document.querySelector('.row-menu');
        const distanceFromTop = menuHeight.getBoundingClientRect();
        //console.log(menuHeight);


                /* 
                * 
                *   PROGRAMMATIC - Clean this up, and pass in offset from top
                * 
                */ 


        //console.log(distanceFromTop.y);
        mainContainer.style.top = (distanceFromTop.y + 100 + 'px');
        //console.log(distanceFromTop);



        var box = document.getElementById('waypoint826-primary-container'),
        top = box.offsetTop;

        window.addEventListener('scroll', function(event) {
            //console.log('Page scrolled!');

            var y = document.documentElement.scrollTop || document.body.scrollTop;
            //console.log(y);

            if ( (y - 80) >= top) { 
                box.classList.add('stick');
               
            } else { 
                box.classList.remove('stick');
            }

        });

        // Oberserver - creates effect where nav bolds when it crosses the boundary of its related h4
        let observer;

        function setupIntersectionObserver() {
          // Disconnect existing observer if it exists
          if (observer) {
            observer.disconnect();
          }

          // Array of links
          const tocLinks = document.querySelectorAll('.list-wrapper li a');
          // Mapping links to sections
          const sections = Array.from(tocLinks)
            .map(link => document.querySelector(link.getAttribute('href')))
            .filter(Boolean); // Ensure sections exist

          // Callback function to handle the intersections
          const handleIntersect = (entries, observer) => {
            entries.forEach(entry => {
              if (entry.isIntersecting) {
                // Clear previous active list items
                const tocListItems = document.querySelectorAll('.list-wrapper li');
                tocListItems.forEach(li => li.classList.remove('active'));

                const activeLink = document.querySelector(`.list-wrapper li a[href="#${entry.target.id}"]`);
                if (activeLink) {
                  activeLink.parentElement.classList.add('active');
                }
              }
            });
          };


                    /* 
                    * 
                    *   PROGRAMMATIC - eventually, pass in rootMargin and threshold
                    * 
                    */ 


          // Set up the observer with better margins and thresholds
          const options = {
            rootMargin: '-10px 0px 0px 0px', // Adjust the top margin to handle elements near the top of the page
            threshold: 0.1 // Consider multiple thresholds
          };

          observer = new IntersectionObserver(handleIntersect, options);

          // Observe each section
          sections.forEach(section => observer.observe(section));
        }

        // Reinitialize the observer on page load and when scrolling to the top
        window.addEventListener('load', setupIntersectionObserver);
        window.addEventListener('scroll', () => {
          if (window.scrollY === 0) {
            setupIntersectionObserver();
          }
        });


        /* MOBILE EXPERIENCE sigh */


        /*  PUSHING ITEMS TO AN ARRAY   */

        // Assuming you have an array of <li> elements
        // let listItems = Array.from(document.querySelectorAll('#myList li'));

        // Create a new <li> element
        // const newListItem = document.createElement('li');
        // newListItem.textContent = 'New Item';

        // Add (push) the new <li> to the array
        // listItems.push(newListItem);

        // Now listItems includes the new <li> element
        // console.log(listItems); // The array now contains the new <li> element at the end


        /* ADMIN  */

        //https://medium.com/@hbahonar/how-to-create-wordpress-custom-admin-page-and-menu-from-scratch-ultimate-guide-updated-d7b4d2e57f96

        // https://developer.wordpress.org/plugins/settings/






    });
    </script>
    <?php
    }
}

add_action('wp', 'waypoint826_run');






