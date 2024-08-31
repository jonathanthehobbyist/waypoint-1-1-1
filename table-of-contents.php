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

add_action('wp_enqueue_scripts', 'waypoint826_enqueue_styles');


function wporg_custom_box_html( $post ) {

    /*  
            
        HOW TO ADD AN ADMIN - places to look
        • Add to - Retreive value for the admin field
        • NOTE: use Waypoint_ naming structure
        • Add to - Add input field
        • Add to - Define the field in $fields
        • Add to - it in the forEach loop in wporg_post_savedata()
        • Add to - it just before the DOMContentLoaded function

    */


    // LIST OF ALL ADMIN functions goes here

    // Retrieve current value for the checkbox field
    $checkbox_value = get_post_meta( $post->ID, '_waypoint_enable_for_post', true );
    $checkbox_value_H2 = get_post_meta( $post->ID, '_waypoint_H2_enable', true );
    $checkbox_value_H3 = get_post_meta( $post->ID, '_waypoint_H3_enable', true );
    $checkbox_value_H4 = get_post_meta( $post->ID, '_waypoint_H4_enable', true );
    $checkbox_value_H5 = get_post_meta( $post->ID, '_waypoint_H5_enable', true );
    $field_value_add_to = get_post_meta( $post->ID, '_waypoint_add_to_page', true );

    // Add a nonce field for security
    wp_nonce_field( 'wporg_save_postdata', 'wporg_nonce' );

    ?>

    <input type="checkbox" id="waypoint_enable_for_post" name="waypoint_enable_for_post" value="1" <?php checked( $checkbox_value, '1' ); ?>>
    <label for="waypoint_enable_for_post">Enable Waypoint => Table of Contents</label><br>

    <hr>

    <div class="">
        <p>Include in table of contents</p>
        <input type="checkbox" id="waypoint_H2_enable" name="waypoint_H2_enable" value="1" <?php checked( $checkbox_value_H2, '1' ); ?>>
        <label for="waypoint_H2_enable">H2</label><br>

        <input type="checkbox" id="waypoint_H3_enable" name="waypoint_H3_enable" value="1" <?php checked( $checkbox_value_H3, '1' ); ?>>
        <label for="waypoint_H3_enable">H3</label><br>
        <input type="checkbox" id="waypoint_H4_enable" name="waypoint_H4_enable" value="1" <?php checked( $checkbox_value_H4, '1' ); ?>>
        <label for="waypoint_H4_enable">H4</label><br>
        <input type="checkbox" id="waypoint_H5_enable" name="waypoint_H5_enable" value="1" <?php checked( $checkbox_value_H5, '1' ); ?>>
        <label for="waypoint_H5_enable">H5</label><br>

    </div>

    <hr>

    <div class="">
        <p></p>
        <label for="waypoint_add_to_page">Specify a class or ID to attach to</label><br>
        <input type="text" id="waypoint_add_to_page" name="waypoint_add_to_page" value="<?php echo esc_attr( $field_value_add_to ); ?>">

        <!-- .post-content for example -->
        <p></p>
        
        <br />

        <form>
          <label for="selection">Choose an option:</label>
          <select id="selection" name="selection">
            <option value="class">Class</option>
            <option value="id">ID</option>
          </select>
        </form>


        <p></p>
    </div>

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

  // Define an array of fields to save
    $fields = [
        'waypoint_enable_for_post',
        'waypoint_H2_enable',
        'waypoint_H3_enable',
        'waypoint_H4_enable',
        'waypoint_H5_enable',
        'waypoint_add_to_page',
        //'wporg_field_three',
        // Add more fields as needed
    ];

    foreach ( $fields as $field ) {
           if ( $field === 'waypoint_enable_for_post' ) {
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_enable_for_post'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_enable_for_post',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");

        } else if ( $field === 'waypoint_H2_enable' ) { //H2
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_H2_enable'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_H2_enable',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");


        } else if ( $field === 'waypoint_H3_enable' ) { //H3
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_H3_enable'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_H3_enable',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");


        } else if ( $field === 'waypoint_H4_enable' ) { //H4
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_H4_enable'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_H4_enable',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");


        } else if ( $field === 'waypoint_H5_enable' ) { //H5
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_H5_enable'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_H5_enable',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");


        } else if ( $field === 'waypoint_add_to_page' ) { //H5
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_add_to_page'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_H5_enable',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");

        }
            // Handle regular text fields
            if ( array_key_exists( $field, $_POST ) ) {
                $sanitized_value = sanitize_text_field( $_POST[$field] );
                update_post_meta(
                    $post_id,
                    '_' . $field,  // Use the field name as the meta key
                    $sanitized_value
                );
                error_log("$field saved with sanitized value: $sanitized_value");

            } else {
                error_log("$field not found in POST data.");
            }
        }
    }

add_action( 'save_post', 'wporg_save_postdata' );

/*
*
*
*   NOTE - admin pages are not post or page types and so running an admin vs. a user-facing page yields different results
*
*
*/

function waypoint826_run() {
            //error_log("inside waypoitn826_run");
            
            // Output the JavaScript in the footer of the page
            function custom_checkbox_js() {

                if (is_singular('post') || is_singular('page') || is_singular('portfolio')) {
                    
                    $post_id = get_the_ID(); 

                    // To get values, go get function wporg_custom_box_html()
                    $checkbox_state = get_post_meta($post_id, '_waypoint_enable_for_post', true);
                    $field_state = get_post_meta($post_id, '_wporg_field', true);

                    if ($checkbox_state === '1') {
                        error_log("checkbox state php $checkbox_state");
                        $post = $post_id;
                    }
               
                    ?>

                    <script type="text/javascript">
                        function run_state () {
                        
                            var postId = <?php echo json_encode($post_id); ?>;
                            var checkboxState = <?php echo json_encode($checkbox_state); ?>;

                            if (checkboxState === '1') {
                                console.log('Checkbox is checked on post ID:', postId);
                                applyCustomFunction(postId);
                            } else {
                                console.log('Checkbox is not checked on post ID:', postId);
                            }

                            function applyCustomFunction(postId) {
                                //alert('Checkbox is checked for post ID: ' + postId);
                            }
                        };

                        run_state();
                    </script>

                    <?php
                }
            }
        add_action('wp_footer', 'custom_checkbox_js');

    /*  PROGRAMMATIC - Pass in post-id or page-id for enabled   */ 

    global $post;

    $post_id = get_the_ID(); 

    // To get values, go get function wporg_custom_box_html()
    $checkbox_state = get_post_meta($post_id, '_waypoint_enable_for_post', true);

    // To get values, go get function wporg_custom_box_html()
    $checkbox_value_H2 = get_post_meta( $post_id, '_waypoint_H2_enable', true );
    $checkbox_value_H3 = get_post_meta( $post_id, '_waypoint_H3_enable', true );
    $checkbox_value_H4 = get_post_meta( $post_id, '_waypoint_H4_enable', true );
    $checkbox_value_H5 = get_post_meta( $post_id, '_waypoint_H5_enable', true );
    $field_value_add_to = get_post_meta( $post_id, '_waypoint_add_to_page', true );

    if (is_page() || is_single() && $checkbox_state === '1' ) {

    ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {

        // Transfer php var into js var
        // Change into a number
        let waypointH2 = <?php echo json_encode($checkbox_value_H2); ?>;
        let wph2 = parseFloat(waypointH2);
        let waypointH3 = <?php echo json_encode($checkbox_value_H3); ?>;
        let wph3 = parseFloat(waypointH3);
        var waypointH4 = <?php echo json_encode($checkbox_value_H4); ?>;
        let wph4 = parseFloat(waypointH4);
        var waypointH5 = <?php echo json_encode($checkbox_value_H5); ?>;
        let wph5 = parseFloat(waypointH5);

        // USER CONFIGURE
        // a class (for now) or ID (later) to add waypoint826-main to 
        let waypointFieldAddTo = <?php echo json_encode($field_value_add_to); ?>;
        console.log(waypointFieldAddTo);

        // Create the main container to hold the waypoint table of contents
        let mainContainer = document.createElement('div');
        mainContainer.className = 'waypoint826-main';
        mainContainer.id = 'waypoint826-primary-container';

        // var contentArea = document.querySelector('.main-wrapper'); // original content area (for home page)

        // Append the main waypoint container to a DIV element on the page
        var contentArea = document.querySelector('.' + waypointFieldAddTo);
        contentArea.appendChild(mainContainer);

        // USER CONFIGURE - which of the h2, h3, h4, h5 gets passed

        // Create array
        let waypointArr = [];

        //push into array if the checkbox is checked ( == 1)
        if (wph2 == '1') waypointArr.push("h2");
        if (wph3 == '1') waypointArr.push("h3");
        if (wph4 == '1') waypointArr.push("h4");
        if (wph5 == '1') waypointArr.push("h5");

        // Create the list h2,h3,h4,h5 based on values passed from the page or post admin
        var headings = document.querySelectorAll(waypointArr.join(", "));

        // Iterate over waypointArr instead of headings
        waypointArr.forEach(function(selector, index) {
            var heading = document.querySelector(selector); // Get the first matching element for this selector
            if (heading) {
                var newValue = 'newValue_' + index; // Example new value
                //console.log('Original Selector:', selector, 'New Value:', newValue);
            } else {
                // console.log('No heading found for selector:', selector);
            }
        });

        // Array to hold the associations
        var associatedElements = [];

        // Iterate over each heading and find its corresponding selector
        headings.forEach(function(heading) {
            // Iterate over the selectors to find which one matches the current heading
            waypointArr.forEach(function(selector) {
                // Check if the heading matches the selector (e.g., by tag name)
                if (heading.matches(selector)) {
                    associatedElements.push({
                        selector: selector,
                        element: heading
                    });
                    //console.log('Selector:', selector, 'Element:', heading);
                }
            });
        });

        // var headings = document.querySelectorAll("h2, h3, h4");
        const list = document.createElement('ol');
        list.classList.add('list-wrapper');

        // Create a header or title area
        var contentParagraph = document.createElement('p');
        contentParagraph.className = "content";
        contentParagraph.innerHTML = "Contents";
        // use this .appendChild(contentParagraph);

        // the map method creates a new array populated with the results of calling a provided function on every element in the calling array
        // 
        let valuesOfHeadings = waypointArr.map(function(heading) {
            return parseInt(heading.replace('h', ''), 10);
        });

        /*  Indentation settings  */

        // Find the arraylength
        var numberOfHeadings = valuesOfHeadings.length;
        //console.log(numberOfHeadings);

        // Find the highest level H number (smallest number)
        var topLevel = Math.min(...valuesOfHeadings);
        //console.log(topLevel);

        // Find the loest level H number (highest number)
        var bottomLevel = Math.max(...valuesOfHeadings);
        //console.log(topLevel);

        // if every H is selected, h2, h3, h4, h5
        if (numberOfHeadings == 4) {
            valuesOfHeadings.sort(function(a, b) {
                return a - b;
            });
        }

        // Set the base margin, this could be user CONFIGURABLE eventually (ask if they want nesting)
        var baseMargin = 8;

        associatedElements.forEach(function(item) {

            var selector = item.selector; // The selector (h2, h3, etc)
            var element = item.element; // The DOM element

            // Duplicates how the h2, h3, h4 is written - 'dirty version'
           var innerContent = element.innerText;
           //console.log(innerContent);

           // Cleans up the string to make it into a usable class name / on-page anchor link
           var str = innerContent;
           str = str.replace(/^\s/g, ''); //matches any space at the beginning of an input
           str = str.replace(/\s+/g, '-'); //matches 1 or more spaces and converts to a dash
           str = str.replace(/[1234567890?\u201c\u201d.!\#',’>\:\;\=<_~`/"\(\)&+%^@*]/g, '').toLowerCase(); //matches 
           // Takes h2 innerHTML, replaces spaces (1) with dashes, (2) replaces all other banned digitals with nothing, and (3)makes it lowercase

           //console.log(str);

           // Assign a unique ID to the h2, h3, h4 tag based on its position
            element.id = str;
            
            // Create a list item and link for each h2, h3, h4
            const listItem = document.createElement('li');
            const link = document.createElement('a');
            link.href = "#" + str;
            link.innerHTML = innerContent;

            // Add a class that says whether this came from an h2, h3, h4, or h5 elem
            listItem.classList.add(item.selector + '_selector');

            switch (numberOfHeadings) {

                    case '1':
                        break;

                    case '2':
                        //highest number IE bottomLevel gets a margin of 8px assigned
                        if(item.selector == bottomLevel) listItem.style.marginLeft = (baseMargin * 1) + "px";
                        break;
                        
                    case '3':
                        // The topLevel - 1 (middle level) gets a base*1 margin
                        if(item.selector != bottomLevel && item.selector != topLevel) { 
                            listItem.style.marginLeft = (baseMargin * 1) + "px";
                        } else if (item.selector == bottomLevel) {
                        // The topLevel - 2 gets a base*2 margin 
                         listItem.style.marginLeft = (baseMargin * 2) + "px";
                        }
                        break;

                    case '4':
                        // TopLevel - 1 gets a base*1 margin
                        // TopLevel - 2 gets a base*2 margin
                        // TopLevel - 3 gets a base*3 margin

                        if(item.selector == valuesOfHeadings[1]) { //topLevel -1
                            listItem.style.marginLeft = (baseMargin * 1) + "px";
                        } else if (item.selector == valuesOfHeadings[2]) { //topLevel -2
                            listItem.style.marginLeft = (baseMargin * 2) + "px";
                        } else if (item.selector == bottomLevel) { //topLeft -3
                         listItem.style.marginLeft = (baseMargin * 3) + "px";
                        }
                        break;
            }

            // append
            listItem.appendChild(link);
            list.appendChild(listItem);

        }); //end for loop

        /*   Placing Waypoint826 table of contents into the structure of the page    */
        
        if (mainContainer) {
             // If parent div has first child, insert mainContainer before first child
            if (mainContainer.firstChild) {
                 mainContainer.insertBefore(list, mainContainer.firstChild);
            } else {
                // If mainContainer has 0 children, append
                mainContainer.appendChild(list);
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
            //console.log(marginLeftValue);

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
        mainContainer.style.top = (distanceFromTop.y - 124 + 'px');
        //console.log(distanceFromTop);

        var box = document.getElementById('waypoint826-primary-container'),
        top = box.offsetTop;

        window.addEventListener('scroll', function(event) {
            //console.log('Page scrolled!');

            var y = document.documentElement.scrollTop || document.body.scrollTop;
            //console.log(y);

            // Get element that mainContainer was attached to
            // waypointFieldAddTo

            // Assuming there is only one element with this class
            var element = document.querySelector('.' + waypointFieldAddTo);

            if (element) {
                var distanceFromTop = element.getBoundingClientRect().top + window.scrollY;
                console.log('Distance from top: ' + distanceFromTop + 'px');
            }

            if ( (y - distanceFromTop) >= top) { 
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






