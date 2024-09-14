<?php
/**
 * Plugin Name: Waypoint 826 - Table of Contents
 * Description: Adds a table of contents to select pages and posts based on h2, h3 and h4 headings
 * Author: Jon Simmons
 * Version: 1.0.0
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
        • NOTE: use Waypoint_ naming structure

        • Add to - Add input field
        • Add to - Retreive value for the admin field
        • Add to - Define the field in $fields
        • Add to - it in the forEach loop in wporg_post_savedata()
        • Add to - it just before the DOMContentLoaded function to transition from php to js variables

    */


    /*
        ADMIN - what else is needed? 

        POSITION

        - DONE: need an element to attach to on the right - individual
        - DONE: need to adjust the center DIV right if margin: 0 auto;
        - DONE: peg to top element
        - DONE: Additional right margin for mainContainer
        - What happens in the case of sticky headers?

        SETTINGS PAGE
        - Masthead
        - Background color

        STYLE

        - background color / transparent - individ.
        - Selection color - settings
        - Color of normal type - settings
        - Border right style (or not at all) - settings
        - Typeography - settings
        - Make base margin user configurabl - settings

        INDIVIDUAL POST/PAGE vs. SETTINGS

        CLEAN UP
        - DONE: Nicer animation of Waypoint moving around after a window resize
        - Probably needs to be scrollable in case it goes off the page

        DEBUG
        - DONE: There's a bug when you zoom to the top of the page, might be around my implementation of the header. Might need to save that value as a unchangeable variatble rarther than recalculating each time 

    */


    // LIST OF ALL ADMIN functions goes here

    // Retrieve current value for the checkbox field
    $checkbox_value = get_post_meta( $post->ID, '_waypoint_enable_for_post', true );
    $checkbox_value_H2 = get_post_meta( $post->ID, '_waypoint_H2_enable', true );
    $checkbox_value_H3 = get_post_meta( $post->ID, '_waypoint_H3_enable', true );
    $checkbox_value_H4 = get_post_meta( $post->ID, '_waypoint_H4_enable', true );
    $checkbox_value_H5 = get_post_meta( $post->ID, '_waypoint_H5_enable', true );

    // Intro li at top
    $checkbox_value_intro = get_post_meta( $post->ID, '_waypoint_intro_enable', true );

    $field_value_masthead_define = get_post_meta( $post->ID, '_waypoint_masthead_define', true );

    $field_value_add_to = get_post_meta( $post->ID, '_waypoint_add_to_page', true );
    $field_value_align_to_element = get_post_meta( $post->ID, '_waypoint_align_to_element', true );

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
        <br>
        <hr>
        <input type="checkbox" id="waypoint_intro_enable" name="waypoint_intro_enable" value="1" <?php checked( $checkbox_value_intro, '1' ); ?>>
        <label for="waypoint_intro_enable">H5</label><br>
    </div>

    <hr>

    <div class="">
        <p></p>
        <label for="waypoint_add_to_page">Specify a class or ID - we will append waypoint as a child to this element</label><br>
        <input type="text" id="waypoint_add_to_page" name="waypoint_add_to_page" value="<?php echo esc_attr( $field_value_add_to ); ?>">
        <!-- no dot necessary for class name -->
        <!-- .post-content for example -->
        <p></p>
        
        <br />

        <!--form>
          <label for="selection">Choose an option:</label>
          <select id="selection" name="selection">
            <option value="class">Class</option>
            <option value="id">ID</option>
          </select>
        </form-->
        <label for="waypoint_align_to_element">Enter a class name of a tag with main content - we will use this to calculate the width of the main content</label><br>
        <input type="text" id="waypoint_align_to_element" name="waypoint_align_to_element" value="<?php echo esc_attr( $field_value_align_to_element ); ?>">

        <!-- this one needs a viewport width backup -->

        <!-- no dot necessary -->
        <!-- This actually moves Waypoint closer to the attached element rather than doing anything with vertical position -->
        <!-- it also can accept multiple classes separated by a space -->

        <p></p>
        <br />
            <label for="waypoint_masthead_define">Define a masthead by ID or class(es) - we will use this to determine waypoint's position.top - generally aligning waypoint's top with the bottom of the menubar / header</label><br>
                <input type="text" id="waypoint_masthead_define" name="waypoint_masthead_define" value="<?php echo esc_attr( $field_value_masthead_define ); ?>">
        
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
        'waypoint_intro_enable',
        'waypoint_masthead_define',
        'waypoint_add_to_page',
        'waypoint_align_to_element',
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


        } else if ( $field === 'waypoint_intro_enable' ) { // Intro enable
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_intro_enable'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_intro_enable',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");


        } else if ( $field === 'waypoint_masthead_define' ) { // Masthead field
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_masthead_define'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_masthead_define',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");


        } else if ( $field === 'waypoint_add_to_page' ) { // Add to page
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_add_to_page'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_add_to_page',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");

        } else if ( $field === 'waypoint_align_to_element' ) { // Align to element
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_align_to_element'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_align_to_element',
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

                        // may not need this, I think it's just a verification / test function

                        function run_state () {
                        
                            var postId = <?php echo json_encode($post_id); ?>;
                            var checkboxState = <?php echo json_encode($checkbox_state); ?>;

                            if (checkboxState === '1') {
                                applyCustomFunction(postId);
                            } else {
                                
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

    /*  ----------- VARIABLES, USER CONFIGURATION ----------  */

    global $post;

    $post_id = get_the_ID(); 

    // To get values, go get function wporg_custom_box_html()
    $checkbox_state = get_post_meta($post_id, '_waypoint_enable_for_post', true);

    // To get values, go get function wporg_custom_box_html()
    $checkbox_value_H2 = get_post_meta( $post_id, '_waypoint_H2_enable', true );
    $checkbox_value_H3 = get_post_meta( $post_id, '_waypoint_H3_enable', true );
    $checkbox_value_H4 = get_post_meta( $post_id, '_waypoint_H4_enable', true );
    $checkbox_value_H5 = get_post_meta( $post_id, '_waypoint_H5_enable', true );
    $checkbox_value_intro = get_post_meta( $post_id, '_waypoint_intro_enable', true );


    $field_value_masthead_define = get_post_meta( $post_id, '_waypoint_masthead_define', true );

    $field_value_add_to = get_post_meta( $post_id, '_waypoint_add_to_page', true );
    $field_value_align_to_element = get_post_meta( $post_id, '_waypoint_align_to_element', true );

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

        /*  ------  USER CONFIGURABLE  ----------  */
        // Enable intro at top of list
        var waypointIntroEnable = <?php echo json_encode($checkbox_value_intro); ?>;

        // a class (for now) or ID (later) to add waypoint826-main to 
       let waypointFieldAddTo = <?php echo json_encode($field_value_add_to); ?>;
       // console.log(waypointFieldAddTo);

       // Horizontal alignment to an element
       let waypointFieldAlignToElement = <?php echo json_encode($field_value_align_to_element); ?>;

       // Define a masthead or menu
       let waypointMasthead = <?php echo json_encode($field_value_masthead_define); ?>;

       /*  ----------- CREATE WAYPOINT CONTAINTER ----------  */

        // Create the main container to hold the waypoint table of contents
        let mainContainer = document.createElement('div');
        mainContainer.className = 'waypoint826-main';
        mainContainer.id = 'waypoint826-primary-container';

        // Append the main waypoint container to a DIV element on the page
        var entirePage = document.querySelector('.' + waypointFieldAddTo);
        entirePage.appendChild(mainContainer);

        /*  ----------- USER CONFIGURABLE ----------  */

        waypointFieldAlignToElement = waypointFieldAlignToElement.trim();
        // console.log('waypointFieldAlignToElement: ' + waypointFieldAlignToElement);

        // Because waypointFieldAlignToElement might have more than one class so...
        const checkAndCombineField = "." + waypointFieldAlignToElement.replace(/ /g, '.'); // replaces spaces with dots

        var contentArea = document.querySelector(checkAndCombineField);
        //console.log('this is contentArea: ' + contentArea);

        /*  ------  USER CONFIGURABLE  ----------  */

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

        // 
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

        /*  ----------- SECTION: LEFT MARGIN FOR h2, h3, h4, h5 ----------  */

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


        /*  ----------- BASE MARGIN ----------  */

        // Set the base margin, this could be user CONFIGURABLE eventually (ask if they want nesting)
        // Cascades to other settings

        var baseMargin = 8;



        /*  ----------- BUILDING THE LIST CONTENT ----------  */

        associatedElements.forEach(function(item) {

            var selector = item.selector; // The selector (h2, h3, etc)
            var element = item.element; // The DOM element

            // Duplicates how the h2, h3, h4 is written - 'dirty version'
            // Currently keeps the exact formatting IE uppercase, all caps etc. 
           var innerContent = element.innerText;
           // console.log('innerContent ' + innerContent);

           // Cleans up the string to make it into a usable class name / on-page anchor link
           var str = innerContent;
           str = str.replace(/^\s/g, ''); //matches any space at the beginning of an input
           str = str.replace(/\s+/g, '-'); //matches 1 or more spaces and converts to a dash
           str = str.replace(/[1234567890?\u201c\u201d.!\#',’>\:\;\=<_~`/"\(\)&$+%^@*]/g, '').toLowerCase(); //matches 
           // Takes h2 innerHTML, replaces spaces (1) with dashes, (2) replaces all other banned digitals with nothing, and (3)makes it lowercase

           // First, define the list of words to exclude
           const excludeWords = /(privacy|security|gdpr)/i; // i makes it case-insensitive

           // Next, look at the parents to see if its a GDPR or privacy notice
           const parentLevelOne = element.parentElement
           const parentLevelTwo = parentLevelOne.parentElement;
           const parentLevelThree = parentLevelTwo.parentElement;

           const parentOneClass = parentLevelOne.className;
           const parentTwoClass = parentLevelTwo.className;
           const parentThreeClass = parentLevelThree.className;
           //console.log('Parent class 1: ' + parentOneClass + 'Parent class 2: ' + parentTwoClass + 'Parent class 3: ' + parentThreeClass  );

           // now exclude 
           if ( excludeWords.test(parentOneClass) || excludeWords.test(parentTwoClass) || excludeWords.test(parentThreeClass) ) {
            return;
           }

           // Assign a unique ID to the h2, h3, h4 tag based on its position
            element.id = str;
            
            // Create a list item and link for each h2, h3, h4
            const listItem = document.createElement('li');
            const link = document.createElement('a');
            link.href = "#" + str;
            // console.log(str);
            link.innerHTML = innerContent.toLowerCase();

            // Add a class that says whether this came from an h2, h3, h4, or h5 elem
            listItem.classList.add(item.selector + '_selector');
            //console.log(numberOfHeadings);

            var breakDownSelector = parseInt(selector.replace('h', ''),10);


            /*  ----------- LOGIC FOR LEFT MARGIN FOR h2, h3, h4, h5 ----------  */

            switch (numberOfHeadings) {

                    case 1:
                        break;

                    case 2:
                        // console.log('2');
                        // Highest number IE bottomLevel gets a margin of 8px assigned
                        // Logic to if: when breakdownselector equals bottomlevle, set the leftmargin of the li to baseMargin (8)
                        if(breakDownSelector == bottomLevel) listItem.style.marginLeft = (baseMargin * 1) + "px";
                        //console.log('bottomLevel: ' + bottomLevel + '  breakDownSelector: ' + breakDownSelector);
                        break;
                        
                    case 3:
                        //console.log('3');
                        // The topLevel - 1 (middle level) gets a base*1 margin
                        if(breakDownSelector != bottomLevel && breakDownSelector != topLevel) { 
                            listItem.style.marginLeft = (baseMargin * 1) + "px";
                        } else if (selector == bottomLevel) {
                        // The topLevel - 2 gets a base*2 margin 
                         listItem.style.marginLeft = (baseMargin * 2) + "px";
                        }
                        break;

                    case 4:
                        //console.log('item selector: ' + selector + ' valuesOfH: ' + valuesOfHeadings[1]);
                        if(breakDownSelector == valuesOfHeadings[1]) { //topLevel -1
                            //console.log('item selector:' + selector + 'valuesOfH' + valuesOfHeadings);
                            //console.log("topLevel - 1");
                            listItem.style.marginLeft = (baseMargin * 1) + "px";
                        } else if (breakDownSelector == valuesOfHeadings[2]) { //topLevel -2
                            listItem.style.marginLeft = (baseMargin * 2) + "px";
                        } else if (breakDownSelector == bottomLevel) { //topLeft -3
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

        var initPaddingLeft = window.getComputedStyle(mainContainer).paddingLeft;

        // Set the right-hand position of the waypoint826 plugin
        function positionMainContainer() {

            mainContainer.style.opacity = '0.2';

            /*  ----------- WINDOW RESIZE & HORZ. ALIGNMENT ----------  */

            /*  -----------  USER CONFIGURABLE  ----------  */

            // User can choose an element to align Waypoint to horizontally
            if ( waypointFieldAlignToElement ) {

                // Class or ID
                // Remove spaces and add a dot '.' from the class or ID data passed from the user
                var alignElement = waypointFieldAlignToElement.replace(/ /g, '.');

                // DOM element of class or ID 
                // .main-container .row-container
                const contentElement = document.querySelector('.' + alignElement);

                // Get the computed styles for the contentElement
                const computedStyle = window.getComputedStyle(contentElement);

                // Access the left margin
                const marginLeft = computedStyle.marginLeft;
                //console.log(marginLeft);
                const marginTop = computedStyle.marginTop;
                //console.log(marginTop);
                // Computer style returns a string not a number

                const marginLeftValue = parseFloat(computedStyle.marginLeft);
                var marginTopValue = parseFloat(computedStyle.marginTop);

                // Starts with a width of 250px defined in the style sheet
                var elementWaypoint = document.querySelector('.waypoint826-main');

                // Get the right position of the elementWaypoint 
                // AM I USING THIS? IF not, delete
                var waypointLeftEdge = elementWaypoint.getBoundingClientRect().right;

                // 
                var elemWaypointWidth = window.getComputedStyle(elementWaypoint).width;

                // Remove 'px' REGEX
                var cleanElemWaypointWidth = elemWaypointWidth.replace(/px/g, '');

                if (waypointLeftEdge == '0') {
                    
                    mainContainer.style.display = 'none';

                    // TESTING
                    // console.log('right position is zero via IF statement');
                }

                // console.log('Right position of Waypoint:', waypointLeftEdge + 'px');
                // For CONTENT

                // If user has defined a content area
                if (contentArea) {
                    
                    var elemContentWidth = window.getComputedStyle(contentArea).width;
                    var cleanElemContentWidth = elemContentWidth.replace(/px/g, '');

                    // Get the left position of the main content area - we should probably check to see if this is absolutely positioned
                    var contentLeftEdge = contentArea.getBoundingClientRect().left;
                    console.log('Left edge of content contentLeftEdge' + contentLeftEdge);

                    // TESTING
                    // console.log('Content width is' + elemContentWidth);
                    // console.log('Left edge of the main content area: ' + contentLeftEdge);
                    // console.log('contentArea is defined ' + contentArea);
                    // console.log('Left position: of maincontent', contentLeftEdge + 'px');

                } else {

                    // TESTING
                    // console.log('contentArea not found ' + contentArea);
                }

                /*  ----------- ONE-TIME LEFT POSITIONING ----------  */

                // Viewport width
                var viewportWidth = window.innerWidth;

                // Calculating space on the margins around the content
                // What if used hasn't defined contentArea? 
                var spaceForWaypoint = (viewportWidth - cleanElemContentWidth);

                // Waypoint width as a number
                var waypointSpaceNeeded = (Number(cleanElemWaypointWidth));

                // TEST
                // console.log('View port width ' + viewportWidth + ' Main content width ' + cleanElemContentWidth);
                // console.log('spaceForWaypoint: ' + spaceForWaypoint);

                // Set style
                mainContainer.style.transition = 'opacity 0.5s ease-out, visibility 0.5s ease-out';

                // Needed space: Waypoint is 250px wide (could be 200px) so 250 on each side, plus 20px margins x 4 (another 80px)

                // Since mainConatiner starts as pos:absolute, find the static positioned partent to find the true distance from waypoint to the left part of the screen
                function findPositionedParent(element) {
                    // Start with the parent of the element
                    let parent = mainContainer.parentElement;

                    // Traverse up the DOM tree
                    while (parent) {
                        // Get the computed style of the parent
                        const style = window.getComputedStyle(parent);

                        // Check if the parent has a position other than 'static'
                        if (style.position !== 'static') {
                            return parent; // This is the nearest positioned parent
                        }

                        // Move to the next parent element
                        parent = parent.parentElement;
                    }

                    // If no positioned parent is found, return null
                    return null;
                } // END FINDPOSITIONEDPARENT()

                // Get actual value for waypoint
                const absoluteElement = document.querySelector('.waypoint826-main'); 
                const positionedParent = findPositionedParent(absoluteElement);

                // Get the computed style of the content element
                let computedStyleContent = window.getComputedStyle(contentElement);

                // Get the display value
                let displayValue = computedStyleContent.position;

                // console.log('Display value:', displayValue);

                if ( displayValue != 'absolute') {
                    // Make sure content isn't absolutely positioned
                    //console.log('not absolute');
                }



                if (positionedParent) {

                    // True distance from the left viewport edge
                    var parentLeft = positionedParent.getBoundingClientRect().left;

                    //console.log('Positioned Parent left ' + parentLeft);
                } else {
                    //console.log('No positioned parent found, using the body/document.');
                }

                if (parentLeft){
                    var adjustMargin = (parseFloat(parentLeft));
                } else {
                    var adjustMargin = 0;
                }

                // Test waypoint's display
                // Get the computed style of the content element
                let computedStyleWaypoint = window.getComputedStyle(mainContainer);
                // Get the display value
                var displayValueWaypoint = computedStyleWaypoint.position;

                // console.log('Waypoint is position: ' + displayValueWaypoint);
                console.log('spaceForWaypppoint: ' + spaceForWaypoint);


                if ( spaceForWaypoint < 580) {

                    // Waypoint displays NONE
                    mainContainer.style.display = 'none';

                } else if ( spaceForWaypoint > 620) {

                    // Waypoint displays BLOCK, 200 width
                    mainContainer.style.display = 'block';
                    mainContainer.style.width = '230px';

                    // Width of Waypoint, as a number
                    var offset = parseFloat(mainContainer.offsetWidth); // Number of pixels to offset

                    // Waypoint positioning
                    if (displayValueWaypoint == 'absolute') {

                        // waypoint is ABSOLUTE and if adjustMargin exists
                        if (adjustMargin) {

                            // Account for parent's distance to viewport edge
                            var leftAdjustCalc = (contentLeftEdge - offset - (baseMargin * 5) + adjustMargin) + 'px';

                        } else { }

                    } else { // waypoint is FIXED
                        var leftAdjustCalc = (contentLeftEdge - offset - (baseMargin * 5)) + 'px';
                        // console.log('FIXED - calculated left adjustment in ELSE: ' + leftAdjustCalc);
                    }

                    mainContainer.style.left = leftAdjustCalc;
                    

                } // Cut an else if and put it into notes

                // TEST 
                var newWidth = window.getComputedStyle(mainContainer).width;
                // console.log('Width of the main container' + newWidth);

            } else {

            } // END if ( waypointFieldAlignToElement ) {

            // Start the pulse for 5 seconds
            startPulse(1500);

            /*  ----------- POSITION TO TOP ----------  */

            // searches for # or .
            const elementHasID = /#/g;
            const elementHasClassEs = /\./g; 

            // Test, find, replace and create var menuHeight
            if (elementHasID.test(waypointMasthead)) {
               
               //console.log("true");
               var elementIDName = String(waypointMasthead.replace('#', ''));
               var refToMasthead = document.getElementById(elementIDName);
               // console.log('refToMasthead' + refToMasthead);

            } else if (document.getElementById('masthead')) {

                // Test to see if there's a masthead
                var refToMasthead = document.getElementById('masthead'); //outputs an HTMLCollection
            }

            // default to height of the header element with a user configurable override
            if (refToMasthead) {
                var distanceFromTop = refToMasthead.getBoundingClientRect().height;
            }

            //console.log('Height of menu ' + distanceFromTop + 'px');
            mainContainer.style.top = ('0px');

            var box = document.getElementById('waypoint826-primary-container');
            let top = box.offsetTop;

            var widthOfWaypoint = window.getComputedStyle(mainContainer).width;
            var widthOfWaypoint = parseFloat(widthOfWaypoint);
            // may not need

            let calcLeftFixed = (parseFloat(leftAdjustCalc) + parseFloat(adjustMargin));
            let calcLeftAbsolute = (parseFloat(leftAdjustCalc));


            /*  ----------- SCROLL FUNCTION ----------  */

            var positionVar;

            window.addEventListener('scroll', function(event) {
                
                var y = document.documentElement.scrollTop || document.body.scrollTop;

                // Assuming there is only one element with this class
                var element = document.querySelector('.' + waypointFieldAddTo);

                // Already doing some calculations around the element that Waypoint is added to ...

                





                if (element) {
                    var distanceFromTop = element.getBoundingClientRect().top + window.scrollY;
                    //console.log('Distance from top: ' + distanceFromTop + 'px');
                }

                // (y) how far page scrolled minus how far elem is from top of page, great than offset of box from containing elem

                if ( (y - distanceFromTop) >= top ) {  // Is at the viewport top

                    /*  ----------- LEFT POSITIONING ON SCROLL EVENT ----------  */
                    
                    if (!box.classList.contains('stick')) { // doesn't contain stick = ABSOLUTE positioning

                        // Add class
                        box.classList.add('stick'); // Now its FIXED

                        // Apply adjustment
                        mainContainer.style.left = (calcLeftFixed) + 'px';

                    }  
                   
                } else { 

                    if (box.classList.contains('stick')) { // FIXED positioning

                        // Remove class
                        box.classList.remove('stick'); // Now its ABSOLUTE, position relative to a STATIC parent

                        // Apply adjustment
                        mainContainer.style.left = leftAdjustCalc;
                       
                    }
                }

            }); // End scroll function

        } // end positionMainContainer

        /*--------  End positionMainContainer  -----------*/

        // Pulse effect with JavaScript
            function startPulse(duration) {
                let isFading = false;
                let intervalId;

                // Start the interval to alternate opacity every 500ms
                intervalId = setInterval(() => {
                    if (isFading) {
                        mainContainer.style.opacity = '.5'; // Fully visible
                    } else {
                        mainContainer.style.opacity = '0.3'; // Semi-transparent
                    }
                    isFading = !isFading;
                }, 250); // Change opacity every 500ms

                // Stop the pulse effect after the specified duration
                setTimeout(() => {
                    clearInterval(intervalId);
                    mainContainer.style.opacity = '1'; // Reset to fully visible
                }, duration);
            }

        // Run the function when the page loads
        window.addEventListener('load', positionMainContainer);

        // Run the function whenever the window is resized

        function debounce(func, wait = 20, immediate = true) {
            let timeout;
            return function() {
                const context = this, args = arguments;
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }


        // Oberserver - creates effect where nav bolds when it crosses the boundary of its related h4
        let observer;

      function setupIntersectionObserver() {
        // Keep
        // console.log('IntersectionObserver called');
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
        const handleIntersect = (entries) => {
            entries.forEach(entry => {
                // Keeping for troubleshooting the handleIntersect
                // console.log('Observing entry:', entry.target.id, 'Is intersecting:', entry.isIntersecting, 'Intersection ratio:', entry.intersectionRatio);
                if (entry.isIntersecting) {
                    // Clear previous active list items
                    const tocListItems = document.querySelectorAll('.list-wrapper li');
                    tocListItems.forEach(li => li.classList.remove('active'));

                    const activeLink = document.querySelector(`.list-wrapper li a[href="#${entry.target.id}"]`);
                    if (activeLink) {
                        // Keep
                        // console.log('Setting active:', activeLink.parentElement);
                        activeLink.parentElement.classList.add('active');
                    }
                }
            });
        };

        const options = {
            rootMargin: '-10px 0px 0px 0px', // Adjust the top margin to handle elements near the top of the page
            threshold: 0.1 // Consider multiple thresholds
        };

        observer = new IntersectionObserver(handleIntersect, options);

        // Observe each section
        sections.forEach(section => { 
            // Keep
            // console.log('Observing section:', section.id);
            observer.observe(section);
    });
    } // END setupIntersectionObserver

    window.addEventListener('load', handleResize);


    function debounce(func, wait = 100) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

// Combine the functions into one debounced handler
function handleResize() {
    positionMainContainer();
    setupIntersectionObserver();
}

window.addEventListener('resize', debounce(handleResize, 100));

        /* ADMIN  */

        //https://medium.com/@hbahonar/how-to-create-wordpress-custom-admin-page-and-menu-from-scratch-ultimate-guide-updated-d7b4d2e57f96

        // https://developer.wordpress.org/plugins/settings/

    });
    </script>
    <?php
    }
}

add_action('wp', 'waypoint826_run');






